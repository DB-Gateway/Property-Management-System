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
        'priority', 'description', 'request_date', 'due_date', 'approved_date', 'inspection_date', 'inspection_end_date',
        'inspection_start_time', 'inspection_end_time',
        'representative_1', 'representative_2', 'representative_3', 'inspection_completed_at',
        'work_order_start_date', 'work_order_end_date', 'work_order_start_time', 'work_order_end_time', 'work_order_representatives', 'work_order_completed_at',
        'service_report_date', 'service_report_completed_at', 'completion_notified_at', 'status', 'completed_at',
    ];

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

    public function notifications()
    {
        return $this->hasMany(DatabaseNotification::class, 'property_request_id');
    }

    public function isAssignedTo(User $user): bool
    {
        return $user->isDialA() && ($this->assigned_support_id === null || $this->assigned_support_id === $user->id);
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
            ->whereDoesntHave('notifications', fn (Builder $notification) => $notification
                ->where('data->kind', 'new_request')
                ->whereNotNull('read_at'));
    }

    public function scopePendingRequest(Builder $query): void
    {
        $query->incomplete()->where(function (Builder $pending) {
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

        match ($stage) {
            'inspection' => $query->whereNull('inspection_completed_at')
                ->whereNull('work_order_completed_at')->whereNull('service_report_completed_at'),
            'work_order' => $query->whereNotNull('inspection_completed_at')
                ->whereNull('work_order_completed_at')->whereNull('service_report_completed_at'),
            'service_report' => $query->whereNotNull('work_order_completed_at')
                ->whereNull('service_report_completed_at'),
            default => $query->whereRaw('1 = 0'),
        };
    }

    public function isAwaitingDialLeadCompletion(): bool
    {
        return $this->isAwaitingDialACompletion();
    }

    public function isAwaitingDialACompletion(): bool
    {
        $hasServiceReport = ! empty($this->service_report_completed_at)
            || ($this->relationLoaded('serviceReportFiles') ? $this->serviceReportFiles->isNotEmpty() : $this->serviceReportFiles()->exists());

        return $hasServiceReport && empty($this->completed_at);
    }

    public function getStatusLabelAttribute(): string
    {
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
        match ($stage) {
            'inspection' => match ($status) {
                'pending' => $query->where('status', 'pending')->whereNull('inspection_completed_at'),
                'on_going', 'in_progress' => $query->where('status', '!=', 'pending')
                    ->where('status', '!=', 'completed')
                    ->whereNull('inspection_completed_at'),
                'completed' => $query->where(function (Builder $completed) {
                    $completed->where('status', 'completed')->orWhereNotNull('inspection_completed_at');
                }),
                default => null,
            },
            'work_order' => match ($status) {
                'pending' => $query->where('status', '!=', 'completed')
                    ->whereNull('work_order_completed_at')
                    ->whereNull('inspection_completed_at'),
                'on_going', 'in_progress' => $query->where('status', '!=', 'completed')
                    ->whereNotNull('inspection_completed_at')
                    ->whereNull('work_order_completed_at'),
                'completed' => $query->where(function (Builder $completed) {
                    $completed->where('status', 'completed')->orWhereNotNull('work_order_completed_at');
                }),
                default => null,
            },
            'service_report' => match ($status) {
                'pending' => $query->where('status', '!=', 'completed')
                    ->whereNull('service_report_completed_at')
                    ->whereNull('work_order_completed_at'),
                'on_going', 'in_progress' => $query->where('status', '!=', 'completed')
                    ->whereNotNull('work_order_completed_at')
                    ->whereNull('service_report_completed_at'),
                'completed' => $query->where(function (Builder $completed) {
                    $completed->where('status', 'completed')->orWhereNotNull('service_report_completed_at');
                }),
                default => null,
            },
            default => null,
        };
    }
}
