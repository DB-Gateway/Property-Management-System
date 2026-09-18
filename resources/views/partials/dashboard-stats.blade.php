<?php
    $isReportsPage = request()->routeIs('reports.*') || !empty($isReport);
    $isRequestsPage = request()->routeIs('requests.*') || !empty($isRequestPage);
    $isFilterPage = $isReportsPage || $isRequestsPage;

    $activeStage = $filters['stage'] ?? request('stage', '');
    $activeStatus = $filters['status'] ?? request('status', '');
    $baseFilterParams = request()->except(['page', 'stage', 'status']);
    $targetRoute = $isReportsPage ? 'reports.index' : 'requests.index';

    $getUrl = function ($stage, $status) use ($baseFilterParams, $activeStage, $activeStatus, $targetRoute, $isFilterPage, $isReportsPage) {
        if ($isFilterPage) {
            $isActive = ($activeStage === $stage) && ($activeStatus === $status || ($status === '' && ($activeStatus === '' || $activeStatus === 'all')));
            if ($isActive) {
                $cleared = $baseFilterParams;
                if ($isReportsPage) {
                    $cleared['status'] = 'all';
                }
                return route($targetRoute, $cleared);
            }
        }
        $params = $baseFilterParams;
        if (!empty($stage)) $params['stage'] = $stage;
        if (!empty($status)) $params['status'] = $status;
        return route($targetRoute, $params);
    };

    $isItemActive = function ($stage, $status) use ($activeStage, $activeStatus, $isFilterPage) {
        if (! $isFilterPage) {
            return false;
        }
        if (!empty($stage) && !empty($status)) {
            return $activeStage === $stage && $activeStatus === $status;
        }
        if (!empty($stage) && empty($status)) {
            return $activeStage === $stage && (empty($activeStatus) || $activeStatus === 'all');
        }
        if (empty($stage) && !empty($status)) {
            if ($status === 'not_acknowledged') {
                return (empty($activeStage) && in_array($activeStatus, ['not_acknowledged', 'for_acknowledgement'], true))
                    || in_array($activeStage, ['not_acknowledged', 'for_acknowledgement'], true);
            }
            if ($status === 'aging') {
                return empty($activeStage) && in_array($activeStatus, ['aging', 'overdue'], true);
            }
            if ($status === 'completed') {
                return empty($activeStage) && $activeStatus === 'completed';
            }
            if ($status === 'all') {
                return empty($activeStage) && ($activeStatus === 'all' || (request()->has('status') && $activeStatus === ''));
            }
            return empty($activeStage) && $activeStatus === $status;
        }
        return empty($activeStage) && empty($activeStatus);
    };
    $hasAging = config('features.aging_requests', false) || !empty($agingRequestsEnabled);
?>

<div class="stats-grid dashboard-stats {{ $hasAging ? 'has-aging' : '' }} {{ $isFilterPage ? 'is-quick-filter-enabled' : '' }}" id="dashboard-stats-grid">
    <?php $ackActive = $isFilterPage && $isItemActive('', 'not_acknowledged'); ?>
    <a href="{{ $getUrl('', 'not_acknowledged') }}"
       class="stat-card stat-orange {{ $ackActive ? 'is-active' : '' }}"
       title="{{ $isFilterPage ? ($ackActive ? 'Click to clear filter' : 'Quick filter: For Acknowledgement') : 'View requests for acknowledgement' }}"
       @if($isFilterPage)
           data-report-quick-filter
           data-quick-stage=""
           data-quick-status="not_acknowledged"
           data-is-active="{{ $ackActive ? '1' : '0' }}"
       @endif>
        @if($ackActive)
            <span class="stat-card-badge stat-card-badge-active">Active</span>
        @endif
        <div class="stat-label"><span class="stat-icon">&#9679;</span>For Acknowledgement</div>
        <strong>{{ $counts['not_acknowledged'] ?? 0 }}</strong>
    </a>

    <?php
        $inspCardActive = $isFilterPage && $isItemActive('inspection', '');
        $inspPendingActive = $isFilterPage && $isItemActive('inspection', 'pending');
        $inspOngoingActive = $isFilterPage && $isItemActive('inspection', 'on_going');
        $inspCompletedActive = $isFilterPage && $isItemActive('inspection', 'completed');
        $inspHasActiveSub = $inspPendingActive || $inspOngoingActive || $inspCompletedActive;
    ?>
    <div @class(['stat-card', 'stat-yellow', 'stat-stage-card', 'is-active' => $inspCardActive, 'has-active-substat' => $inspHasActiveSub])>
        @if($inspCardActive || $inspHasActiveSub)
            <span class="stat-card-badge stat-card-badge-active">Active</span>
        @endif
        <a href="{{ $getUrl('inspection', '') }}"
           class="stat-stage-main-link"
           title="{{ $isFilterPage ? ($inspCardActive ? 'Click to clear filter' : 'Quick filter: All Inspection') : 'View all Inspection progress' }}"
           aria-label="View all Inspection progress"
           @if($isFilterPage)
               data-report-quick-filter
               data-quick-stage="inspection"
               data-quick-status=""
               data-is-active="{{ $inspCardActive ? '1' : '0' }}"
           @endif></a>
        <div class="stat-label"><span class="stat-icon">&#9719;</span>Inspection</div>
        <div class="stat-stage-breakdown">
            <a href="{{ $getUrl('inspection', 'pending') }}"
               class="stage-substat-link substat-pending {{ $inspPendingActive ? 'is-active' : '' }}"
               title="{{ $isFilterPage ? ($inspPendingActive ? 'Click to clear filter' : 'Quick filter: Inspection Pending') : 'View Inspection: Pending' }}"
               @if($isFilterPage)
                   data-report-quick-filter
                   data-quick-stage="inspection"
                   data-quick-status="pending"
                   data-is-active="{{ $inspPendingActive ? '1' : '0' }}"
               @endif>
                <span class="substat-name"><span class="substat-dot"></span>Pending</span>
                <strong class="substat-val">{{ $counts['inspection_pending'] ?? 0 }}@if($inspPendingActive)<span class="substat-active-check" aria-hidden="true">&#10003;</span>@endif</strong>
            </a>
            <a href="{{ $getUrl('inspection', 'on_going') }}"
               class="stage-substat-link substat-ongoing {{ $inspOngoingActive ? 'is-active' : '' }}"
               title="{{ $isFilterPage ? ($inspOngoingActive ? 'Click to clear filter' : 'Quick filter: Inspection On-going') : 'View Inspection: On-going' }}"
               @if($isFilterPage)
                   data-report-quick-filter
                   data-quick-stage="inspection"
                   data-quick-status="on_going"
                   data-is-active="{{ $inspOngoingActive ? '1' : '0' }}"
               @endif>
                <span class="substat-name"><span class="substat-dot"></span>On-going</span>
                <strong class="substat-val">{{ $counts['inspection_ongoing'] ?? 0 }}@if($inspOngoingActive)<span class="substat-active-check" aria-hidden="true">&#10003;</span>@endif</strong>
            </a>
            <a href="{{ $getUrl('inspection', 'completed') }}"
               class="stage-substat-link substat-completed {{ $inspCompletedActive ? 'is-active' : '' }}"
               title="{{ $isFilterPage ? ($inspCompletedActive ? 'Click to clear filter' : 'Quick filter: Inspection Completed') : 'View Inspection: Completed' }}"
               @if($isFilterPage)
                   data-report-quick-filter
                   data-quick-stage="inspection"
                   data-quick-status="completed"
                   data-is-active="{{ $inspCompletedActive ? '1' : '0' }}"
               @endif>
                <span class="substat-name"><span class="substat-dot"></span>Completed</span>
                <strong class="substat-val">{{ $counts['inspection_completed'] ?? 0 }}@if($inspCompletedActive)<span class="substat-active-check" aria-hidden="true">&#10003;</span>@endif</strong>
            </a>
        </div>
    </div>

    <?php
        $woCardActive = $isFilterPage && $isItemActive('work_order', '');
        $woPendingActive = $isFilterPage && $isItemActive('work_order', 'pending');
        $woOngoingActive = $isFilterPage && $isItemActive('work_order', 'on_going');
        $woCompletedActive = $isFilterPage && $isItemActive('work_order', 'completed');
        $woHasActiveSub = $woPendingActive || $woOngoingActive || $woCompletedActive;
    ?>
    <div @class(['stat-card', 'stat-blue', 'stat-stage-card', 'is-active' => $woCardActive, 'has-active-substat' => $woHasActiveSub])>
        @if($woCardActive || $woHasActiveSub)
            <span class="stat-card-badge stat-card-badge-active">Active</span>
        @endif
        <a href="{{ $getUrl('work_order', '') }}"
           class="stat-stage-main-link"
           title="{{ $isFilterPage ? ($woCardActive ? 'Click to clear filter' : 'Quick filter: All Work Order') : 'View all Work Order progress' }}"
           aria-label="View all Work Order progress"
           @if($isFilterPage)
               data-report-quick-filter
               data-quick-stage="work_order"
               data-quick-status=""
               data-is-active="{{ $woCardActive ? '1' : '0' }}"
           @endif></a>
        <div class="stat-label"><span class="stat-icon">&#9635;</span>Work Order</div>
        <div class="stat-stage-breakdown">
            <a href="{{ $getUrl('work_order', 'pending') }}"
               class="stage-substat-link substat-pending {{ $woPendingActive ? 'is-active' : '' }}"
               title="{{ $isFilterPage ? ($woPendingActive ? 'Click to clear filter' : 'Quick filter: Work Order Pending') : 'View Work Order: Pending' }}"
               @if($isFilterPage)
                   data-report-quick-filter
                   data-quick-stage="work_order"
                   data-quick-status="pending"
                   data-is-active="{{ $woPendingActive ? '1' : '0' }}"
               @endif>
                <span class="substat-name"><span class="substat-dot"></span>Pending</span>
                <strong class="substat-val">{{ $counts['work_order_pending'] ?? 0 }}@if($woPendingActive)<span class="substat-active-check" aria-hidden="true">&#10003;</span>@endif</strong>
            </a>
            <a href="{{ $getUrl('work_order', 'on_going') }}"
               class="stage-substat-link substat-ongoing {{ $woOngoingActive ? 'is-active' : '' }}"
               title="{{ $isFilterPage ? ($woOngoingActive ? 'Click to clear filter' : 'Quick filter: Work Order On-going') : 'View Work Order: On-going' }}"
               @if($isFilterPage)
                   data-report-quick-filter
                   data-quick-stage="work_order"
                   data-quick-status="on_going"
                   data-is-active="{{ $woOngoingActive ? '1' : '0' }}"
               @endif>
                <span class="substat-name"><span class="substat-dot"></span>On-going</span>
                <strong class="substat-val">{{ $counts['work_order_ongoing'] ?? 0 }}@if($woOngoingActive)<span class="substat-active-check" aria-hidden="true">&#10003;</span>@endif</strong>
            </a>
            <a href="{{ $getUrl('work_order', 'completed') }}"
               class="stage-substat-link substat-completed {{ $woCompletedActive ? 'is-active' : '' }}"
               title="{{ $isFilterPage ? ($woCompletedActive ? 'Click to clear filter' : 'Quick filter: Work Order Completed') : 'View Work Order: Completed' }}"
               @if($isFilterPage)
                   data-report-quick-filter
                   data-quick-stage="work_order"
                   data-quick-status="completed"
                   data-is-active="{{ $woCompletedActive ? '1' : '0' }}"
               @endif>
                <span class="substat-name"><span class="substat-dot"></span>Completed</span>
                <strong class="substat-val">{{ $counts['work_order_completed'] ?? 0 }}@if($woCompletedActive)<span class="substat-active-check" aria-hidden="true">&#10003;</span>@endif</strong>
            </a>
        </div>
    </div>

    <?php
        $srCardActive = $isFilterPage && $isItemActive('service_report', '');
        $srPendingActive = $isFilterPage && $isItemActive('service_report', 'pending');
        $srOngoingActive = $isFilterPage && $isItemActive('service_report', 'on_going');
        $srCompletedActive = $isFilterPage && $isItemActive('service_report', 'completed');
        $srHasActiveSub = $srPendingActive || $srOngoingActive || $srCompletedActive;
    ?>
    <div @class(['stat-card', 'stat-green', 'stat-stage-card', 'is-active' => $srCardActive, 'has-active-substat' => $srHasActiveSub])>
        @if($srCardActive || $srHasActiveSub)
            <span class="stat-card-badge stat-card-badge-active">Active</span>
        @endif
        <a href="{{ $getUrl('service_report', '') }}"
           class="stat-stage-main-link"
           title="{{ $isFilterPage ? ($srCardActive ? 'Click to clear filter' : 'Quick filter: All Service Report') : 'View all Service Report progress' }}"
           aria-label="View all Service Report progress"
           @if($isFilterPage)
               data-report-quick-filter
               data-quick-stage="service_report"
               data-quick-status=""
               data-is-active="{{ $srCardActive ? '1' : '0' }}"
           @endif></a>
        <div class="stat-label"><span class="stat-icon">&#10003;</span>Service Report</div>
        <div class="stat-stage-breakdown">
            <a href="{{ $getUrl('service_report', 'pending') }}"
               class="stage-substat-link substat-pending {{ $srPendingActive ? 'is-active' : '' }}"
               title="{{ $isFilterPage ? ($srPendingActive ? 'Click to clear filter' : 'Quick filter: Service Report Pending') : 'View Service Report: Pending' }}"
               @if($isFilterPage)
                   data-report-quick-filter
                   data-quick-stage="service_report"
                   data-quick-status="pending"
                   data-is-active="{{ $srPendingActive ? '1' : '0' }}"
               @endif>
                <span class="substat-name"><span class="substat-dot"></span>Pending</span>
                <strong class="substat-val">{{ $counts['service_report_pending'] ?? 0 }}@if($srPendingActive)<span class="substat-active-check" aria-hidden="true">&#10003;</span>@endif</strong>
            </a>
            <a href="{{ $getUrl('service_report', 'on_going') }}"
               class="stage-substat-link substat-ongoing {{ $srOngoingActive ? 'is-active' : '' }}"
               title="{{ $isFilterPage ? ($srOngoingActive ? 'Click to clear filter' : 'Quick filter: Service Report On-going') : 'View Service Report: On-going' }}"
               @if($isFilterPage)
                   data-report-quick-filter
                   data-quick-stage="service_report"
                   data-quick-status="on_going"
                   data-is-active="{{ $srOngoingActive ? '1' : '0' }}"
               @endif>
                <span class="substat-name"><span class="substat-dot"></span>On-going</span>
                <strong class="substat-val">{{ $counts['service_report_ongoing'] ?? 0 }}@if($srOngoingActive)<span class="substat-active-check" aria-hidden="true">&#10003;</span>@endif</strong>
            </a>
            <a href="{{ $getUrl('service_report', 'completed') }}"
               class="stage-substat-link substat-completed {{ $srCompletedActive ? 'is-active' : '' }}"
               title="{{ $isFilterPage ? ($srCompletedActive ? 'Click to clear filter' : 'Quick filter: Service Report Completed') : 'View Service Report: Completed' }}"
               @if($isFilterPage)
                   data-report-quick-filter
                   data-quick-stage="service_report"
                   data-quick-status="completed"
                   data-is-active="{{ $srCompletedActive ? '1' : '0' }}"
               @endif>
                <span class="substat-name"><span class="substat-dot"></span>Completed</span>
                <strong class="substat-val">{{ $counts['service_report_completed'] ?? 0 }}@if($srCompletedActive)<span class="substat-active-check" aria-hidden="true">&#10003;</span>@endif</strong>
            </a>
        </div>
    </div>

    @if(config('features.aging_requests', false) || !empty($agingRequestsEnabled))
        <?php $agingActive = $isFilterPage && $isItemActive('', 'aging'); ?>
        <a href="{{ $getUrl('', 'aging') }}"
           class="stat-card stat-red {{ $agingActive ? 'is-active' : '' }}"
           title="{{ $isFilterPage ? ($agingActive ? 'Click to clear filter' : 'Quick filter: Aging Request') : 'View aging requests' }}"
           @if($isFilterPage)
               data-report-quick-filter
               data-quick-stage=""
               data-quick-status="aging"
               data-is-active="{{ $agingActive ? '1' : '0' }}"
           @endif>
            @if($agingActive)
                <span class="stat-card-badge stat-card-badge-active">Active</span>
            @endif
            <div class="stat-label"><span class="stat-icon">&#9888;</span>AGING REQUEST</div>
            <strong>{{ $counts['aging'] ?? 0 }}</strong>
        </a>
    @endif

    <div class="stat-column-dual">
        <?php $totalCardActive = $isFilterPage && $isItemActive('', 'all'); ?>
        <a href="{{ $getUrl('', 'all') }}"
           @class(['stat-card', 'stat-blue', 'stat-card-dual', 'is-active' => $totalCardActive])
           title="{{ $isFilterPage ? ($totalCardActive ? 'Click to clear filter' : 'Quick filter: Show All Requests') : 'View all requests' }}"
           aria-label="View all requests"
           @if($isFilterPage)
               data-report-quick-filter
               data-quick-stage=""
               data-quick-status="all"
               data-is-active="{{ $totalCardActive ? '1' : '0' }}"
           @endif>
            @if($totalCardActive)
                <span class="stat-card-badge stat-card-badge-active">Active</span>
            @endif
            <div class="stat-label"><span class="stat-icon">&#9635;</span>Total Requests</div>
            <strong>{{ $counts['total'] ?? 0 }}</strong>
        </a>

        <?php $completedCardActive = $isFilterPage && $isItemActive('', 'completed'); ?>
        <a href="{{ $getUrl('', 'completed') }}"
           @class(['stat-card', 'stat-green', 'stat-card-dual', 'is-active' => $completedCardActive])
           title="{{ $isFilterPage ? ($completedCardActive ? 'Click to clear filter' : 'Quick filter: Completed') : 'View completed requests' }}"
           aria-label="View completed requests"
           @if($isFilterPage)
               data-report-quick-filter
               data-quick-stage=""
               data-quick-status="completed"
               data-is-active="{{ $completedCardActive ? '1' : '0' }}"
           @endif>
            @if($completedCardActive)
                <span class="stat-card-badge stat-card-badge-active">Active</span>
            @endif
            <div class="stat-label"><span class="stat-icon">&#10003;</span>Completed</div>
            <strong>{{ $counts['completed'] ?? 0 }}</strong>
        </a>
    </div>
</div>
