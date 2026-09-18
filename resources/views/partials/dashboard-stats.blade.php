<div class="stats-grid dashboard-stats">
    <a href="{{ route('requests.index', ['status' => 'not_acknowledged']) }}" class="stat-card stat-orange" title="View requests for acknowledgement">
        <div class="stat-label"><span class="stat-icon">&#9679;</span>For Acknowledgement</div>
        <strong>{{ $counts['not_acknowledged'] ?? 0 }}</strong>
    </a>
    <div class="stat-card stat-yellow stat-stage-card">
        <a href="{{ route('requests.index', ['stage' => 'inspection']) }}" class="stat-stage-main-link" title="View all Inspection progress" aria-label="View all Inspection progress"></a>
        <div class="stat-label"><span class="stat-icon">&#9719;</span>Inspection</div>
        <div class="stat-stage-breakdown">
            <a href="{{ route('requests.index', ['stage' => 'inspection', 'status' => 'pending']) }}" class="stage-substat-link substat-pending" title="View Inspection: Pending">
                <span class="substat-name"><span class="substat-dot"></span>Pending</span>
                <strong class="substat-val">{{ $counts['inspection_pending'] ?? 0 }}</strong>
            </a>
            <a href="{{ route('requests.index', ['stage' => 'inspection', 'status' => 'on_going']) }}" class="stage-substat-link substat-ongoing" title="View Inspection: On-going">
                <span class="substat-name"><span class="substat-dot"></span>On-going</span>
                <strong class="substat-val">{{ $counts['inspection_ongoing'] ?? 0 }}</strong>
            </a>
            <a href="{{ route('requests.index', ['stage' => 'inspection', 'status' => 'completed']) }}" class="stage-substat-link substat-completed" title="View Inspection: Completed">
                <span class="substat-name"><span class="substat-dot"></span>Completed</span>
                <strong class="substat-val">{{ $counts['inspection_completed'] ?? 0 }}</strong>
            </a>
        </div>
    </div>
    <div class="stat-card stat-blue stat-stage-card">
        <a href="{{ route('requests.index', ['stage' => 'work_order']) }}" class="stat-stage-main-link" title="View all Work Order progress" aria-label="View all Work Order progress"></a>
        <div class="stat-label"><span class="stat-icon">&#9635;</span>Work Order</div>
        <div class="stat-stage-breakdown">
            <a href="{{ route('requests.index', ['stage' => 'work_order', 'status' => 'pending']) }}" class="stage-substat-link substat-pending" title="View Work Order: Pending">
                <span class="substat-name"><span class="substat-dot"></span>Pending</span>
                <strong class="substat-val">{{ $counts['work_order_pending'] ?? 0 }}</strong>
            </a>
            <a href="{{ route('requests.index', ['stage' => 'work_order', 'status' => 'on_going']) }}" class="stage-substat-link substat-ongoing" title="View Work Order: On-going">
                <span class="substat-name"><span class="substat-dot"></span>On-going</span>
                <strong class="substat-val">{{ $counts['work_order_ongoing'] ?? 0 }}</strong>
            </a>
            <a href="{{ route('requests.index', ['stage' => 'work_order', 'status' => 'completed']) }}" class="stage-substat-link substat-completed" title="View Work Order: Completed">
                <span class="substat-name"><span class="substat-dot"></span>Completed</span>
                <strong class="substat-val">{{ $counts['work_order_completed'] ?? 0 }}</strong>
            </a>
        </div>
    </div>
    <div class="stat-card stat-green stat-stage-card">
        <a href="{{ route('requests.index', ['stage' => 'service_report']) }}" class="stat-stage-main-link" title="View all Service Report progress" aria-label="View all Service Report progress"></a>
        <div class="stat-label"><span class="stat-icon">&#10003;</span>Service Report</div>
        <div class="stat-stage-breakdown">
            <a href="{{ route('requests.index', ['stage' => 'service_report', 'status' => 'pending']) }}" class="stage-substat-link substat-pending" title="View Service Report: Pending">
                <span class="substat-name"><span class="substat-dot"></span>Pending</span>
                <strong class="substat-val">{{ $counts['service_report_pending'] ?? 0 }}</strong>
            </a>
            <a href="{{ route('requests.index', ['stage' => 'service_report', 'status' => 'on_going']) }}" class="stage-substat-link substat-ongoing" title="View Service Report: On-going">
                <span class="substat-name"><span class="substat-dot"></span>On-going</span>
                <strong class="substat-val">{{ $counts['service_report_ongoing'] ?? 0 }}</strong>
            </a>
            <a href="{{ route('requests.index', ['stage' => 'service_report', 'status' => 'completed']) }}" class="stage-substat-link substat-completed" title="View Service Report: Completed">
                <span class="substat-name"><span class="substat-dot"></span>Completed</span>
                <strong class="substat-val">{{ $counts['service_report_completed'] ?? 0 }}</strong>
            </a>
        </div>
    </div>
    @if(config('features.aging_requests', false) || !empty($agingRequestsEnabled))
        <a href="{{ route('requests.index', ['status' => 'aging']) }}" class="stat-card stat-red" title="View aging requests">
            <div class="stat-label"><span class="stat-icon">&#9888;</span>AGING REQUEST</div>
            <strong>{{ $counts['aging'] ?? 0 }}</strong>
        </a>
    @endif
    <a href="{{ route('requests.index') }}" class="stat-card stat-blue" title="View all requests">
        <div class="stat-label"><span class="stat-icon">&#9635;</span>Total Requests</div>
        <strong>{{ $counts['total'] ?? 0 }}</strong>
    </a>
    <a href="{{ route('requests.index', ['status' => 'completed']) }}" class="stat-card stat-green" title="View completed requests" hidden aria-hidden="true">
        <div class="stat-label"><span class="stat-icon">&#10003;</span>Completed</div>
        <strong>{{ $counts['completed'] ?? 0 }}</strong>
    </a>
</div>
