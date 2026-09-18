@extends('layouts.app')

@section('title', 'Add Dealer')

@section('content')
<div class="page-heading narrow-heading">
    <p class="eyebrow">DEALER DIRECTORY</p>
</div>

<form class="panel form-panel" method="POST" action="{{ route('dealers.store') }}">
    @csrf
    @include('dealers.partials.form')
    <div class="form-actions">
        <a class="button button-light" href="{{ route('dealers.index') }}">Cancel</a>
        <button class="button button-primary" type="submit">Create Dealer</button>
    </div>
</form>
@endsection
