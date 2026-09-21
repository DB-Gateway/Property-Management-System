@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="page-heading heading-with-badge"><div><p class="eyebrow">SYSTEM CONFIGURATION</p><h1>System Settings</h1><p>Operational settings currently applied to the Gateway property management workflow.</p></div><span class="access-badge">Read-only Oversight</span></div>
<div class="settings-grid">
    <section class="panel setting-card"><div class="setting-icon">◷</div><div><span>Timezone</span><strong>{{ config('app.timezone') }}</strong><p>All dashboard and audit timestamps use Philippine time.</p></div></section>
    <section class="panel setting-card"><div class="setting-icon">▦</div><div><span>Dealer source</span><strong>Directory_Luzon Dealers.xlsx</strong><p>70 dealer and operations records imported into MariaDB.</p></div></section>
    <section class="panel setting-card"><div class="setting-icon">▣</div><div><span>Request due dates</span><strong>2–14 days by priority</strong><p>Urgent: 2, High: 4, Regular: 7, Low: 14 days.</p></div></section>
    <section class="panel setting-card"><div class="setting-icon">▧</div><div><span>Attachments</span><strong>5 images · 10 MB each</strong><p>Accepted formats are JPG, PNG, and WebP.</p></div></section>
</div>
@endsection
