<?php

namespace Tests\Feature;

use App\Jobs\SendBrowserPush;
use App\Http\Controllers\PropertyRequestController;
use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use App\Models\WebPushSubscription;
use App\Services\RequestNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class RequestNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $dialA;

    private User $dealer;

    private User $manager;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(now()->setDate(2026, 9, 15)->setTime(10, 0));
        config(['webpush.public_key' => null, 'webpush.private_key' => null]);
        $branch = Dealer::create(['source_no' => 1, 'name' => 'Test Dealer', 'city' => 'Pasig', 'area' => 'Metro Manila', 'brand' => 'Test']);
        $this->dealer = User::factory()->create(['role' => 'dealer', 'dealer_id' => $branch->id]);
        $this->dialA = User::factory()->create(['role' => 'dial_a']);
        $this->manager = User::factory()->create(['role' => 'pm_manager']);
        $this->admin = User::factory()->create(['role' => 'admin']);
    }

    public function test_submission_notifies_only_active_dial_a_and_uses_four_day_deadline(): void
    {
        $inactive = User::factory()->create(['role' => 'dial_a', 'is_active' => false]);
        $legacy = User::factory()->create(['role' => 'pm_support']);
        $this->actingAs($this->dealer)->post(route('requests.store'), [
            'name' => $this->dealer->name, 'designation' => 'Dealer', 'request_type' => 'Electrical Works',
            'priority' => 'urgent', 'description' => 'The showroom lights need repair.',
        ])->assertSessionHasNoErrors()->assertRedirect();
        $request = PropertyRequest::sole();
        $this->assertSame('2026-09-19', $request->due_date->toDateString());
        $this->assertSame('new_request', $this->dialA->notifications()->sole()->data['kind']);
        $this->assertSame(1, $legacy->notifications()->count());
        foreach ([$this->admin, $this->dealer, $this->manager, $inactive] as $user) {
            $this->assertSame(0, $user->notifications()->count());
        }
    }

    public function test_each_stage_completion_notifies_dealer_and_managers_once_and_can_be_recompleted(): void
    {
        $otherDealer = User::factory()->create(['role' => 'dealer', 'dealer_id' => $this->dealer->dealer_id]);
        $request = $this->makeRequest();
        foreach (['inspection_completed_at', 'work_order_completed_at', 'service_report_completed_at'] as $field) {
            $request->update([$field => now()]);
            $request->update(['description' => Str::random(20)]);
        }
        $this->assertSame(3, $this->dealer->notifications()->count());
        $this->assertSame(3, $this->manager->notifications()->count());
        $this->assertSame(0, $otherDealer->notifications()->count());
        $this->assertSame(0, $this->admin->notifications()->count());
        $request->update(['service_report_completed_at' => null]);
        $request->update(['service_report_completed_at' => now()]);
        $this->assertSame(4, $this->dealer->notifications()->count());
        $request->update(['completed_at' => now(), 'status' => 'completed']);
        $this->assertSame(5, $this->manager->notifications()->count());
    }

    public function test_schedule_changes_include_old_and_new_dates_and_unchanged_saves_do_not_notify(): void
    {
        $request = $this->makeRequest();
        $url = route('requests.schedule', $request);
        $dates = ['inspection_date' => '2026-09-16', 'work_order_start_date' => '2026-09-17', 'service_report_date' => '2026-09-18'];
        $this->actingAs($this->dialA)->patch($url, $dates)->assertSessionHasNoErrors();
        $this->patch($url, $dates)->assertSessionHasNoErrors();
        $this->assertSame(1, $this->dealer->notifications()->count());
        $this->assertStringContainsString('Not scheduled', $this->dealer->notifications()->sole()->data['message']);
        $this->patch($url, ['inspection_date' => '2026-09-17'])->assertSessionHasNoErrors();
        $this->assertSame(2, $this->manager->notifications()->count());
        $messages = $this->manager->notifications()->pluck('data')->pluck('message')->implode(' ');
        $this->assertStringContainsString('Sep 16, 2026 → Sep 17, 2026', $messages);
        $this->patch($url, ['service_report_date' => null])->assertSessionHasNoErrors();
        $this->assertSame(3, $this->dealer->notifications()->count());
        $this->actingAs($this->manager)->patch($url, $dates)->assertForbidden();
        $this->actingAs($this->dealer)->patch($url, $dates)->assertForbidden();
        $this->actingAs($this->admin)->patch($url, $dates)->assertForbidden();
        $this->actingAs(User::factory()->create(['role' => 'dial_a']))->patch($url, $dates)->assertForbidden();
    }

    public function test_completion_forms_emit_one_alert_per_recipient_and_one_push_per_device_even_when_dates_change(): void
    {
        Storage::fake('local');
        Queue::fake();
        $propertyRequest = $this->makeRequest();
        config(['webpush.public_key' => 'public', 'webpush.private_key' => 'private', 'webpush.subject' => 'mailto:test@example.com']);
        foreach ([$this->dealer, $this->manager] as $user) {
            WebPushSubscription::create([
                'user_id' => $user->id, 'endpoint_hash' => hash('sha256', 'endpoint-'.$user->id),
                'endpoint' => 'https://fcm.googleapis.com/device-'.$user->id, 'public_key' => 'key', 'auth_token' => 'auth',
                'device_token_hash' => hash('sha256', 'device-'.$user->id),
            ]);
        }
        $steps = [
            ['inspection', 'patch', 'requests.inspection.complete', 'completeInspection', [
                'inspection_date' => today()->toDateString(),
                'inspection_start_time' => '08:00',
                'inspection_end_time' => '09:00',
                'inspection_representatives' => ['Inspector'],
                'inspection_file' => UploadedFile::fake()->create('inspection.xlsx', 10, 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'),
                'inspection_template_confirmed' => '1',
            ]],
            ['work_order', 'post', 'requests.work-order.complete', 'completeWorkOrder', [
                'work_order_start_date' => today()->toDateString(),
                'work_order_end_date' => today()->toDateString(),
                'work_order_representatives' => ['Technician'],
                'work_order_files' => [UploadedFile::fake()->create('work.pdf', 10, 'application/pdf')],
            ]],
            ['service_report', 'post', 'requests.service-report.complete', 'completeServiceReport', [
                'service_report_files' => [UploadedFile::fake()->create('report.pdf', 10, 'application/pdf')],
            ]],
        ];
        foreach ($steps as $index => [$stage, $method, $route, $controllerMethod, $payload]) {
            // Simulate a second request that was route-bound before the first one completed.
            $staleRequest = $propertyRequest->fresh();
            $this->actingAs($this->dialA)->$method(route($route, $propertyRequest), $payload)
                ->assertSessionHasNoErrors()->assertRedirect();
            foreach ([$this->dealer, $this->manager] as $recipient) {
                $this->assertSame($index + 1, $recipient->notifications()->count());
                $notification = $recipient->notifications()->where('data->stage', $stage)->sole();
                $this->assertSame('stage_completed', $notification->data['kind']);
                if ($stage === 'service_report') {
                    $this->assertTrue($notification->data['request_completed']);
                    $this->assertStringContainsString('request is now completed', $notification->data['message']);
                } else {
                    $this->assertStringContainsString('Dates recorded:', $notification->data['message']);
                }
            }
            $httpRequest = Request::create('/requests/retry', strtoupper($method));
            $httpRequest->setUserResolver(fn () => $this->dialA);
            try {
                app(PropertyRequestController::class)->$controllerMethod($httpRequest, $staleRequest);
                $this->fail('A stale, repeated completion must be rejected.');
            } catch (ValidationException $error) {
                $this->assertArrayHasKey($stage, $error->errors());
            }
            Queue::assertPushed(SendBrowserPush::class, ($index + 1) * 2);
            $this->assertSame($index + 1, $this->dealer->notifications()->count());
        }
        $this->assertSame('completed', $propertyRequest->fresh()->status);
        $this->assertSame(3, $propertyRequest->attachments()->count());
        $this->post(route('requests.finish', $propertyRequest))->assertRedirect();
        $this->assertSame(3, $this->dealer->notifications()->count());
        Queue::assertPushed(SendBrowserPush::class, 6);
        $this->actingAs($this->dealer)->getJson(route('notifications.index'))
            ->assertJsonPath('unread_count', 3)->assertJsonCount(3, 'notifications');
    }

    public function test_reopened_service_report_sends_one_new_combined_notification_on_recompletion(): void
    {
        $request = $this->makeRequest();
        $request->update(['work_order_completed_at' => now()]);
        $this->dealer->notifications()->delete();
        $complete = ['service_report_completed_at' => now(), 'completed_at' => now(), 'status' => 'completed'];
        $request->update($complete);
        $request->update(['service_report_completed_at' => null, 'completed_at' => null, 'status' => 'in_progress']);
        $request->update($complete);
        $this->assertSame(2, $this->dealer->notifications()->count());
        $this->assertSame(0, $this->dealer->notifications()->where('data->kind', 'request_completed')->count());
    }

    public function test_ageing_starts_after_four_calendar_days_and_is_consistent_in_counts(): void
    {
        $request = $this->makeRequest(['request_date' => today()->subDays(4), 'due_date' => today()->subDays(20)]);
        $this->assertFalse($request->is_overdue);
        $this->assertSame(0, PropertyRequest::overdue()->count());
        app(RequestNotificationService::class)->reminders();
        $this->assertSame(0, $this->reminders()->count());
        $this->travel(1)->days();
        $this->assertTrue($request->fresh()->is_overdue);
        app(RequestNotificationService::class)->reminders();
        app(RequestNotificationService::class)->reminders();
        $this->assertSame(1, $this->reminders()->count());
        $this->assertSame('Inspection past due', $this->reminders()->sole()->data['title']);
        $this->actingAs($this->dialA)->get(route('dashboard'))->assertViewHas('counts', fn ($counts) => $counts['overdue'] === 1);
        $this->get(route('requests.index', ['status' => 'overdue']))->assertSee($request->reference_no);
        $this->actingAs($this->manager)->get(route('reports.index'))->assertViewHas('counts', fn ($counts) => $counts['overdue'] === 1);
        $this->reminders()->update(['read_at' => now()]);
        app(RequestNotificationService::class)->reminders();
        $this->assertSame(0, $this->reminders()->whereNull('read_at')->count());
        $this->travel(1)->days();
        app(RequestNotificationService::class)->reminders();
        $this->assertSame(2, $this->reminders()->count());
    }

    public function test_not_acknowledged_and_pending_are_separate_and_opening_a_request_acknowledges_it(): void
    {
        $request = $this->makeRequest();

        $this->actingAs($this->dialA)->get(route('dashboard'))
            ->assertViewHas('counts', fn ($counts) => $counts['not_acknowledged'] === 1 && $counts['pending'] === 0)
            ->assertSeeInOrder(['For Acknowledgement', 'Inspection', 'Work Order', 'Service Report', 'Total Requests']);

        $requestsIndexResponse = $this->get(route('requests.index', ['status' => 'not_acknowledged']));
        $requestsIndexResponse
            ->assertOk()
            ->assertSee('For Acknowledgement')
            ->assertDontSee('Not Acknowledged')
            ->assertDontSee('Not acknowledged')
            ->assertViewHas('requests', fn ($requests) => $requests->pluck('id')->contains($request->id));

        $this->get(route('requests.index', ['status' => 'for_acknowledgement']))
            ->assertOk()
            ->assertSee('For Acknowledgement')
            ->assertDontSee('Not Acknowledged')
            ->assertDontSee('Not acknowledged')
            ->assertViewHas('requests', fn ($requests) => $requests->pluck('id')->contains($request->id));

        $this->get(route('requests.show', $request))->assertOk();
        $this->assertNotNull($this->dialA->notifications()->where('data->kind', 'new_request')->sole()->read_at);

        $this->get(route('dashboard'))
            ->assertViewHas('counts', fn ($counts) => $counts['not_acknowledged'] === 0 && $counts['pending'] === 1);
        $this->get(route('requests.index', ['status' => 'pending']))
            ->assertOk()
            ->assertViewHas('requests', fn ($requests) => $requests->pluck('id')->contains($request->id));

        // Starting the workflow always makes the request pending, even with an unread alert.
        $this->dialA->notifications()->where('data->kind', 'new_request')->update(['read_at' => null]);
        $request->update(['status' => 'in_progress']);
        $this->get(route('dashboard'))
            ->assertViewHas('counts', fn ($counts) => $counts['not_acknowledged'] === 0 && $counts['pending'] === 1);
    }

    public function test_dashboard_stage_counts_and_links_follow_workflow_and_dealer_visibility(): void
    {
        $unacknowledged = $this->makeRequest();
        $inspection = $this->makeRequest();
        $inspection->notifications()->update(['read_at' => now()]);
        $workOrder = $this->makeRequest(['status' => 'in_progress', 'inspection_completed_at' => now()]);
        $serviceReport = $this->makeRequest([
            'status' => 'in_progress', 'inspection_completed_at' => now(), 'work_order_completed_at' => now(),
        ]);
        $awaitingCompletion = $this->makeRequest([
            'status' => 'in_progress', 'inspection_completed_at' => now(), 'work_order_completed_at' => now(),
            'service_report_completed_at' => now(),
        ]);
        $completed = $this->makeRequest(['status' => 'completed', 'completed_at' => now()]);
        $otherDealer = User::factory()->create(['role' => 'dealer', 'dealer_id' => $this->dealer->dealer_id]);
        $otherInspection = $this->makeRequest(['submitted_by' => $otherDealer->id, 'status' => 'in_progress']);

        $ownedRecords = collect([$unacknowledged, $inspection, $workOrder, $serviceReport, $awaitingCompletion, $completed]);

        foreach ([$this->admin, $this->dialA, $this->manager, $this->dealer] as $user) {
            $visibleRecords = $user->isDealer() ? $ownedRecords : $ownedRecords->concat([$otherInspection]);
            $visibleCount = $visibleRecords->count();
            $response = $this->actingAs($user)->get(route('dashboard'))->assertOk();
            $response->assertViewHas('counts', fn ($counts) =>
                $counts['not_acknowledged'] === 1
                && $counts['total'] === $visibleCount
                && collect(['inspection', 'work_order', 'service_report'])->every(fn ($stage) =>
                    $counts[$stage] === $visibleCount
                    && $counts[$stage] === $counts["{$stage}_pending"] + $counts["{$stage}_ongoing"] + $counts["{$stage}_completed"]));
            preg_match('/<div class="stats-grid dashboard-stats">(.*?)<\/div>\s*<div class="dashboard-grid">/s', $response->getContent(), $grid);
            $this->assertNotEmpty($grid);
            $this->assertSame(6, substr_count($grid[1], 'class="stat-card '));
            $this->assertStringContainsString('title="View completed requests" hidden aria-hidden="true"', $grid[1]);

            foreach (['inspection' => 0, 'work_order' => 1, 'service_report' => 2] as $stage => $progressIndex) {
                $this->get(route('requests.index', ['stage' => $stage]))->assertOk()
                    ->assertViewHas('requests', fn ($requests) => $requests->pluck('id')->sort()->values()->all()
                        === $visibleRecords->pluck('id')->sort()->values()->all());

                foreach (['pending', 'on_going', 'completed'] as $status) {
                    $expectedIds = $visibleRecords
                        ->filter(fn (PropertyRequest $record) => $record->fresh()->activity_progress[$progressIndex]['status'] === $status)
                        ->pluck('id')->sort()->values()->all();
                    $this->get(route('requests.index', ['stage' => $stage, 'status' => $status]))->assertOk()
                        ->assertViewHas('requests', fn ($requests) => $requests->pluck('id')->sort()->values()->all() === $expectedIds);
                }
            }
        }

        $this->actingAs($this->manager)->get(route('reports.index'))->assertOk()
            ->assertSeeInOrder(['For Acknowledgement', 'Inspection', 'Work Order', 'Service Report', 'Total Requests'])
            ->assertDontSee('Not Acknowledged')
            ->assertDontSee('Not acknowledged')
            ->assertViewHas('counts', fn ($counts) => $counts['inspection'] === 7
                && $counts['work_order'] === 7 && $counts['service_report'] === 7 && $counts['total'] === 7);
    }

    public function test_aging_request_card_uses_four_day_count_and_can_be_enabled(): void
    {
        $this->makeRequest(['request_date' => today()->subDays(5)]);
        config(['features.aging_requests' => true]);

        $this->actingAs($this->dialA)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('AGING REQUEST')
            ->assertSee(route('requests.index', ['status' => 'aging']), false)
            ->assertViewHas('counts', fn ($counts) => $counts['aging'] === 1);
    }

    public function test_reminders_follow_unfinished_stages_and_stop_at_completion(): void
    {
        $request = $this->makeRequest(['request_date' => today()->subDays(5)]);
        $service = app(RequestNotificationService::class);
        $service->reminders();
        foreach (['inspection_completed_at' => 'Work past due', 'work_order_completed_at' => 'Service Report past due',
            'service_report_completed_at' => 'Request past due: confirmation needed'] as $field => $title) {
            $request->update([$field => now()]);
            $service->reminders();
            $this->assertSame($title, $this->reminders()->whereNull('read_at')->sole()->data['title']);
        }
        $request->update(['completed_at' => now(), 'status' => 'completed']);
        $service->reminders();
        $this->assertSame(0, $this->reminders()->whereNull('read_at')->count());
        $this->assertSame(0, PropertyRequest::overdue()->count());
    }

    public function test_upcoming_alerts_cover_today_tomorrow_and_unscheduled_service_report(): void
    {
        $request = $this->makeRequest(['inspection_date' => today()->addDays(2)]);
        $service = app(RequestNotificationService::class);
        $service->reminders();
        $this->assertSame(0, $this->reminders()->count());
        $request->update(['inspection_date' => today()->addDay(), 'work_order_start_date' => today()->addDay(), 'service_report_date' => today()]);
        $service->reminders();
        $service->reminders();
        $this->assertSame(3, $this->reminders()->whereNull('read_at')->count());
        $request->update(['inspection_completed_at' => now(), 'work_order_completed_at' => now(), 'service_report_date' => null]);
        $service->reminders();
        $this->assertSame('Upcoming Service Report', $this->reminders()->whereNull('read_at')->sole()->data['title']);
    }

    public function test_bell_and_api_are_per_account_and_exclude_admin_and_inactive_users(): void
    {
        $request = $this->makeRequest();
        $this->getJson(route('notifications.index'))->assertUnauthorized();
        $this->actingAs($this->dialA)->get(route('dashboard'))->assertSee('id="notificationBell"', false)->assertSee('id="browserPushPrompt"', false);
        $id = $this->dialA->notifications()->sole()->id;
        $this->getJson(route('notifications.index'))->assertOk()->assertJsonPath('unread_count', 1);
        $this->actingAs($this->dealer)->get(route('dashboard'))->assertSee('id="notificationBell"', false);
        $this->get(route('notifications.open', $id))->assertNotFound();
        $this->patchJson(route('notifications.read', $id))->assertNotFound();
        $this->getJson(route('notifications.index'))->assertJsonCount(0, 'notifications');
        $this->actingAs($this->admin)->get(route('dashboard'))->assertDontSee('id="notificationBell"', false)->assertDontSee('id="browserPushPrompt"', false);
        $this->getJson(route('notifications.index'))->assertForbidden();
        $this->postJson(route('notifications.push.store'), [])->assertForbidden();
        $this->actingAs($this->dialA)->get(route('notifications.open', $id))->assertRedirect(route('requests.show', $request));
        $this->getJson(route('notifications.index'))->assertJsonPath('unread_count', 0);
        $this->dialA->update(['is_active' => false]);
        $this->getJson(route('notifications.index'))->assertForbidden();
    }

    public function test_push_jobs_are_queued_once_and_after_commit_for_subscribed_recipients(): void
    {
        Queue::fake();
        config(['webpush.public_key' => 'public', 'webpush.private_key' => 'private', 'webpush.subject' => 'mailto:test@example.com']);
        WebPushSubscription::create([
            'user_id' => $this->dialA->id, 'endpoint_hash' => hash('sha256', 'endpoint'),
            'endpoint' => 'https://fcm.googleapis.com/device', 'public_key' => 'key', 'auth_token' => 'auth', 'device_token_hash' => hash('sha256', 'device'),
        ]);
        $this->makeRequest(['request_date' => today()->subDays(5)]);
        app(RequestNotificationService::class)->reminders();
        app(RequestNotificationService::class)->reminders();
        Queue::assertPushed(SendBrowserPush::class, 2);
        Queue::assertPushed(SendBrowserPush::class, fn ($job) => $job->userId === $this->dialA->id && $job->afterCommit === true);
    }

    public function test_notifications_are_removed_when_a_request_is_deleted(): void
    {
        $request = $this->makeRequest();
        $this->assertDatabaseCount('notifications', 1);
        PropertyRequest::whereKey($request->id)->delete();
        $this->assertDatabaseCount('notifications', 0);
    }

    private function reminders()
    {
        return $this->dialA->notifications()->whereIn('data->kind', ['upcoming', 'ageing']);
    }

    private function makeRequest(array $attributes = []): PropertyRequest
    {
        return PropertyRequest::create(array_merge([
            'reference_no' => 'TEST-'.Str::random(8), 'dealer_id' => $this->dealer->dealer_id,
            'submitted_by' => $this->dealer->id, 'assigned_support_id' => $this->dialA->id,
            'submitter_name' => $this->dealer->name, 'designation' => 'Dealer', 'branch' => 'Pasig',
            'area' => 'Metro Manila', 'request_type' => 'Electrical Works', 'priority' => 'regular',
            'description' => 'Repair showroom lighting.', 'request_date' => today(),
            'due_date' => today()->addDays(4), 'status' => 'pending',
        ], $attributes));
    }
}
