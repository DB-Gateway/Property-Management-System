@extends('layouts.app')

@section('title', 'Reports')

@section('content')

@include('partials.dashboard-stats')

<div class="page-header" style="display:flex; justify-content:space-between; align-items:center; margin-bottom: 20px;">
    <div>
        <h1>Request Operations Report</h1>
    </div>
    <a class="button button-primary" href="{{ route('reports.print', request()->query()) }}" target="_blank" rel="noopener">Print / Publish Report</a>
</div>

<section class="panel report-filter-panel">
    @php
        $stageFilter = $filters['stage'] ?? '';
        $statusFilter = $filters['status'] ?? '';
        $hasStageOrStatusFilter = (!empty($stageFilter) && $stageFilter !== 'all') || (!empty($statusFilter) && $statusFilter !== 'all' && (request()->has('status') || $statusFilter !== 'completed'));
    @endphp

    @if($hasStageOrStatusFilter)
        <div class="rfg-quick-filter-banner" id="rfg-quick-filter-banner">
            <div class="rfg-qfb-left">
                <span class="rfg-qfb-title">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                    </svg>
                    Quick Filter Active:
                </span>
                <span class="rfg-qfb-badge">
                    @if(!empty($stageFilter) && $stageFilter !== 'all')
                        <span class="rfg-qfb-stage">{{ ucfirst(str_replace('_', ' ', $stageFilter)) }}</span>
                        @if(!empty($statusFilter) && $statusFilter !== 'all')
                            <span class="rfg-qfb-sep">&rsaquo;</span>
                        @endif
                    @endif
                    @if(!empty($statusFilter) && $statusFilter !== 'all')
                        <span class="rfg-qfb-status">
                            @if($statusFilter === 'not_acknowledged')
                                For Acknowledgement
                            @elseif($statusFilter === 'aging')
                                Aging Request
                            @elseif($statusFilter === 'on_going')
                                On-going
                            @else
                                {{ ucfirst(str_replace('_', ' ', $statusFilter)) }}
                            @endif
                        </span>
                    @endif
                </span>
                <span class="rfg-qfb-count">({{ number_format($requests->total()) }} {{ Str::plural('result', $requests->total()) }})</span>
            </div>
            <a class="rfg-qfb-clear" href="{{ route('reports.index', array_merge(request()->except(['page', 'stage', 'status']), ['status' => 'all'])) }}" data-clear-quick-filter title="Clear this stage/status filter">
                <span>&times;</span> Clear Quick Filter
            </a>
        </div>
    @endif

    <form class="report-filter-grid" id="report-filter-form" data-cascading-filter-form method="GET" action="{{ route('reports.index') }}">
        {{-- Row 1 --}}
        <div class="rfg-field rfg-search {{ !empty($filters['search']) ? 'is-active' : '' }}" title="Search by reference, dealer, requester, or type">
            <span class="rfg-icon-box" aria-hidden="true">
                <svg class="rfg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
            </span>
            <input id="search" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Search ref, dealer, type..." aria-label="Search reference, dealer, requester, or type">
        </div>

        <div class="rfg-field rfg-date-period {{ !empty($filters['date_period']) ? 'is-active' : '' }}" title="Choose a date filter">
            <span class="rfg-icon-box" aria-hidden="true">
                <svg class="rfg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </span>
            <select id="date_period" name="date_period" data-date-period-selector aria-label="Date filter type" aria-controls="period-day-field period-week-field period-month-field">
                <option value="month" @selected(($filters['date_period'] ?? 'month') === 'month')>Month</option>
                <option value="day" @selected(($filters['date_period'] ?? '') === 'day')>Day</option>
                <option value="week" @selected(($filters['date_period'] ?? '') === 'week')>Week</option>
            </select>
        </div>

        <div id="period-day-field" class="rfg-field rfg-period-value {{ !empty($filters['period_day']) ? 'is-active' : '' }}" data-date-period-control="day" title="Choose a weekday" @if(($filters['date_period'] ?? 'month') !== 'day') hidden @endif>
            <span class="rfg-icon-box" aria-hidden="true">
                <svg class="rfg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="9"></circle>
                    <polyline points="12 7 12 12 15 14"></polyline>
                </svg>
            </span>
            <select id="period_day" name="period_day" data-date-period-input aria-label="Weekday" required @disabled(($filters['date_period'] ?? 'month') !== 'day')>
                <option value="">Choose Day</option>
                @foreach(['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'] as $day)
                    <option value="{{ $day }}" @selected(($filters['period_day'] ?? '') === $day)>{{ ucfirst($day) }}</option>
                @endforeach
            </select>
        </div>

        <div id="period-week-field" class="rfg-field rfg-period-value {{ !empty($filters['period_week']) ? 'is-active' : '' }}" data-date-period-control="week" title="Choose one calendar week" @if(($filters['date_period'] ?? 'month') !== 'week') hidden @endif>
            <span class="rfg-icon-box" aria-hidden="true">
                <svg class="rfg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </span>
            <span class="rfg-prefix">Week</span>
            <input id="period_week" type="week" name="period_week" value="{{ $filters['period_week'] ?? '' }}" data-date-period-input aria-label="Calendar week" required @disabled(($filters['date_period'] ?? 'month') !== 'week')>
        </div>

        <div id="period-month-field" class="rfg-field rfg-period-value {{ !empty($filters['period_month']) ? 'is-active' : '' }}" data-date-period-control="month" title="Choose a calendar month" @if(($filters['date_period'] ?? 'month') !== 'month') hidden @endif>
            <span class="rfg-icon-box" aria-hidden="true">
                <svg class="rfg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>
                    <line x1="3" y1="10" x2="21" y2="10"></line>
                </svg>
            </span>
            <span class="rfg-prefix">Month</span>
            <input id="period_month" type="month" name="period_month" value="{{ $filters['period_month'] ?? now()->format('Y-m') }}" data-date-period-input aria-label="Calendar month" required @disabled(($filters['date_period'] ?? 'month') !== 'month')>
        </div>

        <div class="rfg-field rfg-stage {{ !empty($filters['stage']) && $filters['stage'] !== 'all' ? 'is-active' : '' }}" title="Workflow Stage">
            <span class="rfg-icon-box" aria-hidden="true">
                <svg class="rfg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
                </svg>
            </span>
            <select id="stage" name="stage" aria-label="Workflow Stage">
                <option value="">All Stages</option>
                <option value="inspection" @selected(($filters['stage'] ?? '') === 'inspection')>Inspection</option>
                <option value="work_order" @selected(($filters['stage'] ?? '') === 'work_order')>Work Order</option>
                <option value="service_report" @selected(($filters['stage'] ?? '') === 'service_report')>Service Report</option>
            </select>
        </div>

        <div class="rfg-field rfg-status {{ !empty($filters['status']) && $filters['status'] !== 'all' ? 'is-active' : '' }}" title="Status">
            <span class="rfg-icon-box" aria-hidden="true">
                <svg class="rfg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
            </span>
            <select id="status" name="status" aria-label="Status">
                <option value="all" @selected(($filters['status'] ?? '') === 'all')>All Statuses</option>
                <option value="pending" @selected(($filters['status'] ?? '') === 'pending')>Pending</option>
                <option value="on_going" @selected(($filters['status'] ?? '') === 'on_going' || ($filters['status'] ?? '') === 'in_progress')>On-going</option>
                <option value="not_acknowledged" @selected(in_array($filters['status'] ?? '', ['not_acknowledged', 'for_acknowledgement'], true))>For Acknowledgement</option>
                <option value="completed" @selected(($filters['status'] ?? '') === 'completed')>Completed</option>
                <option value="aging" @selected(in_array($filters['status'] ?? '', ['aging', 'overdue'], true))>Aging Request</option>
            </select>
        </div>

        <div class="rfg-field rfg-priority {{ !empty($filters['priority']) ? 'is-active' : '' }}" title="Priority">
            <span class="rfg-icon-box" aria-hidden="true">
                <svg class="rfg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"></path>
                    <line x1="4" y1="22" x2="4" y2="15"></line>
                </svg>
            </span>
            <select id="priority" name="priority" aria-label="Priority">
                <option value="">All Priorities</option>
                @foreach(['urgent', 'regular'] as $priority)
                    <option value="{{ $priority }}" @selected(($filters['priority'] ?? '') === $priority)>{{ ucfirst($priority) }}</option>
                @endforeach
            </select>
        </div>

        {{-- Row 2 --}}
        <div class="rfg-field rfg-area {{ (!empty($filters['area']) || !empty($filters['branch'])) ? 'is-active' : '' }}" title="Branch / Area">
            <span class="rfg-icon-box" aria-hidden="true">
                <svg class="rfg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                    <circle cx="12" cy="10" r="3"></circle>
                </svg>
            </span>
            <select id="area" name="area" data-cascading="combined-area" data-default-label="All Areas" aria-label="Branch / Area">
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

        <div class="rfg-field rfg-brand {{ !empty($filters['brand']) ? 'is-active' : '' }}" title="Brand">
            <span class="rfg-icon-box" aria-hidden="true">
                <svg class="rfg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                </svg>
            </span>
            <select id="brand" name="brand" data-cascading="brand" data-default-label="All Brands" aria-label="Brand">
                <option value="">All Brands</option>
                @foreach($brands as $brand)
                    <option value="{{ $brand }}" @selected(($filters['brand'] ?? '') === $brand)>{{ $brand }}</option>
                @endforeach
            </select>
        </div>

        <div class="rfg-field rfg-type {{ !empty($filters['request_type']) ? 'is-active' : '' }}" title="Request Type">
            <span class="rfg-icon-box" aria-hidden="true">
                <svg class="rfg-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                    <line x1="7" y1="7" x2="7.01" y2="7"></line>
                </svg>
            </span>
            <select id="request_type" name="request_type" aria-label="Request Type">
                <option value="">All Types</option>
                @foreach($requestTypes as $type)
                    <option value="{{ $type }}" @selected(($filters['request_type'] ?? '') === $type)>{{ $type }}</option>
                @endforeach
            </select>
        </div>

        <div class="rfg-actions">
            <button class="button button-primary rfg-btn rfg-btn-submit" type="submit" title="Apply filters">
                <svg class="rfg-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
                <span>Filter</span>
            </button>
            <?php
                $hasCustomFilters = collect($filters)->contains(function ($val, $key) {
                    if ($val === null || $val === '') return false;
                    if ($key === 'date_period' && $val === 'month') return false;
                    if ($key === 'period_month' && $val === now()->format('Y-m')) return false;
                    if ($key === 'status' && $val === 'completed' && !request()->has('status')) return false;
                    return true;
                });
            ?>
            <a class="button button-light rfg-btn rfg-btn-reset {{ $hasCustomFilters ? 'has-active' : '' }}" href="{{ route('reports.index') }}" title="Clear all filters">
                <svg class="rfg-btn-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="1 4 1 10 7 10"></polyline>
                    <path d="M3.51 15a9 9 0 1 0 2.13-9.36L1 10"></path>
                </svg>
                <span>Reset</span>
            </a>
        </div>
    </form>

    <script id="reports-directory-data" data-cascading-matrix type="application/json">
    {!! json_encode($directoryMatrix) !!}
    </script>
</section>

<section class="panel report-results">
    <div class="panel-heading"><h2>Report Preview</h2><span>{{ number_format($requests->total()) }} {{ Str::plural('request', $requests->total()) }}@if($dateFilterLabel) &middot; {{ $dateFilterLabel }}@endif</span></div>
    <div class="table-scroll">
        <table class="data-table report-table">
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Request / Dealer</th>
                    <th>Requested</th>
                    <th>Completed</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Request Attachments</th>
                    <th>Activity Progress</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $item)
                    <tr>
                        <td><a class="reference" href="{{ route('requests.show', $item) }}">{{ $item->reference_no }}</a></td>
                        <td><strong>{{ $item->request_type }}</strong><small>{{ $item->display_dealer_name }} ({{ $item->display_branch }}) · {{ $item->area }}</small></td>
                        <td class="nowrap">{{ $item->request_date->format('M d, Y') }}</td>
                        <td class="nowrap">{{ $item->completed_at?->format('M d, Y') ?? '—' }}</td>
                        <td><span class="badge status-{{ $item->status }}">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span></td>
                        <td><span class="badge priority-{{ $item->priority }}">{{ ucfirst($item->priority) }}</span></td>
                        <td class="report-attachments-col">
                            <div class="report-attachments-cell">
                                @if($item->attachments->isNotEmpty())
                                    <div class="attachment-chips">
                                        @foreach($item->attachments as $file)
                                            <a class="attachment-chip" href="{{ route('attachments.show', $file) }}" target="_blank" rel="noopener" title="{{ $file->original_name }} ({{ number_format($file->size / 1024, 1) }} KB)">
                                                @if($file->isImage())
                                                    <img class="chip-img" src="{{ route('attachments.show', $file) }}" alt="{{ $file->original_name }}">
                                                @else
                                                    <span class="workflow-file-badge {{ $file->fileBadgeClass() }}" style="font-size: 9px; padding: 2px 4px; margin-right: 4px;">{{ $file->fileTypeLabel() }}</span>
                                                @endif
                                                <span class="chip-text">{{ Str::limit($file->original_name, 18) }}</span>
                                            </a>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="empty-attachment-note">None</span>
                                @endif
                            </div>
                        </td>
                        <td class="activity-progress-cell">@include('requests.partials.activity-progress', ['requestItem' => $item, 'compact' => true])</td>
                        <td><a class="table-action" href="{{ route('requests.show', $item) }}">View →</a></td>
                    </tr>
                @empty
                    <tr><td colspan="9"><div class="empty-state">No requests match the selected report filters.</div></td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="pagination-wrap">{{ $requests->links() }}</div>
</section>
@endsection
