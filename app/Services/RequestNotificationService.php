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
        $this->send($request, $this->dialAUsers(), 'new_request', 'New request',
            "{$request->reference_no}: {$request->request_type} requested by {$request->submitter_name} ({$request->branch}).",
            'created');
    }

    public function updated(PropertyRequest $request): void
    {
        $recipients = $this->stakeholders($request);
        $event = (string) Str::uuid();
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
            DatabaseNotification::where('property_request_id', $request->id)->whereNull('read_at')
                ->whereIn('data->kind', ['upcoming', 'ageing'])->update(['read_at' => now()]);
        }
    }

    public function reminders(): void
    {
        $recipients = $this->dialAUsers();
        if ($recipients->isEmpty()) {
            return;
        }

        PropertyRequest::where('status', '!=', 'completed')->whereNull('completed_at')
            ->chunkById(100, function ($requests) use ($recipients) {
                foreach ($requests as $request) {
                    DB::transaction(function () use ($request, $recipients) {
                        // Serialize against schedule/completion updates so stale reminders cannot outlive them.
                        $request = PropertyRequest::whereKey($request->id)->lockForUpdate()->first();
                        if (! $request || $request->status === 'completed' || $request->completed_at) {
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
        ])));
        $this->send($request, $users, $kind, $title, $message, "{$kind}:{$stage}:".today()->toDateString().":{$state}");
    }

    private function dialAUsers(): Collection
    {
        return User::where('is_active', true)->whereIn('role', ['dial_a', 'pm_support', 'dial_lead'])->get();
    }

    private function stakeholders(PropertyRequest $request): Collection
    {
        // Dealer access is scoped to submitted_by throughout PMS, including notification destinations.
        return User::where('is_active', true)->where(function (Builder $query) use ($request) {
            $query->where('role', 'pm_manager')->orWhere(fn (Builder $dealer) => $dealer->where('role', 'dealer')->whereKey($request->submitted_by));
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
