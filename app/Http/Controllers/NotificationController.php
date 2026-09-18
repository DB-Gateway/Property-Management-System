<?php

namespace App\Http\Controllers;

use App\Models\PropertyRequest;
use App\Services\RequestNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        // Keep reminders current on XAMPP even when the console scheduler is not running.
        if (Cache::add('pms:notification-reminders', true, now()->addMinute())) {
            try {
                app(RequestNotificationService::class)->reminders();
            } catch (\Throwable $error) {
                Cache::forget('pms:notification-reminders');
                throw $error;
            }
        }
        $user = $request->user();
        $items = $user->notifications()->orderByRaw('CASE WHEN read_at IS NULL THEN 0 ELSE 1 END')
            ->latest()->paginate(20);

        return response()->json([
            'user_id' => $user->id,
            'unread_count' => $user->unreadNotifications()->count(),
            'notifications' => $items->getCollection()->map(fn ($item) => [
                'id' => $item->id,
                'title' => $item->data['title'],
                'message' => $item->data['message'],
                'kind' => $item->data['kind'],
                'stage' => $item->data['stage'] ?? null,
                'read' => $item->read_at !== null,
                'time' => $item->created_at->diffForHumans(),
                'url' => route('notifications.open', $item->id),
            ]),
            'next_page' => $items->hasMorePages() ? $items->currentPage() + 1 : null,
        ]);
    }

    public function read(Request $request, string $notification)
    {
        $request->user()->notifications()->findOrFail($notification)->markAsRead();

        return response()->json(['ok' => true]);
    }

    public function readAll(Request $request)
    {
        $request->user()->unreadNotifications()->update(['read_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function open(Request $request, string $notification)
    {
        $record = $request->user()->notifications()->findOrFail($notification);
        $propertyRequest = PropertyRequest::findOrFail($record->property_request_id);
        abort_if($request->user()->isDealer() && $propertyRequest->submitted_by !== $request->user()->id, 403);
        $record->markAsRead();

        return redirect()->route('requests.show', $propertyRequest);
    }
}
