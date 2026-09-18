@extends('layouts.app')

@section('title', 'Change Your Password')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/accounts.css') }}?v={{ filemtime(public_path('css/accounts.css')) }}">
@endpush

@section('content')
<dialog class="account-dialog first-login-dialog" data-first-login-dialog open aria-modal="true" aria-labelledby="change-password-title" aria-describedby="change-password-description">
    <p class="eyebrow">WELCOME TO GATEWAY PMS</p>
    <h1 id="change-password-title">Change your password</h1>
    <p id="change-password-description">Hi {{ auth()->user()->name }}, choose a new password to secure your account before continuing.</p>
    @if($errors->any())
        <div class="alert alert-error" role="alert"><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif
    <form method="POST" action="{{ route('password.change.update') }}">
        @csrf
        @method('PUT')
        <div class="form-grid">
            <div class="form-field">
                <label for="password">New Password <em>*</em></label>
                <input id="password" name="password" type="password" autocomplete="new-password" minlength="8" maxlength="72" aria-describedby="password-help" required autofocus>
                <small id="password-help">Use at least 8 characters and choose a password different from the preset.</small>
            </div>
            <div class="form-field">
                <label for="password_confirmation">Confirm New Password <em>*</em></label>
                <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" minlength="8" maxlength="72" required>
            </div>
        </div>
        <div class="form-actions"><button class="button button-primary" type="submit">Change Password &amp; Continue</button></div>
    </form>
    <form class="first-login-logout" method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="button button-light" type="submit">Sign out</button>
    </form>
</dialog>
@endsection

@push('scripts')
<script src="{{ asset('js/accounts.js') }}?v={{ filemtime(public_path('js/accounts.js')) }}" defer></script>
@endpush
