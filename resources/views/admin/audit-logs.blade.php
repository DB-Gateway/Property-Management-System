@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="page-heading heading-with-badge"><div><p class="eyebrow">ACTIVITY MONITORING</p><h1>Audit Logs</h1><p>A chronological record of sign-ins, submissions, assignments, profile changes, and request status updates.</p></div><span class="access-badge">System Record</span></div>
<section class="panel">
    <form class="filter-bar" method="GET"><select name="action"><option value="">All actions</option>@foreach($actions as $action)<option value="{{ $action }}" @selected(request('action') === $action)>{{ str($action)->replace('_', ' ')->title() }}</option>@endforeach</select><button class="button button-primary" type="submit">Filter</button><a class="button button-light" href="{{ route('admin.audit') }}">Reset</a></form>
    <div class="table-scroll"><table class="data-table"><thead><tr><th>Date &amp; Time</th><th>User</th><th>Action</th><th>Description</th><th>IP Address</th></tr></thead><tbody>
        @forelse($logs as $log)<tr><td class="nowrap">{{ $log->created_at->format('M d, Y') }}<small>{{ $log->created_at->format('h:i:s A') }}</small></td><td>{{ $log->user?->name ?? 'System' }}</td><td><span class="audit-action">{{ str($log->action)->replace('_', ' ')->title() }}</span></td><td>{{ $log->description }}</td><td>{{ $log->ip_address ?: '—' }}</td></tr>@empty<tr><td colspan="5"><div class="empty-state">No audit activity recorded.</div></td></tr>@endforelse
    </tbody></table></div><div class="pagination-wrap">{{ $logs->links() }}</div>
</section>
@endsection
