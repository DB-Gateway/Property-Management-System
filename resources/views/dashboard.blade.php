@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@if($user->isManager() && ($counts['pm_review'] ?? 0) > 0)
    <section class="panel monitoring-note wide-note">
        <strong>{{ $counts['pm_review'] }} request(s) awaiting PM review</strong>
        <p>Decide the priority, add remarks for the dealer, and assign each request.</p>
        <a class="button button-primary" href="{{ route('requests.index', ['stage' => 'pm_review']) }}">Review Requests</a>
    </section>
@endif

@if($user->isDialA() && !empty($awaitingCompletion) && $awaitingCompletion->isNotEmpty())
    <section class="panel" style="border: 2px solid #2563eb; background: #eff6ff; padding: 18px 22px; border-radius: 8px; margin-bottom: 24px;">
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 28px;">🔔</span>
                <div>
                    <strong style="color: #1e40af; font-size: 1.05rem;">{{ $awaitingCompletion->count() }} Request(s) Awaiting Final Confirmation</strong>
                </div>
            </div>
            <div style="display: flex; gap: 8px; flex-wrap: wrap;">
                @foreach($awaitingCompletion->take(3) as $pendingReq)
                    <a class="button button-primary" style="background: #2563eb; font-size: 0.85rem; padding: 7px 15px;" href="{{ route('requests.show', $pendingReq) }}">
                        Review {{ $pendingReq->reference_no }} →
                    </a>
                @endforeach
            </div>
        </div>
    </section>
@endif

@include('partials.dashboard-stats')

<div class="dashboard-grid">
    <section class="panel recent-panel">
        <div class="panel-heading">
            <h2>Recent Request Activity</h2>
            <a href="{{ route('requests.index') }}">View All Requests</a>
        </div>
        <div class="activity-list">
            @forelse($recent as $item)
                <a class="activity-row" href="{{ route('requests.show', $item) }}">
                    <div class="activity-main">
                        <span class="reference">{{ $item->reference_no }}</span>
                        <strong>{{ $item->request_type }}</strong>
                        <small>{{ $item->dealer->name }} · {{ $item->area }} · Updated {{ $item->updated_at->format('M d, Y h:i A') }}</small>
                    </div>
                    <div class="activity-progress-cell">
                        <small class="activity-progress-title">Activity Progress</small>
                        @include('requests.partials.activity-progress', ['requestItem' => $item, 'compact' => true])
                    </div>
                </a>
            @empty
                <div class="empty-state">No requests have been submitted yet.</div>
            @endforelse
        </div>
    </section>
</div>
@endsection
