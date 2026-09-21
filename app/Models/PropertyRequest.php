<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Carbon;

class PropertyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_no', 'dealer_id', 'dealer_name', 'submitted_by', 'assigned_support_id', 'assigned_manager_id',
        'submitter_name', 'designation', 'branch', 'area', 'request_type',
        'priority', 'priority_remarks', 'description', 'request_date', 'due_date', 'approved_date', 'inspection_date', 'inspection_end_date',
        'inspection_start_time', 'inspection_end_time',
        'representative_1', 'representative_2', 'representative_3', 'inspection_completed_at',
        'work_order_start_date', 'work_order_end_date', 'work_order_start_time', 'work_order_end_time', 'work_order_representatives', 'work_order_completed_at',
        'service_report_date', 'service_report_completed_at', 'completion_notified_at', 'status', 'completed_at',
        'assignment_type', 'assignment_by_id', 'assignment_by_name', 'assignment_by_role', 'assigned_at',
        'in_house_inspection_by_id', 'in_house_inspection_by_name', 'in_house_inspection_by_role', 'in_house_requested_at',
        'in_house_work_order', 'in_house_work_order_by_id', 'in_house_work_order_by_name', 'in_house_work_order_by_role', 'in_house_work_order_at',
        'in_house_completion_by_id', 'in_house_completion_by_name', 'in_house_completion_by_role', 'in_house_completed_at',
        'dial_a_status', 'dial_a_completed_at',
        'pm_reviewed_at', 'pm_reviewed_by_id', 'pm_reviewed_by_name', 'pm_reviewed_by_role',
        'assignment_phase', 'assignment_proceeded_at',
    ];

    protected $attributes = ['assignment_type' => 'dial_a', 'assignment_phase' => 'proceeded'];

    public function isAwaitingPmReview(): bool
    {
        return $this->assignment_type === 'pending_review' || $this->assignment_phase === 'unassigned';
    }

    public function hasProceeded(): bool
    {
        return ! $this->isAwaitingPmReview() && $this->assignment_phase === 'proceeded';
    }

    public function isAssignmentStaged(): bool
    {
        return $this->assignment_phase === 'assigned';
    }

    public function getPriorityLabelAttribute(): string
    {
        return $this->isAwaitingPmReview() ? 'Awaiting PM review' : ucfirst($this->priority);
    }

    public function getPmReviewerLabelAttribute(): ?string
    {
        return $this->actorLabel('pm_reviewed');
    }

    public function isInHouse(): bool
    {
        return $this->assignment_type === 'in_house';
    }

    public function getAssignmentByLabelAttribute(): ?string
    {
        return $this->actorLabel('assignment');
    }

    public function getInHouseInspectionByLabelAttribute(): ?string
    {
        return $this->actorLabel('in_house_inspection');
    }

    public function getInHouseWorkOrderByLabelAttribute(): ?string
    {
        return $this->actorLabel('in_house_work_order');
    }

    public function getInHouseCompletionByLabelAttribute(): ?string
    {
        return $this->actorLabel('in_house_completion');
    }

    private function actorLabel(string $prefix): ?string
    {
        $role = $this->getAttribute($prefix.'_by_role');
        $name = $this->getAttribute($prefix.'_by_name');

        return $role ? (User::ROLES[$role] ?? $role).($name ? ' — '.$name : '') : null;
    }

    public function getDisplayDealerNameAttribute(): string
    {
        return $this->dealer_name ?: ($this->dealer?->brand ?: ($this->dealer?->name ?: 'Dealer'));
    }

    public function getDisplayBranchAttribute(): string
    {
        return $this->branch ?: ($this->dealer?->city ?: ($this->dealer?->name ?: ''));
    }

    public function getInspectionStartTimeLabelAttribute(): ?string
    {
        return $this->inspection_start_time
            ? Carbon::parse($this->inspection_start_time)->format('h:i A')
            : null;
    }

    public function getInspectionEndTimeLabelAttribute(): ?string
    {
        return $this->inspection_end_time
            ? Carbon::parse($this->inspection_end_time)->format('h:i A')
            : null;
    }

    public function getWorkOrderStartTimeLabelAttribute(): ?string
    {
        return $this->work_order_start_time
            ? Carbon::parse($this->work_order_start_time)->format('h:i A')
            : null;
    }

    public function getWorkOrderEndTimeLabelAttribute(): ?string
    {
        return $this->work_order_end_time
            ? Carbon::parse($this->work_order_end_time)->format('h:i A')
            : null;
    }

    protected function casts(): array
    {
        return [
            'request_date' => 'date',
            'due_date' => 'date',
            'approved_date' => 'date',
            'inspection_date' => 'date',
            'inspection_end_date' => 'date',
            'inspection_completed_at' => 'datetime',
            'work_order_start_date' => 'date',
            'work_order_end_date' => 'date',
            'work_order_representatives' => 'array',
            'work_order_completed_at' => 'datetime',
            'service_report_completed_at' => 'datetime',
            'service_report_date' => 'date',
            'completion_notified_at' => 'datetime',
            'completed_at' => 'datetime',
            'assigned_at' => 'datetime',
            'in_house_requested_at' => 'datetime',
            'in_house_work_order_at' => 'datetime',
            'in_house_completed_at' => 'datetime',
            'dial_a_completed_at' => 'datetime',
            'pm_reviewed_at' => 'datetime',
            'assignment_proceeded_at' => 'datetime',
        ];
    }

    public function dealer()
    {
        return $this->belongsTo(Dealer::class);
    }

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by')->withTrashed();
    }

    public function assignedSupport()
    {
        return $this->belongsTo(User::class, 'assigned_support_id')->withTrashed();
    }

    public function assignedManager()
    {
        return $this->belongsTo(User::class, 'assigned_manager_id')->withTrashed();
    }

    public function attachments()
    {
        return $this->hasMany(RequestAttachment::class);
    }

    public function requestFiles()
    {
        return $this->hasMany(RequestAttachment::class)->where('category', 'request');
    }

    public function inspectionFiles()
    {
        return $this->hasMany(RequestAttachment::class)->where('category', 'inspection');
    }

    public function workOrderFiles()
    {
        return $this->hasMany(RequestAttachment::class)->where('category', 'work_order');
    }

    public function serviceReportFiles()
    {
        return $this->hasMany(RequestAttachment::class)->where('category', 'service_report');
    }

    public function inHouseCompletionFiles()
    {
        return $this->hasMany(RequestAttachment::class)->where('category', 'in_house_completion');
    }

    public function notifications()
    {
        return $this->hasMany(DatabaseNotification::class, 'property_request_id');
    }

    public function isAssignedTo(User $user): bool
    {
        return $this->hasProceeded() && $this->assignment_type === 'dial_a' && $user->isDialA() && ($this->assigned_support_id === null || $this->assigned_support_id === $user->id);
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->status !== 'completed' && ! $this->completed_at && $this->ageing_due_date->isBefore(today());
    }

    public function getAgeingDueDateAttribute(): Carbon
    {
        return $this->request_date->copy()->addDays(4);
    }

    public function scopeOverdue(Builder $query): void
    {
        $query->where('status', '!=', 'completed')->whereNull('completed_at')
            ->whereDate('request_date', '<', today()->subDays(4));
    }

    public function scopeIncomplete(Builder $query): void
    {
        $query->where('status', '!=', 'completed')->whereNull('completed_at');
    }

    public function scopeNotAcknowledged(Builder $query): void
    {
        $query->incomplete()
            ->where('status', 'pending')
            ->whereNull('inspection_completed_at')
            ->whereNull('work_order_completed_at')
            ->whereNull('service_report_completed_at')
            ->where(fn (Builder $review) => $review->where('assignment_type', 'pending_review')
                ->orWhereDoesntHave('notifications', fn (Builder $notification) => $notification
                    ->where('data->kind', 'new_request')->whereNotNull('read_at')));
    }

    public function scopePendingRequest(Builder $query): void
    {
        $query->incomplete()->where('assignment_type', '!=', 'pending_review')->where(function (Builder $pending) {
            $pending->where('status', '!=', 'pending')
                ->orWhereNotNull('inspection_completed_at')
                ->orWhereNotNull('work_order_completed_at')
                ->orWhereNotNull('service_report_completed_at')
                ->orWhereHas('notifications', fn (Builder $notification) => $notification
                    ->where('data->kind', 'new_request')
                    ->whereNotNull('read_at'));
        });
    }

    public function scopeAtWorkflowStage(Builder $query, string $stage): void
    {
        $query->pendingRequest();
        $query->where(function (Builder $routes) use ($stage) {
            foreach ([false, true] as $inHouse) {
                $routes->orWhere(function (Builder $route) use ($stage, $inHouse) {
                    $route->where('assignment_type', $inHouse ? 'in_house' : 'dial_a');
                    $inspection = $inHouse ? 'in_house_requested_at' : 'inspection_completed_at';
                    $workOrder = $inHouse ? 'in_house_work_order_at' : 'work_order_completed_at';
                    $report = $inHouse ? 'in_house_completed_at' : 'service_report_completed_at';
                    match ($stage) {
                        'inspection' => $route->whereNull($inspection)->whereNull($workOrder)->whereNull($report),
                        'work_order' => $route->whereNotNull($inspection)->whereNull($workOrder)->whereNull($report),
                        'service_report' => $route->whereNotNull($workOrder)->whereNull($report),
                        default => $route->whereRaw('1 = 0'),
                    };
                });
            }
        });
    }

    public function isAwaitingDialLeadCompletion(): bool
    {
        return $this->isAwaitingDialACompletion();
    }

    public function isAwaitingDialACompletion(): bool
    {
        if (! $this->hasProceeded() || $this->isInHouse()) {
            return false;
        }

        $hasServiceReport = ! empty($this->service_report_completed_at)
            || ($this->relationLoaded('serviceReportFiles') ? $this->serviceReportFiles->isNotEmpty() : $this->serviceReportFiles()->exists());

        return $hasServiceReport && empty($this->completed_at);
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->isAwaitingPmReview()) {
            return 'Awaiting PM Review';
        }
        if ($this->isAssignmentStaged()) {
            return 'Assigned — Awaiting Proceed';
        }
        if ($this->isAwaitingDialACompletion()) {
            return 'Awaiting Dial-A Confirmation';
        }

        return match ($this->status) {
            'on_going', 'in_progress' => 'On-going',
            'awaiting_dealer' => 'Awaiting Dealer',
            'completed' => 'Completed',
            default => 'Pending',
        };
    }

    /** @return array<int, array{name: string, status: string, label: string}> */
    public function getActivityProgressAttribute(): array
    {
        if ($this->isAwaitingPmReview()) {
            return [
                $this->progressStep('PM Review', false, true),
                $this->progressStep('Inspection', false, false),
                $this->progressStep('Work Order', false, false),
                $this->progressStep('Completion', false, false),
            ];
        }
        if ($this->isAssignmentStaged()) {
            return [
                $this->progressStep('Assigned', true, true),
                $this->progressStep('Proceed', false, true),
                $this->progressStep('Work Order', $this->isInHouse() && (bool) $this->in_house_work_order_at, false),
                $this->progressStep('Completion', false, false),
            ];
        }
        if ($this->isInHouse()) {
            $inspectionDone = (bool) $this->in_house_requested_at;
            $workOrderDone = (bool) $this->in_house_work_order_at;
            $isCompleted = $this->status === 'completed' && (bool) $this->in_house_completed_at;

            return [
                $this->progressStep('Inspection Request', $inspectionDone, true),
                $this->progressStep('Work Order', $workOrderDone, $inspectionDone),
                $this->progressStep('Completion Report', $isCompleted, $workOrderDone),
                $this->progressStep('Completed', $isCompleted, $isCompleted),
            ];
        }

        $inspectionDone = (bool) $this->inspection_completed_at || $this->status === 'completed';
        $workOrderDone = (bool) $this->work_order_completed_at || $this->status === 'completed';
        $serviceReportDone = (bool) $this->service_report_completed_at || $this->status === 'completed';
        $isCompleted = $this->status === 'completed';
        $inspectionStarted = $this->status !== 'pending';

        return [
            $this->progressStep('Inspection', $inspectionDone, $inspectionStarted),
            $this->progressStep('Work Order', $workOrderDone, $inspectionDone),
            $this->progressStep('Service Report', $serviceReportDone, $workOrderDone),
            $this->progressStep('Completed', $isCompleted, $serviceReportDone),
        ];
    }

    /** @return array{name: string, status: string, label: string} */
    private function progressStep(string $name, bool $completed, bool $available): array
    {
        $status = $completed ? 'completed' : ($available ? 'on_going' : 'pending');

        return [
            'name' => $name,
            'status' => $status,
            'label' => match ($status) {
                'completed' => 'Completed',
                'on_going', 'in_progress' => 'On-going',
                default => 'Pending',
            },
        ];
    }

    public function scopeStageStatus(Builder $query, string $stage, string $status): void
    {
        if (! in_array($stage, ['inspection', 'work_order', 'service_report'], true)) {
            return;
        }

        $query->where(function (Builder $routes) use ($stage, $status) {
            foreach ([false, true] as $inHouse) {
                $routes->orWhere(function (Builder $route) use ($stage, $status, $inHouse) {
                    $route->where('assignment_type', $inHouse ? 'in_house' : 'dial_a');
                    $this->filterStageStatus($route, $stage, $status, $inHouse);
                });
            }
        });
    }

    private function filterStageStatus(Builder $query, string $stage, string $status, bool $inHouse): void
    {
        $inspection = $inHouse ? 'in_house_requested_at' : 'inspection_completed_at';
        $workOrder = $inHouse ? 'in_house_work_order_at' : 'work_order_completed_at';
        $report = $inHouse ? 'in_house_completed_at' : 'service_report_completed_at';
        match ($stage) {
            'inspection' => match ($status) {
                'pending' => $query->where('status', 'pending')->whereNull($inspection),
                'on_going', 'in_progress' => $query->where('status', '!=', 'pending')
                    ->where('status', '!=', 'completed')
                    ->whereNull($inspection),
                'completed' => $query->where(function (Builder $completed) use ($inspection) {
                    $completed->where('status', 'completed')->orWhereNotNull($inspection);
                }),
                default => null,
            },
            'work_order' => match ($status) {
                'pending' => $query->where('status', '!=', 'completed')
                    ->whereNull($workOrder)
                    ->whereNull($inspection),
                'on_going', 'in_progress' => $query->where('status', '!=', 'completed')
                    ->whereNotNull($inspection)
                    ->whereNull($workOrder),
                'completed' => $query->where(function (Builder $completed) use ($workOrder) {
                    $completed->where('status', 'completed')->orWhereNotNull($workOrder);
                }),
                default => null,
            },
            'service_report' => match ($status) {
                'pending' => $query->where('status', '!=', 'completed')
                    ->whereNull($report)
                    ->whereNull($workOrder),
                'on_going', 'in_progress' => $query->where('status', '!=', 'completed')
                    ->whereNotNull($workOrder)
                    ->whereNull($report),
                'completed' => $query->where(function (Builder $completed) use ($report) {
                    $completed->where('status', 'completed')->orWhereNotNull($report);
                }),
                default => null,
            },
            default => null,
        };
    }
}
