<?php

namespace App\Services;

use App\Jobs\SendBrowserPush;
use App\Models\PropertyRequest;
use App\Models\User;
use App\Models\WebPushSubscription;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

class RequestNotificationService
{
    public const STAGES = [
        'inspection' => ['Inspection', 'inspection_date', 'inspection_completed_at'],
        'work_order' => ['Work', 'work_order_start_date', 'work_order_completed_at'],
        'service_report' => ['Service Report', 'service_report_date', 'service_report_completed_at'],
    ];

    public function created(PropertyRequest $request): void
    {
        $recipients = $request->assignment_type !== 'dial_a' ? $this->pmUsers() : $this->dialAUsers();
        $this->send($request, $recipients, 'new_request', $request->isAwaitingPmReview() ? 'PM review required' : 'New request',
            "{$request->reference_no}: {$request->request_type} requested by {$request->submitter_name} ({$request->branch}).",
            'created');
    }

    public function updated(PropertyRequest $request): void
    {
        $recipients = $this->stakeholders($request);
        $event = (string) Str::uuid();

        if ($request->wasChanged('assignment_phase')) {
            if ($request->hasProceeded()) {
                $users = $request->isInHouse() ? $recipients : $recipients->merge($this->dialAUsers())->unique('id');
                $label = $request->isInHouse() ? 'In house' : 'Dial-A';
                $this->send($request, $users, 'assignment_proceeded', 'Assignment confirmed',
                    "{$request->reference_no}: Proceed confirmed for {$label}. Priority: ".ucfirst($request->priority).'. Remarks: '.$request->priority_remarks,
                    "{$event}:proceeded");
            } else {
                $label = $request->isAssignmentStaged() ? 'Assigned — awaiting Proceed' : 'Assignment reopened for PM review';
                $this->send($request, $recipients, 'assignment_changed', $label,
                    "{$request->reference_no}: {$label}. Priority: ".ucfirst($request->priority).'. Remarks: '.$request->priority_remarks,
                    "{$event}:assignment");
            }
            $this->retireReminders($request);
            return;
        }

        if ($request->wasChanged('assignment_type')) {
            $inHouse = $request->assignment_type === 'in_house';
            $label = $inHouse ? 'In house' : 'Dial-A';
            $assignmentRecipients = $inHouse ? $recipients : $recipients->merge($this->dialAUsers())->unique('id');
            $this->send($request, $assignmentRecipients, 'assignment_changed', $request->getOriginal('assignment_type') === 'pending_review' ? 'PM review completed' : 'Request reassigned',
                "{$request->reference_no}: Assigned to {$label}".$this->careOf($request, 'assignment').'. Priority: '.ucfirst($request->priority).'.'.($request->priority_remarks ? ' Remarks: '.$request->priority_remarks : ''),
                "{$event}:assignment", ['assignment_type' => $request->assignment_type]);
            $this->retireReminders($request);

            // Switching routes may restore an earlier Dial-A completion; it is not a new completion.
            return;
        }

        if ($request->assignment_type === 'in_house') {
            $this->inHouseUpdated($request, $recipients, $event);

            return;
        }

        $changes = [];
        $completedStages = [];
        $requestCompleted = $request->wasChanged('completed_at') && $request->completed_at && ! $request->getOriginal('completed_at');
        foreach (self::STAGES as $stage => [$label, $schedule, $completed]) {
            $stageCompleted = $request->wasChanged($completed) && $request->$completed && ! $request->getOriginal($completed);
            if ($request->wasChanged($schedule)) {
                $before = $request->getOriginal($schedule)?->format('M d, Y') ?? 'Not scheduled';
                $after = $request->$schedule?->format('M d, Y') ?? 'Not scheduled';
                $changes[] = "{$label}: {$before} → {$after}";
            }
            if ($stageCompleted) {
                $completedStages[$stage] = $label;
            }
        }
        if ($completedStages !== []) {
            // A completion form also saves its date and may close the entire request.
            // Describe those effects in the same alert, rather than sending extra alerts.
            $label = implode(', ', $completedStages);
            $message = "{$request->reference_no}: {$label} completed.";
            if ($requestCompleted) {
                $message .= ' All stages are complete and the request is now completed.';
            }
            if ($changes !== []) {
                $message .= ' Dates recorded: '.implode('; ', $changes).'.';
            }
            $this->send($request, $recipients, 'stage_completed', "{$label} completed", $message,
                "{$event}:completion", ['stage' => array_key_last($completedStages), 'request_completed' => (bool) $requestCompleted]);
        } elseif ($requestCompleted) {
            $message = "{$request->reference_no}: All stages are complete and Dial-A has confirmed completion.";
            if ($changes !== []) {
                $message .= ' Dates recorded: '.implode('; ', $changes).'.';
            }
            $this->send($request, $recipients, 'request_completed', 'Request completed', $message, "{$event}:finished");
        } elseif ($changes !== []) {
            $this->send($request, $recipients, 'schedule_changed', 'Schedule changed',
                $request->reference_no.': '.implode('; ', $changes).'.', "{$event}:schedule");
        }
        // Retire reminders when a stage, schedule, or final completion changes.
        $reminderFields = ['completed_at'];
        foreach (self::STAGES as [, $schedule, $completed]) {
            array_push($reminderFields, $schedule, $completed);
        }
        if ($request->wasChanged($reminderFields) || ($request->wasChanged('status') && $request->status === 'completed')) {
            $this->retireReminders($request);
        }
    }

    private function inHouseUpdated(PropertyRequest $request, Collection $recipients, string $event): void
    {
        $stages = [
            'in_house_requested_at' => ['inspection', 'Inspection requested', 'in_house_inspection'],
            'in_house_work_order_at' => ['work_order', 'Work Order recorded', 'in_house_work_order'],
            'in_house_completed_at' => ['completion_report', 'Completion Report submitted', 'in_house_completion'],
        ];

        foreach ($stages as $field => [$stage, $label, $actorPrefix]) {
            if (! $request->wasChanged($field) || ! $request->$field) {
                continue;
            }

            $completed = $stage === 'completion_report' && (bool) $request->completed_at;
            $message = "{$request->reference_no}: In house {$label}".$this->careOf($request, $actorPrefix).'.';
            if ($completed) {
                $message .= ' The request is now completed.';
            }
            $this->send($request, $recipients, 'in_house_updated', $label, $message, "{$event}:{$stage}", [
                'stage' => $stage,
                'assignment_type' => 'in_house',
                'request_completed' => $completed,
            ]);
        }

        if ($request->wasChanged([...array_keys($stages), 'completed_at', 'status'])) {
            $this->retireReminders($request);
        }
    }

    private function careOf(PropertyRequest $request, string $prefix): string
    {
        $role = $request->getAttribute($prefix.'_by_role');
        $name = $request->getAttribute($prefix.'_by_name');
        if (! $role && ! $name) {
            return '';
        }

        $label = User::ROLES[$role] ?? 'PM user';

        return ' c/o '.$label.($name ? ' ('.$name.')' : '');
    }

    private function retireReminders(PropertyRequest $request): void
    {
        DatabaseNotification::where('property_request_id', $request->id)->whereNull('read_at')
            ->whereIn('data->kind', ['upcoming', 'ageing'])->update(['read_at' => now()]);
    }

    public function priorityChanged(PropertyRequest $request, string $oldPriority, string $newPriority, ?string $remarks = null): void
    {
        $recipients = User::where('is_active', true)->where('role', 'dealer')->where(function (Builder $query) use ($request) {
            $query->whereKey($request->submitted_by);
            if ($request->dealer_id) {
                $query->orWhere('dealer_id', $request->dealer_id);
            }
        })->get();

        if ($recipients->isEmpty()) {
            return;
        }

        $event = (string) Str::uuid();
        $oldLabel = ucfirst($oldPriority);
        $newLabel = ucfirst($newPriority);

        $message = "{$request->reference_no}: Priority was changed from {$oldLabel} to {$newLabel}.";
        if ($remarks) {
            $message .= " Remarks: {$remarks}";
        }

        $this->send(
            $request,
            $recipients,
            'priority_changed',
            'Priority updated',
            $message,
            "{$event}:priority",
            [
                'old_priority' => $oldPriority,
                'new_priority' => $newPriority,
                'remarks' => $remarks,
                'reference_no' => $request->reference_no,
            ]
        );
    }

    public function reminders(): void
    {
        $recipients = $this->dialAUsers();
        $pmRecipients = $this->pmUsers();
        if ($recipients->isEmpty() && $pmRecipients->isEmpty()) {
            return;
        }

        PropertyRequest::where('status', '!=', 'completed')->whereNull('completed_at')
            ->chunkById(100, function ($requests) use ($recipients, $pmRecipients) {
                foreach ($requests as $request) {
                    DB::transaction(function () use ($request, $recipients, $pmRecipients) {
                        // Serialize against schedule/completion updates so stale reminders cannot outlive them.
                        $request = PropertyRequest::whereKey($request->id)->lockForUpdate()->first();
                        if (! $request || $request->status === 'completed' || $request->completed_at) {
                            return;
                        }
                        if (! $request->hasProceeded()) {
                            if ($request->is_overdue) {
                                $this->sendReminder($request, $pmRecipients, 'pm_review', 'ageing', 'PM review pending',
                                    "{$request->reference_no}: Review the priority, remarks, and assignment before work begins.");
                            }
                            return;
                        }
                        if ($request->assignment_type === 'in_house') {
                            $this->inHouseReminder($request, $pmRecipients);

                            return;
                        }
                        foreach (self::STAGES as $stage => [$label, $schedule, $completed]) {
                            if ($request->$completed) {
                                continue;
                            }
                            $ready = match ($stage) {
                                'work_order' => (bool) $request->inspection_completed_at,
                                'service_report' => (bool) $request->work_order_completed_at,
                                default => true,
                            };
                            $date = $request->$schedule;
                            if (! $date && $ready && $stage === 'service_report') {
                                $date = $request->work_order_completed_at->copy()->startOfDay();
                            }
                            if ($request->is_overdue && $ready) {
                                $this->sendReminder($request, $recipients, $stage, 'ageing', "{$label} past due",
                                    "{$request->reference_no}: {$label} is unfinished. This request is more than 4 calendar days old (submitted {$request->request_date->format('M d, Y')}).");
                            } elseif ($date && $date->betweenIncluded(today(), today()->addDay())) {
                                $this->sendReminder($request, $recipients, $stage, 'upcoming', "Upcoming {$label}",
                                    "{$request->reference_no}: {$label} is due {$date->format('M d, Y')}.");
                            }
                            // Ageing concerns the current unfinished step; future scheduled steps may still be upcoming.
                            if ($ready && $request->is_overdue) {
                                break;
                            }
                        }
                        if ($request->service_report_completed_at && $request->is_overdue) {
                            $this->sendReminder($request, $recipients, 'confirmation', 'ageing', 'Request past due: confirmation needed',
                                "{$request->reference_no}: The service report is complete, but final confirmation is outstanding. This request is more than 4 calendar days old.");
                        }
                    });
                }
            });
    }

    private function sendReminder(PropertyRequest $request, Collection $users, string $stage, string $kind, string $title, string $message): void
    {
        // One reminder per stage, day, and schedule/state. Reading or polling cannot recreate it.
        $state = md5(json_encode($request->only([
            'inspection_date', 'work_order_start_date', 'service_report_date',
            'inspection_completed_at', 'work_order_completed_at', 'service_report_completed_at',
            'assignment_type', 'assigned_at', 'in_house_requested_at', 'in_house_work_order_at', 'in_house_completed_at',
        ])));
        $this->send($request, $users, $kind, $title, $message, "{$kind}:{$stage}:".today()->toDateString().":{$state}");
    }

    private function dialAUsers(): Collection
    {
        return User::where('is_active', true)->whereIn('role', ['dial_a', 'pm_support', 'dial_lead'])->get();
    }

    private function pmUsers(): Collection
    {
        return User::where('is_active', true)->whereIn('role', ['pm_manager', 'pm_admin'])->get();
    }

    private function inHouseReminder(PropertyRequest $request, Collection $recipients): void
    {
        if (! $request->is_overdue) {
            return;
        }

        [$stage, $label] = match (true) {
            ! $request->in_house_requested_at => ['inspection', 'Inspection Request'],
            ! $request->in_house_work_order_at => ['work_order', 'Work Order'],
            default => ['completion_report', 'Completion Report'],
        };
        $this->sendReminder($request, $recipients, $stage, 'ageing', "In house {$label} past due",
            "{$request->reference_no}: In house {$label} is unfinished. This request is more than 4 calendar days old (submitted {$request->request_date->format('M d, Y')}).");
    }

    private function stakeholders(PropertyRequest $request): Collection
    {
        // Dealer access is scoped to submitted_by throughout PMS, including notification destinations.
        return User::where('is_active', true)->where(function (Builder $query) use ($request) {
            $query->whereIn('role', ['pm_manager', 'pm_admin'])->orWhere(fn (Builder $dealer) => $dealer->where('role', 'dealer')->whereKey($request->submitted_by));
        })->get();
    }

    private function send(PropertyRequest $request, Collection $users, string $kind, string $title, string $message, string $event, array $details = []): void
    {
        foreach ($users as $user) {
            if ($user->isAdmin() || ! $user->is_active) {
                continue;
            }
            // A deterministic primary key prevents duplicate alerts from concurrent reminder runs.
            $id = Uuid::uuid5(Uuid::NAMESPACE_URL, "pms:{$request->id}:{$request->created_at->toISOString()}:{$user->id}:{$event}")->toString();
            $notification = $user->notifications()->firstOrCreate(['id' => $id], [
                'type' => 'request_workflow',
                'property_request_id' => $request->id,
                'data' => array_merge(compact('kind', 'title', 'message'), $details),
            ]);
            if ($notification->wasRecentlyCreated && WebPushService::configured()) {
                WebPushSubscription::where('user_id', $user->id)->each(fn ($device) => SendBrowserPush::dispatch($device->id, $user->id, $notification->id));
            }
        }
    }
}
