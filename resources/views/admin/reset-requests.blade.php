@extends('layouts.app')

@section('title', 'Reset Requests')

@section('content')
<div class="page-heading">
    <p class="eyebrow">ADMINISTRATOR · DANGER ZONE</p>
    <h1>Reset Requests</h1>
    <p>Clear all request activity and return the request workflow to an empty state.</p>
</div>

<div class="reset-page-grid">
    <section class="panel reset-warning-panel">
        <div class="reset-warning-head">
            <span class="reset-warning-icon">!</span>
            <div>
                <h2>This action cannot be undone</h2>
                <p>The reset permanently removes request records and their related data.</p>
            </div>
        </div>

        <div class="reset-count-grid">
            <div><strong>{{ number_format($counts['requests']) }}</strong><span>Requests</span></div>
            <div><strong>{{ number_format($counts['attachments']) }}</strong><span>Attachments</span></div>
            <div><strong>{{ number_format($counts['request_logs']) }}</strong><span>Request audit entries</span></div>
        </div>

        <div class="reset-scope">
            <div class="reset-scope-block deleted-scope">
                <h3>Will be deleted</h3>
                <ul>
                    <li>All pending, active, overdue, and completed requests</li>
                    <li>All request assignments, statuses, descriptions, and due dates</li>
                    <li>All uploaded request photos and attachment records</li>
                    <li>Audit history associated with individual requests</li>
                </ul>
            </div>
            <div class="reset-scope-block preserved-scope">
                <h3>Will be preserved</h3>
                <ul>
                    <li>All {{ number_format($counts['dealers']) }} dealer-directory records and contact details</li>
                    <li>All {{ number_format($counts['users']) }} user accounts and role assignments</li>
                    <li>Administrator, PM Manager, PM Admin, Dial-A, and Dealer profiles</li>
                    <li>Non-request audit entries and system configuration</li>
                </ul>
            </div>
        </div>

        <form class="reset-confirm-form" method="POST" action="{{ route('admin.reset-requests.destroy') }}" data-reset-form>
            @csrf
            @method('DELETE')
            <div class="form-field">
                <label for="confirmation">Type <strong>RESET REQUESTS</strong> to confirm</label>
                <input id="confirmation" name="confirmation" value="{{ old('confirmation') }}" autocomplete="off" data-reset-confirmation required>
            </div>
            <div class="form-field">
                <label for="current_password">Administrator password</label>
                <input id="current_password" name="current_password" type="password" autocomplete="current-password" required>
            </div>
            <div class="reset-form-actions">
                <a class="button button-light" href="{{ route('dashboard') }}">Cancel</a>
                <button class="button button-danger" type="submit" data-reset-button disabled>Reset All Requests</button>
            </div>
        </form>
    </section>

    <aside class="panel reset-side-note">
        <span class="reset-side-icon">i</span>
        <h2>After the reset</h2>
        <p>Dashboard request totals will return to zero. Dealers can immediately submit new requests, starting again at sequence <strong>0001</strong> for the day.</p>
        <p>A single administrative audit entry will record who performed the reset and how many records were removed.</p>
    </aside>
</div>
@endsection
