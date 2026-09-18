@extends('layouts.app')

@section('title', 'Dealer Directory')

@section('content')

<div class="page-heading heading-with-action">
    <div>
        <p class="eyebrow">DEALERS</p>
        <h1>Dealer Directory</h1>
        <p>{{ number_format($dealers->total()) }} {{ $dealers->total() === 1 ? 'dealer' : 'dealers' }} matching your filters.</p>
    </div>
    <div class="dealer-actions">
        <a class="button button-light" href="{{ route('dealers.print', ['search' => request('search'), 'city' => $selectedCity, 'area' => $selectedAreaRaw ?: $selectedArea, 'brand' => $selectedBrand]) }}" target="_blank" rel="noopener" title="Print all dealers matching the current filters">Print</a>
        <a class="button button-light" href="{{ route('dealers.export', ['search' => request('search'), 'city' => $selectedCity, 'area' => $selectedAreaRaw ?: $selectedArea, 'brand' => $selectedBrand]) }}" title="Download all dealers matching the current filters">Export Excel</a>
        @if(auth()->user()->isManager())
            <a class="button button-light" href="{{ route('dealers.import') }}">Import Excel</a>
            <a class="button button-primary" href="{{ route('dealers.create') }}">Add Dealer</a>
        @endif
    </div>
</div>

<section class="panel">
    <form class="filter-bar dealer-filter-bar" id="dealer-filter-form" data-cascading-filter-form method="GET">
        <input type="hidden" name="city" id="dealer-filter-city" value="{{ $selectedCity }}">

        <div class="filter-field filter-field-search" title="Search Dealer">
            <span class="filter-icon-box" aria-hidden="true">
                <svg class="filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <label class="filter-label" for="dealer-filter-search">Search Dealer</label>
            <input name="search" id="dealer-filter-search" value="{{ request('search') }}" placeholder="Search dealer, city, address, or point person" aria-label="Search dealer, city, address, or point person">
        </div>

        <div class="filter-field filter-field-area" title="Search Area">
            <span class="filter-icon-box" aria-hidden="true">
                <svg class="filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </span>
            <label class="filter-label" for="dealer-filter-area">Search Area</label>
            <select name="area" id="dealer-filter-area" data-cascading="combined-area" data-default-label="All Areas" title="Search Area" aria-label="Search Area">
                <option value="">All Areas</option>
                @foreach($areasWithCities as $areaOption => $cityList)
                    <optgroup label="{{ $areaOption }}">
                        <option value="{{ $areaOption }}" @selected(($selectedAreaRaw ?? '') === $areaOption || ($selectedArea === $areaOption && empty($selectedCity)))>{{ $areaOption }} (All)</option>
                        @foreach($cityList as $cityOption)
                            @php($combinedVal = $cityOption.' - '.$areaOption)
                            <option value="{{ $combinedVal }}" @selected(($selectedAreaRaw ?? '') === $combinedVal || ($selectedCity === $cityOption && $selectedArea === $areaOption))>{{ $combinedVal }}</option>
                        @endforeach
                    </optgroup>
                @endforeach
            </select>
        </div>

        <div class="filter-field filter-field-brand" title="Search Brand">
            <span class="filter-icon-box" aria-hidden="true">
                <svg class="filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
            </span>
            <label class="filter-label" for="dealer-filter-brand">Search Brand</label>
            <select name="brand" id="dealer-filter-brand" data-cascading="brand" data-default-label="All Brands" title="Search Brand" aria-label="Search Brand">
                <option value="">All Brands</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand }}" @selected($selectedBrand === $brand)>{{ $brand }}</option>
                @endforeach
            </select>
        </div>

        <button class="button button-primary" type="submit">Filter</button>
        <a class="button button-light" href="{{ route('dealers.index') }}">Reset</a>
    </form>
    <div class="table-scroll">
        <table class="data-table dealer-table">
            <colgroup>
                <col class="dealer-col-number">
                <col class="dealer-col-name">
                @if(auth()->user()->canManageDealers())
                    <col class="dealer-col-actions">
                @endif
                <col class="dealer-col-city">
                <col class="dealer-col-area">
                <col class="dealer-col-address">
                <col class="dealer-col-person">
                <col class="dealer-col-contact">
                <col class="dealer-col-person">
                <col class="dealer-col-contact">
            </colgroup>
            <thead>
                <tr>
                    <th scope="col" class="dealer-number">No.</th>
                    <th scope="col">Dealer / Brand</th>
                    @if(auth()->user()->canManageDealers())
                        <th scope="col">Actions</th>
                    @endif
                    <th scope="col">Branch (City)</th>
                    <th scope="col">Area</th>
                    <th scope="col">Address</th>
                    <th scope="col">Point Person 1</th>
                    <th scope="col">Contact</th>
                    <th scope="col">Point Person 2</th>
                    <th scope="col">Contact</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dealers as $dealer)
                    <tr>
                        <td class="dealer-number">{{ $dealer->source_no }}</td>
                        <td class="dealer-identity"><strong>{{ $dealer->brand ?: $dealer->name }}</strong><small>{{ $dealer->name }}</small></td>
                        @if(auth()->user()->canManageDealers())
                            <td><a class="table-action" href="{{ route('dealers.edit', $dealer) }}" aria-label="Edit {{ $dealer->name }}">Edit</a></td>
                        @endif
                        <td class="dealer-city"><strong>{{ $dealer->city ?: '—' }}</strong></td>
                        <td class="dealer-area"><span class="badge badge-regular">{{ $dealer->area ?: '—' }}</span></td>
                        <td class="dealer-address">{{ $dealer->address ?: '—' }}</td>
                        <td class="dealer-person">{{ $dealer->point_person_1 ?: '—' }}</td>
                        <td class="dealer-contact nowrap">{{ $dealer->contact_1 ?: '—' }}</td>
                        <td class="dealer-person">{{ $dealer->point_person_2 ?: '—' }}</td>
                        <td class="dealer-contact nowrap">{{ $dealer->contact_2 ?: '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->canManageDealers() ? 10 : 9 }}" style="text-align:center; padding:28px; color:var(--muted); font-size:14px;">
                            No dealers found matching your criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $dealers->links() }}</div>
</section>

<script id="dealer-directory-data" data-cascading-matrix type="application/json">
{!! json_encode($directoryMatrix, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) !!}
</script>
@endsection
