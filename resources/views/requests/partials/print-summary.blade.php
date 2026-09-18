@php
    $dealerName = null;
    if (request()->filled('dealer_id')) {
        $dealerName = $dealers->firstWhere('id', request('dealer_id'))?->name;
    } elseif (auth()->user()->isDealer() && auth()->user()->dealer) {
        $dealerName = auth()->user()->dealer->name;
    }

    $monthLabel = !empty($selectedMonth) ? \Carbon\CarbonImmutable::createFromFormat('!Y-m', $selectedMonth)->format('F Y') : null;

    $printFilters = array_filter([
        'Month' => $monthLabel,
        'Search' => request('search'),
        'Area' => $selectedBranch && $selectedArea ? ($selectedBranch.' - '.$selectedArea) : ($selectedArea ?: $selectedBranch),
        'Dealer' => $dealerName ?: $selectedBrand,
        'Activity' => match (request('stage')) {
            'not_acknowledged', 'for_acknowledgement' => 'For Acknowledgement',
            'inspection' => 'Inspection',
            'work_order' => 'Work Order',
            'service_report' => 'Service Report',
            default => request()->filled('stage') ? ucfirst(str_replace('_', ' ', request('stage'))) : (in_array(request('status'), ['not_acknowledged', 'for_acknowledgement'], true) ? 'For Acknowledgement' : null),
        },
        'Progress' => match (request('status')) {
            'pending' => 'Pending',
            'on_going', 'in_progress' => 'On-going',
            'completed' => 'Completed',
            'overdue' => 'Overdue',
            'not_acknowledged', 'for_acknowledgement' => null,
            'awaiting_dealer' => 'Awaiting dealer',
            default => request()->filled('status') ? ucfirst(str_replace('_', ' ', request('status'))) : 'All Progress',
        },
        'Priority' => request()->filled('priority') ? ucfirst(request('priority')) : null,
    ], fn ($value) => $value !== null && $value !== '');
@endphp

<div class="requests-print-summary">
    <div class="requests-print-filters">
        @foreach($printFilters as $label => $value)
            <span><strong>{{ $label }}:</strong> {{ $value }}</span>
        @endforeach
    </div>
    <p>Showing {{ $requests->firstItem() ?? 0 }}–{{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }} matching requests · Page {{ $requests->currentPage() }} of {{ $requests->lastPage() }}</p>
</div>
