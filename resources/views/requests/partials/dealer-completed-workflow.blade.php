

<section class="panel monitoring-note wide-note">
    <strong>Completed Request Record</strong>
</section>

<section class="panel workflow-card">
    <div class="workflow-card-head"><span class="workflow-title-icon">&#10003;</span><h2>Inspection Details</h2></div>
    <div class="inspection-readonly-grid">
        <div class="workflow-readonly"><span>Started</span><strong>{{ $propertyRequest->inspection_date?->format('M d, Y') ?? 'Not recorded' }}{{ $propertyRequest->inspection_start_time_label ? ' at '.$propertyRequest->inspection_start_time_label : '' }}</strong></div>
        <div class="workflow-readonly"><span>Finished</span><strong>{{ $propertyRequest->inspection_completed_at?->format('M d, Y h:i A') ?? 'Not recorded' }}</strong></div>
        <div class="workflow-readonly"><span>Representatives</span><strong>{{ $representativesList->implode(', ') ?: 'None recorded' }}</strong></div>
        <div class="workflow-readonly"><span>Assigned Dial-A</span><strong>{{ $propertyRequest->assignedSupport?->name ?? 'Not recorded' }}</strong></div>
    </div>
    <div class="workflow-file-gallery">
        @include('requests.partials.files', ['files' => $propertyRequest->inspectionFiles, 'gallery' => true, 'emptyMessage' => 'No inspection attachments were retained for this request.'])
    </div>
</section>

<section class="panel workflow-card">
    <div class="workflow-card-head"><span class="workflow-title-icon">&#10003;</span><h2>Work Order Details</h2></div>
    <div class="inspection-readonly-grid">
        <div class="workflow-readonly"><span>Started</span><strong>{{ $propertyRequest->work_order_start_date?->format('M d, Y') ?? 'Not recorded' }}{{ $propertyRequest->work_order_start_time_label ? ' at '.$propertyRequest->work_order_start_time_label : '' }}</strong></div>
        <div class="workflow-readonly"><span>Finished</span><strong>{{ $propertyRequest->work_order_completed_at?->format('M d, Y h:i A') ?? 'Not recorded' }}</strong></div>
        <div class="workflow-readonly"><span>Representatives</span><strong>{{ $workOrderRepresentativesList->implode(', ') ?: 'None recorded' }}</strong></div>
    </div>
    <div class="workflow-file-gallery">
        @include('requests.partials.files', ['files' => $propertyRequest->workOrderFiles, 'gallery' => true, 'emptyMessage' => 'No Work Order attachments were retained for this request.'])
    </div>
</section>

<section class="panel workflow-card">
    <div class="workflow-card-head"><span class="workflow-title-icon">&#10003;</span><h2>Service Report Details</h2></div>
    <div class="inspection-readonly-grid">
        <div class="workflow-readonly"><span>Finished</span><strong>{{ $propertyRequest->service_report_completed_at?->format('M d, Y h:i A') ?? 'Not recorded' }}</strong></div>
    </div>
    <div class="workflow-file-gallery">
        @include('requests.partials.files', ['files' => $propertyRequest->serviceReportFiles, 'gallery' => true, 'emptyMessage' => 'No Service Report attachments were retained for this request.'])
    </div>
</section>

<section class="panel workflow-card">
    <div class="workflow-card-head"><span class="workflow-title-icon">&#10003;</span><h2>Request Completion</h2></div>
    <div class="inspection-readonly-grid">
        <div class="workflow-readonly"><span>Finished</span><strong>{{ $propertyRequest->completed_at?->format('M d, Y h:i A') ?? 'Not recorded' }}</strong></div>
    </div>
</section>
