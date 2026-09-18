@extends('layouts.app')

@section('title', 'Edit Dealer')

@section('content')
<div class="page-heading narrow-heading">
    <p class="eyebrow">DEALER DIRECTORY</p>
    <h1>Edit Dealer</h1>
    <p>Update the details for {{ $dealer->name }}.</p>
</div>

<form class="panel form-panel" method="POST" action="{{ route('dealers.update', $dealer) }}">
    @csrf
    @method('PUT')
    @include('dealers.partials.form')
    <div class="form-actions">
        <a class="button button-light" href="{{ route('dealers.index') }}">Cancel</a>
        <button class="button button-primary" type="submit">Save Changes</button>
    </div>
</form>
@endsection
