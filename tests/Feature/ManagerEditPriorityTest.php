<?php

namespace Tests\Feature;

use App\Models\AuditLog;
use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ManagerEditPriorityTest extends TestCase
{
    use RefreshDatabase;

    private User $manager;
    private User $dealer;
    private User $dialA;
    private PropertyRequest $request;

    protected function setUp(): void
    {
        parent::setUp();
        config(['webpush.public_key' => null, 'webpush.private_key' => null]);

        $branch = Dealer::create([
            'source_no' => 101,
            'name' => 'Gateway Motors',
            'city' => 'Pasig',
            'area' => 'Metro Manila',
            'brand' => 'Gateway',
        ]);

        $this->dealer = User::factory()->create([
            'role' => 'dealer',
            'dealer_id' => $branch->id,
            'password' => Hash::make('Secret123!'),
        ]);

        $this->manager = User::factory()->create([
            'role' => 'pm_manager',
            'password' => Hash::make('ManagerPass2026!'),
        ]);

        $this->dialA = User::factory()->create([
            'role' => 'dial_a',
            'password' => Hash::make('DialPass2026!'),
        ]);

        $this->request = PropertyRequest::create([
            'reference_no' => 'REQ-2026-TEST',
            'dealer_id' => $branch->id,
            'dealer_name' => 'Gateway Motors',
            'submitted_by' => $this->dealer->id,
            'submitter_name' => $this->dealer->name,
            'designation' => 'Dealer Manager',
            'branch' => 'Pasig',
            'area' => 'Metro Manila',
            'request_type' => 'Electrical Works',
            'priority' => 'regular',
            'description' => 'Fix power socket in showroom.',
            'request_date' => now()->toDateString(),
            'due_date' => now()->addDays(4)->toDateString(),
            'status' => 'pending',
        ]);
    }

    public function test_manager_can_update_priority_with_valid_password(): void
    {
        $response = $this->actingAs($this->manager)->patch(route('requests.priority', $this->request), [
            'priority' => 'urgent',
            'remarks' => 'Urgent VIP event scheduled for tomorrow morning.',
            'current_password' => 'ManagerPass2026!',
        ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $this->request->refresh();
        $this->assertSame('urgent', $this->request->priority);
        $this->assertSame('Urgent VIP event scheduled for tomorrow morning.', $this->request->priority_remarks);

        // Dealer receives a notification about the priority change including remarks
        $dealerNotification = $this->dealer->notifications()->latest()->first();
        $this->assertNotNull($dealerNotification);
        $this->assertSame('priority_changed', $dealerNotification->data['kind']);
        $this->assertSame('Priority updated', $dealerNotification->data['title']);
        $this->assertStringContainsString('REQ-2026-TEST', $dealerNotification->data['message']);
        $this->assertStringContainsString('Regular to Urgent', $dealerNotification->data['message']);
        $this->assertStringContainsString('Urgent VIP event scheduled', $dealerNotification->data['message']);
        $this->assertSame('Urgent VIP event scheduled for tomorrow morning.', $dealerNotification->data['remarks']);

        // Audit log was recorded with remarks
        $log = AuditLog::where('subject_type', 'PropertyRequest')
            ->where('subject_id', $this->request->id)
            ->where('action', 'priority_status_updated')
            ->first();
        $this->assertNotNull($log);
        $this->assertSame('regular', $log->metadata['old_priority']);
        $this->assertSame('urgent', $log->metadata['new_priority']);
        $this->assertSame('Urgent VIP event scheduled for tomorrow morning.', $log->metadata['remarks']);
    }

    public function test_update_priority_fails_with_incorrect_password(): void
    {
        $response = $this->actingAs($this->manager)->patch(route('requests.priority', $this->request), [
            'priority' => 'urgent',
            'current_password' => 'WrongPassword!',
        ]);

        $response->assertSessionHasErrors('current_password');

        $this->request->refresh();
        $this->assertSame('regular', $this->request->priority);
        $this->assertSame(0, $this->dealer->notifications()->count());
    }

    public function test_update_priority_via_ajax_returns_json(): void
    {
        $response = $this->actingAs($this->manager)->patchJson(route('requests.priority', $this->request), [
            'priority' => 'urgent',
            'current_password' => 'ManagerPass2026!',
        ]);

        $response->assertOk();
        $response->assertJson([
            'success' => true,
            'priority' => 'urgent',
            'priority_label' => 'Urgent',
        ]);

        $this->request->refresh();
        $this->assertSame('urgent', $this->request->priority);
    }

    public function test_update_priority_via_ajax_with_wrong_password_returns_422(): void
    {
        $response = $this->actingAs($this->manager)->patchJson(route('requests.priority', $this->request), [
            'priority' => 'urgent',
            'current_password' => 'WrongPassword!',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['current_password']);
    }

    public function test_dealer_cannot_update_priority(): void
    {
        $response = $this->actingAs($this->dealer)->patch(route('requests.priority', $this->request), [
            'priority' => 'urgent',
            'current_password' => 'Secret123!',
        ]);

        $response->assertForbidden();
    }

    public function test_dial_a_cannot_update_priority(): void
    {
        $response = $this->actingAs($this->dialA)->patch(route('requests.priority', $this->request), [
            'priority' => 'urgent',
            'current_password' => 'DialPass2026!',
        ]);

        $response->assertForbidden();
    }

    public function test_manager_sees_edit_button_on_show_and_index(): void
    {
        $showResponse = $this->actingAs($this->manager)->get(route('requests.show', $this->request));
        $showResponse->assertOk();
        $showResponse->assertSee('Edit Priority Status');
        $showResponse->assertSee('data-open-priority-modal', false);

        $indexResponse = $this->actingAs($this->manager)->get(route('requests.index'));
        $indexResponse->assertOk();
        $indexResponse->assertSee('ACTIONS');
        $indexResponse->assertSee('data-open-priority-modal', false);
    }

    public function test_dealer_does_not_see_edit_button(): void
    {
        $showResponse = $this->actingAs($this->dealer)->get(route('requests.show', $this->request));
        $showResponse->assertOk();
        $showResponse->assertDontSee('data-open-priority-modal', false);

        $indexResponse = $this->actingAs($this->dealer)->get(route('requests.index'));
        $indexResponse->assertOk();
        $indexResponse->assertDontSee('data-open-priority-modal', false);
    }

    public function test_notifications_index_includes_remarks_and_priority_metadata(): void
    {
        $this->actingAs($this->manager)->patch(route('requests.priority', $this->request), [
            'priority' => 'urgent',
            'remarks' => 'Urgent VIP inspection required.',
            'current_password' => 'ManagerPass2026!',
        ]);

        $response = $this->actingAs($this->dealer)->getJson(route('notifications.index'));
        $response->assertOk();
        $notifications = $response->json('notifications');
        $this->assertNotEmpty($notifications);

        $target = collect($notifications)->firstWhere('kind', 'priority_changed');
        $this->assertNotNull($target);
        $this->assertSame('Urgent VIP inspection required.', $target['remarks']);
        $this->assertSame('regular', $target['old_priority']);
        $this->assertSame('urgent', $target['new_priority']);
        $this->assertSame('REQ-2026-TEST', $target['reference_no']);
    }

    public function test_opening_priority_notification_redirects_with_priority_notification_param(): void
    {
        $this->actingAs($this->manager)->patch(route('requests.priority', $this->request), [
            'priority' => 'urgent',
            'remarks' => 'Expedited service requested.',
            'current_password' => 'ManagerPass2026!',
        ]);

        $dealerNotification = $this->dealer->notifications()->latest()->first();
        $this->assertNotNull($dealerNotification);

        $openResponse = $this->actingAs($this->dealer)->get(route('notifications.open', $dealerNotification->id));
        $openResponse->assertRedirect(route('requests.show', [
            'propertyRequest' => $this->request,
            'priority_notification' => $dealerNotification->id,
        ]));

        // Visiting request show with notification param displays the priority notice modal script and remarks
        $showResponse = $this->actingAs($this->dealer)->get(route('requests.show', [
            'propertyRequest' => $this->request,
            'priority_notification' => $dealerNotification->id,
        ]));
        $showResponse->assertOk();
        $showResponse->assertSee('dealerPriorityNoticeOverlay');
        $showResponse->assertSee('Priority Remarks');
        $showResponse->assertSee('Expedited service requested.');
    }
}
