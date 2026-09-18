<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\RequestAttachment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    private function adminOnly(Request $request): void
    {
        abort_unless($request->user()->isAdmin(), 403);
    }

    public function areas(Request $request)
    {
        $this->adminOnly($request);
        $areas = Dealer::query()
            ->select('area', DB::raw('COUNT(*) as dealer_count'), DB::raw('COUNT(DISTINCT brand) as brand_count'))
            ->groupBy('area')
            ->orderBy('area')
            ->get();
        $dealers = Dealer::orderBy('area')->orderBy('name')->get()->groupBy('area');

        return view('admin.areas', compact('areas', 'dealers'));
    }

    public function users(Request $request)
    {
        $this->adminOnly($request);
        $users = User::with('dealer')
            ->when($request->filled('role'), fn ($query) => $query->whereIn('role',
                in_array($request->role, ['dial_a', 'pm_support', 'dial_lead'])
                    ? ['dial_a', 'pm_support', 'dial_lead'] : [$request->role]
            ))
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->trim();
                $query->where(fn ($nested) => $nested->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"));
            })
            ->orderByRaw("CASE role WHEN 'admin' THEN 1 WHEN 'pm_manager' THEN 2 WHEN 'dial_a' THEN 3 WHEN 'pm_support' THEN 3 WHEN 'dial_lead' THEN 3 ELSE 4 END")
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users', ['users' => $users, 'roles' => User::ROLES]);
    }

    public function roles(Request $request)
    {
        $this->adminOnly($request);
        $counts = User::select('role', DB::raw('COUNT(*) as total'))->groupBy('role')->pluck('total', 'role');

        return view('admin.roles', compact('counts'));
    }

    public function auditLogs(Request $request)
    {
        $this->adminOnly($request);
        $logs = AuditLog::with('user')
            ->when($request->filled('action'), fn ($query) => $query->where('action', $request->action))
            ->latest()
            ->paginate(20)
            ->withQueryString();
        $actions = AuditLog::distinct()->orderBy('action')->pluck('action');

        return view('admin.audit-logs', compact('logs', 'actions'));
    }

    public function settings(Request $request)
    {
        $this->adminOnly($request);

        return view('admin.settings');
    }

    public function showResetRequests(Request $request)
    {
        $this->adminOnly($request);

        $counts = [
            'requests' => PropertyRequest::count(),
            'attachments' => RequestAttachment::count(),
            'request_logs' => $this->requestAuditLogs()->count(),
            'dealers' => Dealer::count(),
            'users' => User::count(),
        ];

        return view('admin.reset-requests', compact('counts'));
    }

    public function resetRequests(Request $request)
    {
        $this->adminOnly($request);

        $request->validate([
            'confirmation' => ['required', 'in:RESET REQUESTS'],
            'current_password' => ['required', 'current_password'],
        ], [
            'confirmation.in' => 'Type RESET REQUESTS exactly to confirm the reset.',
        ]);

        $attachmentPaths = RequestAttachment::query()->pluck('path')->all();
        $deletedCounts = [
            'requests' => PropertyRequest::count(),
            'attachments' => count($attachmentPaths),
            'request_logs' => $this->requestAuditLogs()->count(),
        ];

        DB::transaction(function (): void {
            $this->requestAuditLogs()->delete();
            PropertyRequest::query()->delete();
        });

        if ($attachmentPaths !== []) {
            Storage::delete($attachmentPaths);
        }
        Storage::deleteDirectory('request-attachments');

        AuditLog::record(
            'requests_reset',
            $request->user()->name.' reset all request records. Dealer and user data were preserved.',
            null,
            $deletedCounts
        );

        return redirect()->route('dashboard')->with(
            'status',
            "Request reset complete: {$deletedCounts['requests']} requests and {$deletedCounts['attachments']} attachments removed. Dealer details were preserved."
        );
    }

    private function requestAuditLogs()
    {
        return AuditLog::query()->where(function ($query): void {
            $query->where('subject_type', 'PropertyRequest')
                ->orWhere('action', 'like', 'request%');
        });
    }
}
