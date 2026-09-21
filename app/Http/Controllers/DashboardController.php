<?php

namespace App\Http\Controllers;

use App\Models\PropertyRequest;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $user = auth()->user();
        $base = PropertyRequest::query();
        if ($user->isDialA()) {
            $base->where('assignment_type', '!=', 'pending_review')->where('assignment_phase', 'proceeded');
        }

        if ($user->isDealer()) {
            $base->where('submitted_by', $user->id);
        }

        $agingCount = (clone $base)->overdue()->count();
        $counts = [
            'pm_review' => (clone $base)->where(fn ($review) => $review->where('assignment_type', 'pending_review')->orWhere('assignment_phase', 'unassigned'))->count(),
            'total' => (clone $base)->count(),
            'not_acknowledged' => (clone $base)->notAcknowledged()->count(),
            'inspection_pending' => (clone $base)->stageStatus('inspection', 'pending')->count(),
            'inspection_ongoing' => (clone $base)->stageStatus('inspection', 'on_going')->count(),
            'inspection_completed' => (clone $base)->stageStatus('inspection', 'completed')->count(),
            'work_order_pending' => (clone $base)->stageStatus('work_order', 'pending')->count(),
            'work_order_ongoing' => (clone $base)->stageStatus('work_order', 'on_going')->count(),
            'work_order_completed' => (clone $base)->stageStatus('work_order', 'completed')->count(),
            'service_report_pending' => (clone $base)->stageStatus('service_report', 'pending')->count(),
            'service_report_ongoing' => (clone $base)->stageStatus('service_report', 'on_going')->count(),
            'service_report_completed' => (clone $base)->stageStatus('service_report', 'completed')->count(),
            'pending' => (clone $base)->pendingRequest()->count(),
            'completed' => (clone $base)->where('status', 'completed')->count(),
            'aging' => $agingCount,
            // Kept as an internal alias for existing reports/tests while the UI uses "Aging Request".
            'overdue' => $agingCount,
        ];

        foreach (['inspection', 'work_order', 'service_report'] as $stage) {
            $counts[$stage] = $counts["{$stage}_pending"]
                + $counts["{$stage}_ongoing"]
                + $counts["{$stage}_completed"];
        }

        $agingRequestsEnabled = config('features.aging_requests', false);

        $recent = (clone $base)
            ->with(['dealer', 'assignedSupport'])
            ->latest()
            ->limit(6)
            ->get();

        $awaitingCompletion = $user->isDialA()
            ? PropertyRequest::query()
                ->with('dealer')
                ->where('assignment_type', 'dial_a')
                ->where('assignment_phase', 'proceeded')
                ->where(function ($query) {
                    $query->whereNotNull('service_report_completed_at')
                        ->orWhereHas('serviceReportFiles');
                })
                ->whereNull('completed_at')
                ->latest('completion_notified_at')
                ->latest('updated_at')
                ->get()
            : collect();

        return view('dashboard', compact('counts', 'recent', 'user', 'awaitingCompletion', 'agingRequestsEnabled'));
    }
}
