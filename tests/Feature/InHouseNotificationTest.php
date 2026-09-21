<?php

namespace Tests\Feature;

use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use App\Services\RequestNotificationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Notifications\DatabaseNotification;
use Tests\TestCase;

class InHouseNotificationTest extends TestCase
{
    use RefreshDatabase;

    private User $dealer;
    private User $manager;
    private User $pmAdmin;
    private User $dialA;
    private PropertyRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        $this->travelTo(now()->setDate(2026, 9, 21)->setTime(10, 0));
        config(['webpush.public_key' => null, 'webpush.private_key' => null]);
        $branch = Dealer::create(['source_no' => 101, 'name' => 'Test Dealer', 'city' => 'Pasig', 'area' => 'Metro Manila', 'brand' => 'Test']);
        $this->dealer = User::factory()->create(['role' => 'dealer', 'dealer_id' => $branch->id]);
        $this->manager = User::factory()->create(['role' => 'pm_manager']);
        $this->pmAdmin = User::factory()->create(['role' => 'pm_admin']);
        $this->dialA = User::factory()->create(['role' => 'dial_a']);
        $this->request = PropertyRequest::create([
            'reference_no' => 'INHOUSE-ALERT-001', 'dealer_id' => $branch->id, 'submitted_by' => $this->dealer->id,
            'submitter_name' => $this->dealer->name, 'designation' => 'Dealer', 'branch' => 'Pasig',
            'area' => 'Metro Manila', 'request_type' => 'Electrical Works', 'priority' => 'regular',
            'description' => 'Repair showroom lighting.', 'request_date' => today()->subDays(5),
            'due_date' => today()->subDay(), 'status' => 'pending',
        ]);
        DatabaseNotification::query()->delete();
    }

    public function test_reassignment_notifies_stakeholders_and_retires_old_dial_a_reminders(): void
    {
        $otherDealer = User::factory()->create(['role' => 'dealer', 'dealer_id' => $this->dealer->dealer_id]);
        $inactive = User::factory()->create(['role' => 'pm_admin', 'is_active' => false]);
        app(RequestNotificationService::class)->reminders();
        $this->assertSame(1, $this->dialA->unreadNotifications()->count());
        $this->request->update([
            'assignment_type' => 'in_house', 'assigned_at' => now(),
            'assignment_by_role' => 'pm_admin', 'assignment_by_name' => 'Alex',
        ]);

        foreach ([$this->dealer, $this->manager, $this->pmAdmin] as $recipient) {
            $notification = $recipient->notifications()->sole();
            $this->assertSame('assignment_changed', $notification->data['kind']);
            $this->assertStringContainsString('Assigned to In house c/o PM Admin (Alex)', $notification->data['message']);
        }
        $this->assertSame(0, $this->dialA->unreadNotifications()->count());
        $this->assertSame(0, $otherDealer->notifications()->count());
        $this->assertSame(0, $inactive->notifications()->count());
    }

    public function test_in_house_completion_identifies_pm_actor_without_claiming_dial_a_completed_it(): void
    {
        $this->request->update(['assignment_type' => 'in_house']);
        DatabaseNotification::query()->delete();
        $this->request->update([
            'in_house_requested_at' => now(), 'in_house_inspection_by_role' => 'pm_manager', 'in_house_inspection_by_name' => 'Jamie',
        ]);
        $this->request->update([
            'in_house_work_order_at' => now(), 'in_house_work_order_by_role' => 'pm_admin', 'in_house_work_order_by_name' => 'Alex',
        ]);
        $this->request->update([
            'in_house_completed_at' => now(), 'completed_at' => now(), 'status' => 'completed',
            'in_house_completion_by_role' => 'pm_admin', 'in_house_completion_by_name' => 'Alex',
        ]);

        foreach ([$this->dealer, $this->manager, $this->pmAdmin] as $recipient) {
            $this->assertSame(3, $recipient->notifications()->count());
            $completion = $recipient->notifications()->where('data->stage', 'completion_report')->sole();
            $this->assertSame('in_house_updated', $completion->data['kind']);
            $this->assertTrue($completion->data['request_completed']);
            $this->assertStringContainsString('c/o PM Admin (Alex)', $completion->data['message']);
            $this->assertStringNotContainsString('Dial-A', $completion->data['message']);
        }
        $this->assertSame(0, $this->dialA->notifications()->count());
    }

    public function test_switching_back_notifies_dial_a_without_reporting_old_completion_as_new(): void
    {
        $this->request->update(['assignment_type' => 'in_house']);
        DatabaseNotification::query()->delete();
        $this->request->update([
            'assignment_type' => 'dial_a', 'status' => 'completed', 'completed_at' => now()->subDay(),
            'assignment_by_role' => 'pm_manager', 'assignment_by_name' => 'Jamie',
        ]);
        foreach ([$this->dealer, $this->manager, $this->pmAdmin, $this->dialA] as $recipient) {
            $notification = $recipient->notifications()->sole();
            $this->assertSame('assignment_changed', $notification->data['kind']);
            $this->assertSame('dial_a', $notification->data['assignment_type']);
        }
    }

    public function test_in_house_ageing_reminders_follow_in_house_stages_and_only_reach_pm_roles(): void
    {
        $this->request->update(['assignment_type' => 'in_house']);
        DatabaseNotification::query()->delete();
        $service = app(RequestNotificationService::class);
        foreach ([
            null => 'Inspection Request',
            'in_house_requested_at' => 'Work Order',
            'in_house_work_order_at' => 'Completion Report',
        ] as $completedField => $nextStage) {
            if ($completedField) {
                $this->request->update([$completedField => now()]);
            }
            $service->reminders();
            $service->reminders();
            foreach ([$this->manager, $this->pmAdmin] as $recipient) {
                $reminder = $recipient->unreadNotifications()->where('data->kind', 'ageing')->sole();
                $this->assertSame('In house '.$nextStage.' past due', $reminder->data['title']);
            }
            $this->assertSame(0, $this->dialA->notifications()->count());
            $this->assertSame(0, $this->dealer->notifications()->where('data->kind', 'ageing')->count());
        }
        $this->request->update(['in_house_completed_at' => now(), 'completed_at' => now(), 'status' => 'completed']);
        $service->reminders();
        $this->assertSame(0, $this->pmAdmin->unreadNotifications()->where('data->kind', 'ageing')->count());
    }
}
