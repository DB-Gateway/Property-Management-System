@extends('layouts.app')

@section('title', auth()->user()->isDealer() ? 'My Requests' : 'Requests')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/requests-print.css') }}?v={{ filemtime(public_path('css/requests-print.css')) }}">
@endpush

@section('content')
<?php $showsActivityProgress = true; ?>

@include('partials.dashboard-stats')

<section class="panel" id="requests-panel">
    <div class="panel-heading requests-heading">
        <h2>{{ auth()->user()->isDealer() ? 'My Requests' : 'Requests' }}</h2>
        <div class="requests-actions">
            <a class="button button-light" id="export-requests" href="{{ route('requests.export', array_merge(['month' => $selectedMonth], request()->except('page'))) }}" title="Download all requests matching the current filters">Export Excel</a>
            <button class="button button-primary" id="print-requests" type="button" onclick="window.print()" title="Print the current table page, status totals, and applied filters">Print</button>
        </div>
    </div>

    @include('requests.partials.print-summary')

    <form class="filter-bar" id="requests-filter-form" data-cascading-filter-form method="GET">
        <div class="filter-field filter-field-search {{ request()->filled('search') ? 'is-active' : '' }}" title="Search Ticket Number">
            <span class="filter-icon-box" aria-hidden="true">
                <svg class="filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <label class="filter-label" for="request-filter-search">Search</label>
            <input name="search" id="request-filter-search" value="{{ request('search') }}" placeholder="Ticket Number" aria-label="Search Ticket number, Ticket Number">
        </div>

        <div class="filter-field filter-field-month {{ (request()->filled('month') && request('month') !== now()->format('Y-m')) ? 'is-active' : '' }}" title="Search Month">
            <span class="filter-icon-box" aria-hidden="true">
                <svg class="filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </span>
            <label class="filter-label" for="request-filter-month">Search Month -</label>
            <input type="month" name="month" id="request-filter-month" value="{{ $selectedMonth }}" title="Search Month -" aria-label="Search Month -">
        </div>

        @unless(auth()->user()->isDealer())
            <div class="filter-field filter-field-area {{ (request()->filled('area') || request()->filled('branch')) ? 'is-active' : '' }}" title="Search Area">
                <span class="filter-icon-box" aria-hidden="true">
                    <svg class="filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                        <circle cx="12" cy="10" r="3"></circle>
                    </svg>
                </span>
                <label class="filter-label" for="request-filter-area">Search</label>
                <select name="area" id="request-filter-area" data-cascading="combined-area" data-default-label="All Areas" title="Search Area" aria-label="Search Area">
                    <option value="">All Areas</option>
                    @foreach($areasWithCities as $areaOption => $cityList)
                        <optgroup label="{{ $areaOption }}">
                            <option value="{{ $areaOption }}" @selected(($selectedAreaRaw ?? '') === $areaOption || ($selectedArea === $areaOption && empty($selectedBranch)))>{{ $areaOption }} (All)</option>
                            @foreach($cityList as $cityOption)
                                <?php $combinedVal = $cityOption.' - '.$areaOption; ?>
                                <option value="{{ $combinedVal }}" @selected(($selectedAreaRaw ?? '') === $combinedVal || ($selectedBranch === $cityOption && $selectedArea === $areaOption))>{{ $combinedVal }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
            </div>

            <div class="filter-field filter-field-dealer {{ (request()->filled('brand') || request()->filled('dealer')) ? 'is-active' : '' }}" title="Search Dealer">
                <span class="filter-icon-box" aria-hidden="true">
                    <svg class="filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                        <polyline points="9 22 9 12 15 12 15 22"></polyline>
                    </svg>
                </span>
                <label class="filter-label" for="request-filter-brand">Search</label>
                <select name="brand" id="request-filter-brand" data-cascading="brand" data-default-label="All Dealers" title="Search Dealer" aria-label="Search Dealer">
                    <option value="">All Dealers</option>
                    @foreach($brands as $brandOption)
                        <option value="{{ $brandOption }}" @selected($selectedBrand === $brandOption)>{{ $brandOption }}</option>
                    @endforeach
                </select>
            </div>
        @endunless

        <div class="filter-field filter-field-stage {{ (request()->filled('stage') && request('stage') !== 'all') ? 'is-active' : '' }}" title="Search Activities">
            <span class="filter-icon-box" aria-hidden="true">
                <svg class="filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"></path>
                    <rect x="8" y="2" width="8" height="4" rx="1" ry="1"></rect>
                    <path d="M9 14l2 2 4-4"></path>
                </svg>
            </span>
            <label class="filter-label" for="request-filter-stage">Search</label>
            <select name="stage" id="request-filter-stage" title="Search Activities" aria-label="Search Activities">
                <option value="">All Activity</option>
                <option value="not_acknowledged" @selected(in_array(request('stage'), ['not_acknowledged', 'for_acknowledgement'], true) || in_array(request('status'), ['not_acknowledged', 'for_acknowledgement'], true))>For Acknowledgement</option>
                <option value="inspection" @selected(request('stage') === 'inspection' || request('status') === 'inspection')>Inspection</option>
                <option value="work_order" @selected(request('stage') === 'work_order' || request('status') === 'work_order')>Work Order</option>
                <option value="service_report" @selected(request('stage') === 'service_report' || request('status') === 'service_report')>Service Report</option>
            </select>
        </div>

        <div class="filter-field filter-field-status {{ (request()->filled('status') && request('status') !== 'all') ? 'is-active' : '' }}" title="Search Progress">
            <span class="filter-icon-box" aria-hidden="true">
                <svg class="filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </span>
            <label class="filter-label" for="request-filter-status">Search</label>
            <select name="status" id="request-filter-status" title="Search Progress" aria-label="Search Progress">
                <option value="">All Progress</option>
                <option value="on_going" @selected(request('status') === 'on_going' || request('status') === 'in_progress')>On-going</option>
                <option value="pending" @selected(request('status') === 'pending')>Pending</option>
                <option value="completed" @selected(request('status') === 'completed')>Completed</option>
            </select>
        </div>

        <div class="filter-field filter-field-priority {{ request()->filled('priority') ? 'is-active' : '' }}" title="Search Priority">
            <span class="filter-icon-box" aria-hidden="true">
                <svg class="filter-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                    <line x1="4" y1="22" x2="4" y2="15"></line>
                </svg>
            </span>
            <label class="filter-label" for="request-filter-priority">Search</label>
            <select name="priority" id="request-filter-priority" title="Search Priority" aria-label="Search Priority">
                <option value="">All priorities</option>
                @foreach(['urgent', 'regular'] as $priority)<option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ ucfirst($priority) }}</option>@endforeach
            </select>
        </div>

        <?php
            $hasActiveFilters = request()->filled('search')
                || (request()->filled('month') && request('month') !== now()->format('Y-m'))
                || (!auth()->user()->isDealer() && (request()->filled('area') || request()->filled('branch') || request()->filled('brand') || request()->filled('dealer')))
                || (request()->filled('stage') && request('stage') !== 'all')
                || (request()->filled('status') && request('status') !== 'all')
                || request()->filled('priority');
        ?>
        <button class="button button-primary" type="submit">Filter</button>
        <a class="button button-light {{ $hasActiveFilters ? 'has-active' : '' }}" href="{{ route('requests.index') }}">Reset</a>
    </form>

    <script id="requests-directory-data" data-cascading-matrix type="application/json">
    {!! json_encode($directoryMatrix) !!}
    </script>

    <div class="table-scroll">
        <table class="data-table requests-table">
            <thead>
                <tr>
                    <th>REQUEST NO.</th>
                    <th>BRANCH / AREA</th>
                    <th>REPAIR CATEGORY</th>
                    <th>PRIORITY</th>
                    <th>Request Submission Date &amp; Time</th>
                    <th>Activity Completion Date &amp; Time</th>
                    <th>Activity Progress</th>
                    @if(auth()->user()->isManager())
                        <th>ACTIONS</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $item)
                    <tr>
                        <td><a class="reference" href="{{ route('requests.show', $item) }}">{{ $item->reference_no }}</a></td>
                        <td title="{{ $item->dealer?->name }}"><strong>{{ $item->display_branch }}</strong><small>{{ $item->display_dealer_name }} · {{ $item->area ?? $item->dealer?->area }}</small></td>
                        <td><span class="badge category-badge">{{ $item->request_type }}</span></td>
                        <td><span class="badge priority-{{ $item->priority }}">{{ ucfirst($item->priority) }}</span></td>
                        <td>
                            <div class="submitted-date-block">
                                <span class="date-val">{{ $item->created_at->format('M d, Y') }}</span>
                                <small class="time-val">{{ $item->created_at->format('h:i A') }}</small>
                            </div>
                        </td>
                        <td>
                            <div class="submitted-date-block">
                                @if($item->completed_at)
                                    <span class="date-val">{{ $item->completed_at->format('M d, Y') }}</span>
                                    <small class="time-val">{{ $item->completed_at->format('h:i A') }}</small>
                                @else
                                    <span class="date-val">&mdash;</span>
                                    <small class="time-val date-pending">Not completed</small>
                                @endif
                            </div>
                        </td>
                        <td class="activity-progress-cell">@include('requests.partials.activity-progress', ['requestItem' => $item, 'compact' => true])</td>
                        @if(auth()->user()->isManager())
                            <td>
                                <button type="button" class="table-action button-link" data-open-priority-modal
                                        data-reference="{{ $item->reference_no }}"
                                        data-priority="{{ $item->priority }}"
                                        data-remarks="{{ $item->priority_remarks ?? '' }}"
                                        data-url="{{ route('requests.priority', $item) }}"
                                        title="Edit Priority Status"
                                        style="background:none; border:none; padding:0; cursor:pointer; font-size:12px;">
                                    Edit Priority
                                </button>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="{{ auth()->user()->isManager() ? 8 : 7 }}"><div class="empty-state">No matching requests found.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $requests->links() }}</div>
</section>

@if(auth()->user()->isManager())
    @include('requests.partials.edit-priority-modal')
@endif
@endsection
