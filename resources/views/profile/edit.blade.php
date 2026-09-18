@extends('layouts.app')

@section('title', 'My Profile')

@section('content')
<div class="page-heading"><p class="eyebrow">ACCOUNT</p><h1>My Profile</h1><p>Update your display information or change your password.</p></div>
<div class="profile-page-grid">
    <aside class="panel profile-summary"><span class="large-avatar">{{ auth()->user()->initials }}</span><h2>{{ auth()->user()->name }}</h2><p>{{ auth()->user()->role_label }}</p><dl><div><dt>Email</dt><dd>{{ auth()->user()->email }}</dd></div><div><dt>Designation</dt><dd>{{ auth()->user()->designation ?: 'Not set' }}</dd></div>@if(auth()->user()->dealer)<div><dt>Dealer</dt><dd>{{ auth()->user()->dealer->name }}</dd></div>@endif</dl></aside>
    <form class="panel form-panel profile-form" method="POST" action="{{ route('profile.update') }}">
        @csrf @method('PATCH')
        <div class="form-section"><h2>Profile Information</h2><div class="form-grid two-col"><div class="form-field"><label for="name">Name <em>*</em></label><input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required></div><div class="form-field"><label for="designation">Designation</label><input id="designation" name="designation" value="{{ old('designation', auth()->user()->designation) }}"></div><div class="form-field"><label>Email</label><input value="{{ auth()->user()->email }}" readonly><small>Contact the administrator to change your sign-in email.</small></div></div></div>
        <div class="form-section no-border"><h2>Change Password</h2><p class="section-help">Leave these fields blank to keep your existing password.</p><div class="form-grid two-col"><div class="form-field"><label for="current_password">Current Password</label><input id="current_password" name="current_password" type="password"></div><div></div><div class="form-field"><label for="password">New Password</label><input id="password" name="password" type="password"></div><div class="form-field"><label for="password_confirmation">Confirm New Password</label><input id="password_confirmation" name="password_confirmation" type="password"></div></div></div>
        <div class="form-actions"><button class="button button-primary" type="submit">Save Profile</button></div>
    </form>
</div>
@endsection
