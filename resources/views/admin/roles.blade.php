@extends('layouts.app')

@section('title', 'Role Management')

@section('content')
<div class="page-heading"><p class="eyebrow">ACCESS CONTROL</p><h1>Role Management</h1><p>View the responsibilities enforced for each role in the system.</p></div>
<div class="role-grid">
    <article class="panel role-card"><div class="role-card-head"><span class="role-icon admin-role">A</span><div><h2>Administrator</h2><p>{{ $counts['admin'] ?? 0 }} account</p></div></div><ul><li>Monitor all requests and request history</li><li>Create, edit, and delete user accounts</li><li>View roles, branches, and audit logs</li><li>Cannot assign work or change request status</li></ul></article>
    <article class="panel role-card"><div class="role-card-head"><span class="role-icon manager-role">M</span><div><h2>PM Manager</h2><p>{{ $counts['pm_manager'] ?? 0 }} account</p></div></div><ul><li>Review request details and dealer requirements (view-only)</li><li>Exclusively view and publish print-only reports for completed requests</li></ul></article>
    <article class="panel role-card"><div class="role-card-head"><span class="role-icon support-role">D</span><div><h2>Dial-A</h2><p>{{ ($counts['dial_a'] ?? 0) + ($counts['pm_support'] ?? 0) + ($counts['dial_lead'] ?? 0) }} account</p></div></div><ul><li>Single designated Dial-A user responsible for property management operations</li><li>Conducts inspections and records accompanying branch representatives</li><li>Exclusively authorized to upload Work Order image attachments</li><li>Exclusively authorized to upload Service Report image attachments</li><li>Officially confirms and finishes requests after confirmation prompt</li></ul></article>
    <article class="panel role-card"><div class="role-card-head"><span class="role-icon dealer-role">D</span><div><h2>Dealer</h2><p>{{ $counts['dealer'] ?? 0 }} accounts</p></div></div><ul><li>Submit requests for their assigned branch</li><li>Attach request photos and documents</li><li>View only their dealership's requests</li></ul></article>
</div>
@endsection
