<?php

namespace Tests\Feature;

use App\Jobs\SendBrowserPush;
use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\User;
use App\Models\WebPushSubscription;
use App\Services\WebPushService;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Request as PushRequest;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Str;
use Minishlink\WebPush\MessageSentReport;
use Mockery;
use RuntimeException;
use Tests\TestCase;

class BrowserPushNotificationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        config(['webpush.public_key' => 'test-public-key', 'webpush.private_key' => 'test-private-key', 'webpush.subject' => 'mailto:push@example.com']);
        Queue::fake();
    }

    public function test_subscription_validates_login_endpoint_and_keys(): void
    {
        $this->postJson(route('notifications.push.store'), $this->payload())->assertUnauthorized();
        $this->actingAs(User::factory()->create());
        foreach (['http://fcm.googleapis.com/a', 'https://127.0.0.1/a', 'https://fcm.googleapis.com.evil.test/a',
            'https://fcm.googleapis.com:444/a', 'https://user@fcm.googleapis.com/a'] as $endpoint) {
            $this->postJson(route('notifications.push.store'), $this->payload($endpoint))
                ->assertUnprocessable()->assertJsonValidationErrors('endpoint');
        }
        $this->postJson(route('notifications.push.store'), ['endpoint' => $this->payload()['endpoint'], 'keys' => ['p256dh' => 'bad', 'auth' => 'bad']])
            ->assertUnprocessable()->assertJsonValidationErrors(['keys.p256dh', 'keys.auth']);
        $this->assertDatabaseCount('web_push_subscriptions', 0);
    }

    public function test_devices_are_idempotent_reassigned_safely_and_can_only_be_removed_by_their_owner(): void
    {
        [$first, $second] = User::factory()->count(2)->create();
        $payload = $this->payload();
        $this->actingAs($first)->postJson(route('notifications.push.store'), $payload)->assertOk()->assertCookie(WebPushSubscription::COOKIE);
        $original = WebPushSubscription::sole()->id;
        $this->postJson(route('notifications.push.store'), $payload)->assertOk();
        $this->assertDatabaseCount('web_push_subscriptions', 1);
        $this->actingAs($second)->postJson(route('notifications.push.store'), $payload)->assertOk();
        $this->assertDatabaseMissing('web_push_subscriptions', ['id' => $original]);
        $this->actingAs($first)->deleteJson(route('notifications.push.destroy'), ['endpoint' => $payload['endpoint']])->assertOk();
        $this->assertDatabaseCount('web_push_subscriptions', 1);
        $this->actingAs($second)->deleteJson(route('notifications.push.destroy'), ['endpoint' => $payload['endpoint']])->assertOk();
        $this->assertDatabaseCount('web_push_subscriptions', 0);
    }

    public function test_old_tabs_cannot_subscribe_or_read_notifications_after_account_changes(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user)->withHeader('X-PMS-Account', (string) ($user->id + 1))
            ->postJson(route('notifications.push.store'), $this->payload())->assertConflict();
        $this->getJson(route('notifications.index'))->assertConflict();
        $this->assertDatabaseCount('web_push_subscriptions', 0);
    }

    public function test_logout_removes_only_the_current_device_and_admin_login_clears_previous_account(): void
    {
        $user = User::factory()->create();
        $device = $this->device($user, 'current-device');
        $other = $this->device($user, 'other-device');
        $this->actingAs($user)->withCookie(WebPushSubscription::COOKIE, 'current-device')->post(route('logout'))->assertRedirect(route('login'));
        $this->assertDatabaseMissing('web_push_subscriptions', ['id' => $device->id]);
        $this->assertDatabaseHas('web_push_subscriptions', ['id' => $other->id]);
        $device = $this->device($user, 'current-device');
        $admin = User::factory()->create(['role' => 'admin']);
        $this->post(route('login.attempt'), ['email' => $admin->email, 'password' => 'password'])->assertRedirect(route('dashboard'));
        $this->assertDatabaseMissing('web_push_subscriptions', ['id' => $device->id]);
        $this->assertDatabaseHas('web_push_subscriptions', ['id' => $other->id]);
    }

    public function test_expired_devices_are_removed_and_temporary_provider_failures_retry(): void
    {
        $user = User::factory()->create();
        $device = $this->device($user);
        $notification = $this->notification($user);
        $push = Mockery::mock(WebPushService::class);
        $push->shouldReceive('send')->once()->andReturn($this->report(410));
        (new SendBrowserPush($device->id, $user->id, $notification->id))->handle($push);
        $this->assertDatabaseMissing('web_push_subscriptions', ['id' => $device->id]);
        $device = $this->device($user, 'retry');
        $device->update(['created_at' => $notification->created_at]);
        $push->shouldReceive('send')->once()->andReturn($this->report(503));
        $this->expectException(RuntimeException::class);
        (new SendBrowserPush($device->id, $user->id, $notification->id))->handle($push);
    }

    public function test_read_notifications_inactive_users_admins_and_reassigned_devices_are_not_sent(): void
    {
        $user = User::factory()->create();
        $device = $this->device($user);
        $notification = $this->notification($user);
        $job = new SendBrowserPush($device->id, $user->id, $notification->id);
        $push = Mockery::mock(WebPushService::class);
        $push->shouldNotReceive('send');
        $notification->markAsRead();
        $job->handle($push);
        $notification->update(['read_at' => null]);
        $user->update(['is_active' => false]);
        $job->handle($push);
        $user->update(['is_active' => true, 'role' => 'admin']);
        $job->handle($push);
        $user->update(['role' => 'dealer']);
        $device->update(['user_id' => User::factory()->create()->id]);
        $job->handle($push);
        $device->delete();
        $job->handle($push);
    }

    public function test_transport_encrypts_and_signs_a_push_using_a_mock_provider(): void
    {
        // Public P-256 test fixture: generator point and scalar 1, never production keys.
        $encode = fn ($bytes) => rtrim(strtr(base64_encode($bytes), '+/', '-_'), '=');
        $public = $encode(hex2bin('046b17d1f2e12c4247f8bce6e563a440f277037d812deb33a0f4a13945d898c2964fe342e2fe1a7f9b8ee7eb4a7c0f9e162bce33576b315ececbb6406837bf51f5'));
        config(['webpush.public_key' => $public, 'webpush.private_key' => $encode(str_repeat("\0", 31)."\x01")]);
        $requests = [];
        $stack = HandlerStack::create(new MockHandler([new Response(201)]));
        $stack->push(Middleware::history($requests));
        $user = User::factory()->create();
        $device = $this->device($user);
        $device->update(['public_key' => $public]);
        $report = (new WebPushService(new Client(['handler' => $stack])))->send($device, $this->notification($user));
        $this->assertTrue($report->isSuccess());
        $request = $requests[0]['request'];
        $this->assertSame('aes128gcm', $request->getHeaderLine('Content-Encoding'));
        $this->assertStringStartsWith('vapid ', $request->getHeaderLine('Authorization'));
        $this->assertStringNotContainsString('Inspection completed', (string) $request->getBody());
    }

    private function payload(string $endpoint = 'https://fcm.googleapis.com/pms-test'): array
    {
        return ['endpoint' => $endpoint, 'keys' => [
            'p256dh' => rtrim(strtr(base64_encode("\x04".str_repeat('a', 64)), '+/', '-_'), '='),
            'auth' => rtrim(strtr(base64_encode(str_repeat('b', 16)), '+/', '-_'), '='),
        ]];
    }

    private function device(User $user, string $token = 'test-device'): WebPushSubscription
    {
        $payload = $this->payload('https://fcm.googleapis.com/'.Str::uuid());

        return WebPushSubscription::create([
            'user_id' => $user->id, 'endpoint' => $payload['endpoint'], 'endpoint_hash' => hash('sha256', $payload['endpoint']),
            'public_key' => $payload['keys']['p256dh'], 'auth_token' => $payload['keys']['auth'], 'device_token_hash' => hash('sha256', $token),
        ]);
    }

    private function notification(User $user)
    {
        $dealer = Dealer::firstOrCreate(['source_no' => 1], ['name' => 'Test Dealer', 'area' => 'Manila', 'brand' => 'Test']);
        $request = PropertyRequest::withoutEvents(fn () => PropertyRequest::create([
            'reference_no' => 'PUSH-'.Str::random(8), 'dealer_id' => $dealer->id, 'submitted_by' => $user->id,
            'submitter_name' => $user->name, 'designation' => 'Dealer', 'branch' => 'Pasig', 'area' => 'Metro Manila',
            'request_type' => 'Electrical Works', 'priority' => 'regular', 'description' => 'Repair lighting.',
            'request_date' => today(), 'due_date' => today()->addDays(4), 'status' => 'pending',
        ]));

        return $user->notifications()->create([
            'id' => (string) Str::uuid(), 'type' => 'request_workflow', 'property_request_id' => $request->id,
            'data' => ['title' => 'Inspection completed', 'message' => 'Inspection is ready to review.', 'kind' => 'stage_completed'],
        ]);
    }

    private function report(int $status): MessageSentReport
    {
        return new MessageSentReport(new PushRequest('POST', $this->payload()['endpoint']), new Response($status), $status === 201);
    }
}
