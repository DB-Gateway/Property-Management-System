@extends('layouts.app')

@section('title', 'User Management')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/accounts.css') }}?v={{ filemtime(public_path('css/accounts.css')) }}">
@endpush

@section('content')
<div class="page-heading heading-with-action">
    <div><p class="eyebrow">ADMINISTRATION</p><h1>User Management</h1><p>Manage system accounts, roles, and assigned branches.</p></div>
    <a class="button button-primary" href="{{ route('admin.users.create') }}">Create User</a>
</div>
<section class="panel">
    <form class="filter-bar" method="GET" action="{{ route('admin.users') }}">
        <div class="filter-search"><input name="search" value="{{ request('search') }}" placeholder="Search name or email" aria-label="Search name or email"></div>
        <select name="role" aria-label="Filter by role / designation">
            <option value="">All roles / designations</option>
            @foreach($roles as $value => $label)
                <option value="{{ $value }}" @selected(request('role') === $value || ($value === 'dial_a' && in_array(request('role'), ['pm_support', 'dial_lead'])))>{{ $label }}</option>
            @endforeach
        </select>
        <button class="button button-primary" type="submit">Filter</button>
        <a class="button button-light" href="{{ route('admin.users') }}">Reset</a>
    </form>
    <div class="table-scroll">
        <table class="data-table users-table">
            <thead><tr><th scope="col">Name</th><th scope="col">Email</th><th scope="col">Role / Designation</th><th scope="col">Dealer / Branch</th><th scope="col">Actions</th></tr></thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td><strong>{{ $user->name }}</strong></td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role_label }}</td>
                        <td>{{ $user->dealer?->name ?? 'All areas' }}</td>
                        <td>
                            <div class="user-row-actions">
                                <a class="button button-light" href="{{ route('admin.users.edit', $user) }}" aria-label="Edit {{ $user->name }}">Edit</a>
                                @unless($user->is(auth()->user()))
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" data-delete-user="{{ $user->name }}">
                                        @csrf
                                        @method('DELETE')
                                        <button class="button button-danger" type="submit" aria-label="Delete {{ $user->name }}">Delete</button>
                                    </form>
                                @endunless
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-state">No users match your search.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $users->links() }}</div>
</section>

<dialog class="account-dialog" data-delete-dialog aria-labelledby="delete-user-title" aria-describedby="delete-user-description">
    <h2 id="delete-user-title">Delete user?</h2>
    <p id="delete-user-description"><strong data-delete-user-name></strong> will no longer be able to sign in. Their request history will be preserved.</p>
    <div class="form-actions">
        <button class="button button-light" type="button" data-delete-cancel autofocus>Cancel</button>
        <button class="button button-danger" type="button" data-delete-confirm>Delete User</button>
    </div>
</dialog>
@endsection

@push('scripts')
<script src="{{ asset('js/accounts.js') }}?v={{ filemtime(public_path('js/accounts.js')) }}" defer></script>
@endpush
