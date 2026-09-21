@extends('layouts.app')

@section('title', $user->exists ? 'Edit User' : 'Create User')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/accounts.css') }}?v={{ filemtime(public_path('css/accounts.css')) }}">
@endpush

@section('content')
<div class="page-heading narrow-heading">
    <p class="eyebrow">USER MANAGEMENT</p>
    <h1>{{ $user->exists ? 'Edit User' : 'Create User' }}</h1>
    <p>{{ $user->exists ? 'Update account information, role, and dealer assignment.' : 'Add an account and assign the correct role and dealer access.' }}</p>
</div>

<form class="panel form-panel" method="POST" action="{{ $user->exists ? route('admin.users.update', $user) : route('admin.users.store') }}">
    @csrf
    @if($user->exists) @method('PUT') @endif
    <div class="form-section">
        <h2>Account Information</h2>
        <div class="form-grid two-col">
            <div class="form-field">
                <label for="name">Name <em>*</em></label>
                <input id="name" name="name" value="{{ old('name', $user->name) }}" maxlength="255" autocomplete="name" required autofocus>
            </div>
            <div class="form-field">
                <label for="email">Email <em>*</em></label>
                <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" maxlength="255" autocomplete="email" required>
            </div>
            <div class="form-field">
                <label for="role">Role / Designation <em>*</em></label>
                <select id="role" name="role" required data-user-role>
                    @foreach($roles as $value => $label)
                        <option value="{{ $value }}" @selected(old('role', $user->isDialA() ? 'dial_a' : $user->role) === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                @if($user->is(auth()->user()))<small>Your account must keep its Administrator role.</small>@endif
            </div>
            <div class="form-field" data-dealer-field @if(old('role', $user->role) !== 'dealer') hidden @endif>
                <label for="dealer_id">Dealer / Branch <em data-dealer-required @if(old('role', $user->role) !== 'dealer') hidden @endif>*</em></label>
                <select id="dealer_id" name="dealer_id" data-user-dealer data-user-editing="{{ $user->exists ? 'true' : 'false' }}" @required(old('role', $user->role) === 'dealer') aria-describedby="dealer-help">
                    <option value="">Select a dealer / branch</option>
                    @foreach($dealers as $dealer)
                        <option
                            value="{{ $dealer->id }}"
                            data-dealer-name="{{ $dealer->name }}"
                            data-dealer-brand="{{ $dealer->brand }}"
                            data-dealer-city="{{ $dealer->city }}"
                            data-dealer-area="{{ $dealer->area }}"
                            data-dealer-address="{{ $dealer->address }}"
                            data-dealer-contact="{{ collect([$dealer->point_person_1, $dealer->contact_1, $dealer->point_person_2, $dealer->contact_2])->filter()->implode(' · ') }}"
                            data-dealer-edit-url="{{ route('dealers.edit', $dealer) }}"
                            @selected((string) old('dealer_id', $user->dealer_id) === (string) $dealer->id)
                        >{{ $dealer->name }}{{ $dealer->city ? ' / '.$dealer->city : '' }}{{ $dealer->brand ? ' / '.$dealer->brand : '' }}</option>
                    @endforeach
                </select>
                <small id="dealer-help">Required for Dealer accounts. This determines which branch and requests the user can access.</small>
            </div>
        </div>

        <section class="dealer-information" data-dealer-information hidden aria-live="polite">
            <div class="dealer-information-heading">
                <div>
                    <p class="eyebrow">ASSIGNED DEALER</p>
                    <h3 data-dealer-info-name></h3>
                </div>
                @if($user->exists)
                    <a class="button button-light" href="#" data-dealer-edit-link hidden>Edit Dealer Information</a>
                @endif
            </div>
            <dl class="dealer-information-grid">
                <div><dt>Brand</dt><dd data-dealer-info-brand>—</dd></div>
                <div><dt>Branch (City)</dt><dd data-dealer-info-city>—</dd></div>
                <div><dt>Area</dt><dd data-dealer-info-area>—</dd></div>
                <div><dt>Address</dt><dd data-dealer-info-address>—</dd></div>
                <div class="dealer-information-wide"><dt>Contacts</dt><dd data-dealer-info-contact>—</dd></div>
            </dl>
        </section>
    </div>
    @unless($user->exists)
        <div class="preset-password-note">
            <strong>Preset password: <code>{{ \App\Models\User::DEFAULT_PASSWORD }}</code></strong>
            <p>The user will be prompted to choose a new password when they first sign in.</p>
        </div>
    @endunless
    <div class="form-actions">
        <a class="button button-light" href="{{ route('admin.users') }}">Cancel</a>
        <button class="button button-primary" type="submit">{{ $user->exists ? 'Save Changes' : 'Create User' }}</button>
    </div>
</form>

@if($user->exists)
    <section class="panel form-panel password-access-panel">
        <div class="form-section no-border">
            <h2>Password Access</h2>
            @if($user->is(auth()->user()))
                <p class="section-help">For security, change your own password from <a href="{{ route('profile.edit') }}">My Profile</a>.</p>
            @else
                <p class="section-help">Use email authentication when the user can access their mailbox. If the mailbox is unavailable, set a password manually after confirming your administrator password.</p>
                @error('password_email')<div class="alert alert-error password-access-alert">{{ $message }}</div>@enderror
                @error('user')<div class="alert alert-error password-access-alert">{{ $message }}</div>@enderror

                <div class="password-option-grid">
                    <article class="password-option-card">
                        <div>
                            <p class="eyebrow">ACTIVE EMAIL</p>
                            <h3>Send a secure reset link</h3>
                            <p>The user authenticates through a time-limited link sent to <strong>{{ $user->email }}</strong> and chooses their own password.</p>
                        </div>
                        <form method="POST" action="{{ route('admin.users.password.email', $user) }}">
                            @csrf
                            <button class="button button-primary" type="submit">Send Password Reset Email</button>
                        </form>
                    </article>

                    <article class="password-option-card">
                        <div>
                            <p class="eyebrow">EMAIL UNAVAILABLE</p>
                            <h3>Set password manually</h3>
                            <p>Confirm your administrator identity before replacing this user’s password.</p>
                        </div>
                        <form method="POST" action="{{ route('admin.users.password.update', $user) }}">
                            @csrf
                            @method('PATCH')
                            <div class="form-field">
                                <label for="password">New Password <em>*</em></label>
                                <input id="password" name="password" type="password" minlength="8" maxlength="72" autocomplete="new-password" required>
                            </div>
                            <div class="form-field">
                                <label for="password_confirmation">Confirm New Password <em>*</em></label>
                                <input id="password_confirmation" name="password_confirmation" type="password" minlength="8" maxlength="72" autocomplete="new-password" required>
                            </div>
                            <div class="form-field">
                                <label for="admin_password">Your Administrator Password <em>*</em></label>
                                <input id="admin_password" name="admin_password" type="password" autocomplete="current-password" required>
                                <small>This confirms that you authorize the password change.</small>
                            </div>
                            <label class="password-change-check">
                                <input type="checkbox" name="require_password_change" value="1" @checked(old('require_password_change', true))>
                                <span>Require the user to choose a new password at next sign-in</span>
                            </label>
                            <button class="button button-primary" type="submit">Update User Password</button>
                        </form>
                    </article>
                </div>
            @endif
        </div>
    </section>
@endif
@endsection

@push('scripts')
<script src="{{ asset('js/accounts.js') }}?v={{ filemtime(public_path('js/accounts.js')) }}" defer></script>
@endpush
