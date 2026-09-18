@extends('layouts.app')

@section('title', 'Areas & Branches')

@section('content')
<div class="page-heading"><p class="eyebrow">NETWORK MONITORING</p><h1>Areas &amp; Branches</h1><p>Read-only coverage overview derived from the official Luzon dealer directory.</p></div>
<div class="area-card-grid">
    @foreach($areas as $area)
        <article class="panel area-card"><span class="area-icon">◎</span><div><h2>{{ $area->area }}</h2><p>{{ $area->dealer_count }} branches · {{ $area->brand_count }} brand groups</p></div></article>
    @endforeach
</div>
<section class="panel">
    <div class="panel-heading"><h2>Branch Coverage</h2><span class="read-only-label">Read only</span></div>
    <div class="area-columns">
        @foreach($dealers as $area => $items)
            <div class="area-list"><h3>{{ $area }}</h3>@foreach($items as $dealer)<div><span>{{ $dealer->name }}</span><small>{{ $dealer->brand }} · {{ $dealer->city }}</small></div>@endforeach</div>
        @endforeach
    </div>
</section>
@endsection
