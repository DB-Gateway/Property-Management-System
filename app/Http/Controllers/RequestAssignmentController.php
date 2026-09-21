<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\PropertyRequest;
use App\Models\User;
use App\Support\PmActionConfirmation;
use App\Services\RequestNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class RequestAssignmentController extends Controller
{
    public function update(Request $request, PropertyRequest $propertyRequest)
    {
        $this->ensurePmUser($request);
        PmActionConfirmation::validate($request);
        $validated = $request->validate([
            'assignment_type' => ['required', Rule::in(['dial_a', 'in_house'])],
            'priority' => ['sometimes', 'required', Rule::in(['regular', 'urgent'])],
            'remarks' => ['sometimes', 'required', 'string', 'max:1000'],
        ], $this->passwordMessages());

        return DB::transaction(function () use ($request, $propertyRequest, $validated) {
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $isReview = $propertyRequest->isAwaitingPmReview();
            if ($request->boolean('review_pending') && ! $isReview) {
                throw ValidationException::withMessages(['review' => 'Another PM user has already reviewed this request. Refresh to see their decision before making changes.']);
            }
            if ($isReview) {
                $request->validate([
                    'priority' => ['required', Rule::in(['regular', 'urgent'])],
                    'remarks' => ['required', 'string', 'max:1000'],
                ]);
            }
            $previousType = $propertyRequest->assignment_type;
            $oldPriority = $propertyRequest->priority;
            $decisionChanged = (isset($validated['priority']) && $validated['priority'] !== $propertyRequest->priority)
                || (isset($validated['remarks']) && $validated['remarks'] !== $propertyRequest->priority_remarks);
            if ($previousType === $validated['assignment_type'] && ! $decisionChanged) {
                return $this->redirect($propertyRequest, 'Assignment is unchanged.');
            }

            $actor = $request->user();
            $previousState = $propertyRequest->only(['status', 'completed_at', 'in_house_completed_at']);
            $updates = [
                'assignment_type' => $validated['assignment_type'],
                'assigned_at' => now(),
                ...$this->actorSnapshot('assignment', $actor),
            ];
            if (isset($validated['priority'])) {
                $updates['priority'] = $validated['priority'];
            }
            if (isset($validated['remarks'])) {
                $updates['priority_remarks'] = $validated['remarks'];
            }
            if ($isReview) {
                $updates += [
                    'pm_reviewed_at' => now(),
                    'pm_reviewed_by_id' => $actor->id,
                    'pm_reviewed_by_name' => $actor->name,
                    'pm_reviewed_by_role' => $actor->role,
                ];
            }

            if ($validated['assignment_type'] === 'in_house' && $previousType !== 'in_house') {
                $updates += [
                    'dial_a_status' => $propertyRequest->status,
                    'dial_a_completed_at' => $propertyRequest->completed_at,
                    'in_house_requested_at' => now(),
                    ...$this->actorSnapshot('in_house_inspection', $actor),
                    // A resumed assignment requires a fresh, explicit completion.
                    'in_house_completed_at' => null,
                    'status' => 'on_going',
                    'completed_at' => null,
                ];
            } elseif ($validated['assignment_type'] === 'dial_a' && $previousType !== 'dial_a') {
                $updates += $this->dialAState($propertyRequest);
                if (! $propertyRequest->assigned_support_id) {
                    $updates['assigned_support_id'] = User::whereIn('role', ['dial_a', 'pm_support', 'dial_lead'])
                        ->where('is_active', true)->orderBy('id')->value('id');
                }
            }

            $propertyRequest->update($updates);
            $label = $propertyRequest->isInHouse() ? 'In house' : 'Dial-A';
            AuditLog::record(
                $isReview ? 'request_pm_reviewed' : 'request_assignment_changed',
                "{$propertyRequest->reference_no} was assigned to {$label} by {$actor->role_label} {$actor->name}.",
                $propertyRequest,
                [
                    'previous_assignment_type' => $previousType,
                    'assignment_type' => $propertyRequest->assignment_type,
                    'previous_state' => $previousState,
                    'actor' => $this->actorSnapshot('assignment', $actor),
                    'saved_workflow_and_files_retained' => true,
                    'priority' => $propertyRequest->priority,
                    'remarks' => $propertyRequest->priority_remarks,
                ]
            );
            if ($previousType === $validated['assignment_type'] && $decisionChanged) {
                app(RequestNotificationService::class)->priorityChanged($propertyRequest, $oldPriority, $propertyRequest->priority, $propertyRequest->priority_remarks);
            }

            return $this->redirect($propertyRequest, "Request assigned to {$label}.".($propertyRequest->isInHouse() ? ' Inspection Request recorded.' : ' The Dial-A workflow has been restored.'));
        });
    }

    public function workOrder(Request $request, PropertyRequest $propertyRequest)
    {
        $this->ensurePmUser($request);
        PmActionConfirmation::validate($request);
        if (is_string($request->input('in_house_work_order'))) {
            $request->merge(['in_house_work_order' => trim($request->input('in_house_work_order'))]);
        }
        $validated = $request->validate([
            'in_house_work_order' => ['required', 'string', 'max:20000'],
        ], $this->passwordMessages());

        return DB::transaction(function () use ($request, $propertyRequest, $validated) {
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureInHouse($propertyRequest);
            if ($propertyRequest->in_house_completed_at || $propertyRequest->completed_at) {
                throw ValidationException::withMessages(['in_house_work_order' => 'This In house request is already completed. Reassign it before changing the Work Order.']);
            }
            if ($propertyRequest->in_house_work_order === $validated['in_house_work_order'] && $propertyRequest->in_house_work_order_at) {
                return $this->redirect($propertyRequest, 'Work Order is unchanged.');
            }

            $previousWorkOrder = $propertyRequest->in_house_work_order;
            $propertyRequest->update([
                'in_house_work_order' => $validated['in_house_work_order'],
                'in_house_work_order_at' => now(),
                ...$this->actorSnapshot('in_house_work_order', $request->user()),
                'status' => 'on_going',
            ]);
            AuditLog::record(
                'in_house_work_order_saved',
                "{$propertyRequest->reference_no} In house Work Order was saved by {$request->user()->role_label} {$request->user()->name}.",
                $propertyRequest,
                ['previous_work_order' => $previousWorkOrder, 'work_order' => $validated['in_house_work_order'], 'actor' => $this->actorSnapshot('in_house_work_order', $request->user())]
            );

            return $this->redirect($propertyRequest, 'In house Work Order saved. A Completion Report can now be uploaded.');
        });
    }

    public function completion(Request $request, PropertyRequest $propertyRequest)
    {
        $this->ensurePmUser($request);
        PmActionConfirmation::validate($request);
        $validated = $request->validate([
            'completion_files' => ['required', 'array', 'min:1', 'max:5'],
            'completion_files.*' => [
                'required', 'file', 'max:20480',
                'extensions:jpeg,jpg,png,webp,pdf,doc,docx,xls,xlsx,csv',
                function ($attribute, $file, $fail) {
                    if (! $file instanceof \Illuminate\Http\UploadedFile || ! $file->isValid()) {
                        return;
                    }
                    $allowedMimes = [
                        'jpeg' => ['image/jpeg'], 'jpg' => ['image/jpeg'],
                        'png' => ['image/png'], 'webp' => ['image/webp'], 'pdf' => ['application/pdf'],
                        'doc' => ['application/msword', 'application/vnd.ms-office', 'application/x-ole-storage'],
                        'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
                        'xls' => ['application/vnd.ms-excel', 'application/vnd.ms-office', 'application/x-ole-storage'],
                        'xlsx' => ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'],
                        'csv' => ['text/plain', 'text/csv', 'application/csv', 'application/vnd.ms-excel'],
                    ];
                    $extension = strtolower($file->getClientOriginalExtension());
                    if (! in_array($file->getMimeType(), $allowedMimes[$extension] ?? [], true)) {
                        $fail('The Completion Report file contents must match its image, PDF, Word, or Excel file extension.');
                    }
                },
            ],
        ], $this->passwordMessages() + [
            'completion_files.required' => 'Upload at least one Completion Report file.',
            'completion_files.*.max' => 'Each Completion Report attachment may not exceed 20 MB.',
        ]);

        $storedPaths = [];
        try {
            return DB::transaction(function () use ($request, $propertyRequest, $validated, &$storedPaths) {
                $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
                $this->ensureInHouse($propertyRequest);
                if (! $propertyRequest->in_house_work_order_at || ! filled($propertyRequest->in_house_work_order)) {
                    throw ValidationException::withMessages(['in_house_work_order' => 'Save the In house Work Order before uploading the Completion Report.']);
                }
                if ($propertyRequest->in_house_completed_at || $propertyRequest->completed_at) {
                    throw ValidationException::withMessages(['completion_files' => 'This In house request has already been completed.']);
                }

                $attachmentIds = [];
                foreach ($validated['completion_files'] as $file) {
                    $path = $file->store('request-attachments/in_house_completion');
                    if ($path === false) {
                        throw ValidationException::withMessages(['completion_files' => 'The Completion Report could not be saved. Please try again.']);
                    }
                    $storedPaths[] = $path;
                    $attachmentIds[] = $propertyRequest->attachments()->create([
                        'category' => 'in_house_completion',
                        'path' => $path,
                        'original_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'uploaded_by_id' => $request->user()->id,
                        'uploaded_by_name' => $request->user()->name,
                        'uploaded_by_role' => $request->user()->role,
                    ])->id;
                }
                $completedAt = now();
                $propertyRequest->update([
                    'in_house_completed_at' => $completedAt,
                    ...$this->actorSnapshot('in_house_completion', $request->user()),
                    'status' => 'completed',
                    'completed_at' => $completedAt,
                ]);
                AuditLog::record(
                    'in_house_request_completed',
                    "{$propertyRequest->reference_no} In house Completion Report was uploaded and the request completed by {$request->user()->role_label} {$request->user()->name}.",
                    $propertyRequest,
                    ['attachment_ids' => $attachmentIds, 'actor' => $this->actorSnapshot('in_house_completion', $request->user())]
                );

                return $this->redirect($propertyRequest, 'Completion Report uploaded. In house request marked as Completed.');
            });
        } catch (Throwable $exception) {
            if ($storedPaths !== []) {
                Storage::delete($storedPaths);
            }
            throw $exception;
        }
    }

    private function ensurePmUser(Request $request): void
    {
        abort_unless($request->user()->isManager(), 403, 'Only PM Manager and PM Admin may manage request assignments and the In house workflow.');
    }

    private function ensureInHouse(PropertyRequest $propertyRequest): void
    {
        if (! $propertyRequest->isInHouse()) {
            throw ValidationException::withMessages(['assignment_type' => 'This request is assigned to Dial-A. Refresh the page before continuing.']);
        }
    }

    private function actorSnapshot(string $prefix, User $actor): array
    {
        return [
            $prefix.'_by_id' => $actor->id,
            $prefix.'_by_name' => $actor->name,
            $prefix.'_by_role' => $actor->role,
        ];
    }

    private function dialAState(PropertyRequest $propertyRequest): array
    {
        if ($propertyRequest->dial_a_completed_at) {
            return ['status' => 'completed', 'completed_at' => $propertyRequest->dial_a_completed_at];
        }

        $hasProgress = $propertyRequest->inspection_completed_at || $propertyRequest->work_order_completed_at
            || $propertyRequest->service_report_completed_at || $propertyRequest->inspection_date
            || $propertyRequest->work_order_start_date || $propertyRequest->service_report_date;
        $savedStatus = $propertyRequest->dial_a_status;
        $status = in_array($savedStatus, ['on_going', 'in_progress', 'awaiting_dealer'], true)
            ? $savedStatus : ($hasProgress ? 'on_going' : 'pending');

        return ['status' => $status, 'completed_at' => null];
    }

    private function passwordMessages(): array
    {
        return [
            'current_password.required' => 'Enter your own account password to confirm this action.',
            'current_password.current_password' => 'The password does not match your signed-in account.',
        ];
    }

    private function redirect(PropertyRequest $propertyRequest, string $message)
    {
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => $message, 'redirect' => route('requests.show', $propertyRequest).'#request-assignment']);
        }
        return redirect()->route('requests.show', $propertyRequest)->withFragment('request-assignment')->with('status', $message);
    }
}
