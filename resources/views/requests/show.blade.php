@extends('layouts.app')

@section('title', $propertyRequest->reference_no)

@section('content')
@php
    $viewer = auth()->user();
    $inspectionDone = (bool) $propertyRequest->inspection_completed_at || $propertyRequest->status === 'completed';
    $workOrderDone = (bool) $propertyRequest->work_order_completed_at || $propertyRequest->status === 'completed';
    $serviceReportDone = (bool) $propertyRequest->service_report_completed_at || $propertyRequest->status === 'completed';
    $isFullyCompleted = $propertyRequest->status === 'completed';
    $hasServiceReportFiles = $propertyRequest->serviceReportFiles->isNotEmpty();
    $representativesList = collect([$propertyRequest->representative_1, $propertyRequest->representative_2, $propertyRequest->representative_3])->filter();
    $inspectionRepresentativeInputs = collect(old('inspection_representatives', $representativesList->all()))->filter(fn ($value) => trim((string) $value) !== '')->values();
    $workOrderRepresentativesList = collect($propertyRequest->work_order_representatives ?? [])->filter();
    $workOrderRepresentativeInputs = collect(old('work_order_representatives', $workOrderRepresentativesList->all()))->filter(fn ($value) => trim((string) $value) !== '')->values();
    $inspectionTimingOpen = $errors->hasAny(['inspection_date', 'inspection_start_time', 'inspection_end_time', 'inspection_representatives', 'inspection_file', 'inspection_files']);
    $inspectionDateSet = (bool) $propertyRequest->inspection_date;
    $inspectionStartTimeSet = (bool) $propertyRequest->inspection_start_time;
    $workOrderStarted = (bool) $propertyRequest->work_order_start_date;

    if ($inspectionRepresentativeInputs->isEmpty()) {
        $inspectionRepresentativeInputs = collect([$viewer->name]);
    }

    if ($workOrderRepresentativeInputs->isEmpty()) {
        $workOrderRepresentativeInputs = collect([$viewer->name]);
    }
@endphp

<div class="page-heading heading-with-action request-detail-heading">
    <div>
        <p class="eyebrow">REQUEST DETAILS</p>
        <h1>{{ $propertyRequest->reference_no }}</h1>
    </div>
</div>

<div class="request-workflow-stack">
    <section class="panel workflow-card">
        <div class="workflow-card-head"><span class="workflow-title-icon">▣</span><h2>Request Information</h2></div>
        <div class="request-information-grid">
            <div class="request-information-column">
                <div class="request-information-row"><span>Request Number</span><strong>{{ $propertyRequest->reference_no }}</strong></div>
                <div class="request-information-row"><span>Request Type</span><strong>{{ $propertyRequest->request_type }}</strong></div>
                <div class="request-information-row"><span>Dealer</span><strong>{{ $propertyRequest->display_dealer_name }}</strong></div>
                <div class="request-information-row"><span>Branch</span><strong>{{ $propertyRequest->display_branch }}</strong></div>
                <div class="request-information-row">
                    <span>Priority</span>
                    <strong>
                        <span class="badge priority-{{ $propertyRequest->priority }}">{{ ucfirst($propertyRequest->priority) }}</span>
                        @if($viewer->isManager())
                            <button type="button" class="button button-light button-xs" data-open-priority-modal
                                    data-reference="{{ $propertyRequest->reference_no }}"
                                    data-priority="{{ $propertyRequest->priority }}"
                                    data-remarks="{{ $propertyRequest->priority_remarks ?? '' }}"
                                    data-url="{{ route('requests.priority', $propertyRequest) }}"
                                    style="margin-left: 8px; font-size: 11px; padding: 2px 8px; min-height: 24px; vertical-align: middle;"
                                    title="Edit Priority Status">
                                Edit
                            </button>
                        @endif
                    </strong>
                </div>
                @if($propertyRequest->priority_remarks)
                    <div class="request-information-row">
                        <span>Priority Remarks</span>
                        <strong style="font-weight: normal; color: #334155;">{{ $propertyRequest->priority_remarks }}</strong>
                    </div>
                @endif
            </div>
            <div class="request-information-column">
                <div class="request-information-row"><span>Submitted By</span><strong>{{ $propertyRequest->submitter_name }}</strong></div>
                <div class="request-information-row"><span>Designation</span><strong>{{ $propertyRequest->designation }}</strong></div>
                <div class="request-information-row"><span>Submitted Date</span><strong>{{ $propertyRequest->created_at->format('M d, Y h:i A') }}</strong></div>
                <div class="request-information-row"><span>Area</span><strong>{{ $propertyRequest->area }}</strong></div>
                <div class="request-information-row"><span>Status</span><strong><span class="badge status-{{ $propertyRequest->status }}">{{ $propertyRequest->status_label }}</span></strong></div>
                <div class="request-information-row"><span>Completion Date &amp; Time</span><strong>{{ $propertyRequest->completed_at?->format('M d, Y h:i A') ?? 'Not completed' }}</strong></div>
            </div>
        </div>
    </section>

    <section class="panel workflow-card">
        <div class="workflow-card-head"><span class="workflow-title-icon">≡</span><h2>Details of Request</h2></div>
        <div class="request-description-box">{{ $propertyRequest->description }}</div>
    </section>

    <section class="panel workflow-card">
        <div class="workflow-card-head"><span class="workflow-title-icon">↗</span><h2>Attached Files &amp; Documents</h2><span class="workflow-count">{{ $propertyRequest->requestFiles->count() }}</span></div>
        <div class="workflow-file-gallery">
            @include('requests.partials.files', ['files' => $propertyRequest->requestFiles, 'gallery' => true, 'emptyMessage' => 'No files attached to this request.'])
        </div>
    </section>

    <section class="panel workflow-card activity-overview-card">
        <div class="workflow-card-head"><span class="workflow-title-icon">◷</span><h2>Activity Progress</h2><span class="workflow-help-inline">Workbook workflow</span></div>
        <div class="activity-overview-body">
            @include('requests.partials.activity-progress', ['requestItem' => $propertyRequest])
        </div>
    </section>

    

    @if($viewer->isManager())
        <section class="panel monitoring-note wide-note">
            <strong>PM Manager Workflow Controls</strong>
            <p>PM Managers monitor request activities, can update request priority status, and exclusively publish and print completed request operations reports. Only Dial-A conducts inspections and uploads workflow documents.</p>
        </section>

        @if($inspectionDone)
            <section class="panel workflow-card">
                <div class="workflow-card-head"><span class="workflow-title-icon">✓</span><h2>Inspection Details</h2></div>
                <div class="inspection-readonly-grid">
                    <div class="workflow-readonly"><span>Start Date</span><strong>{{ $propertyRequest->inspection_date?->format('F d, Y') ?? '—' }}{{ $propertyRequest->inspection_start_time_label ? ' at ' . $propertyRequest->inspection_start_time_label : '' }}</strong></div>
                    <div class="workflow-readonly"><span>End Date</span><strong>{{ ($propertyRequest->inspection_end_date ?? $propertyRequest->inspection_date)?->format('F d, Y') ?? '—' }}{{ $propertyRequest->inspection_end_time_label ? ' at ' . $propertyRequest->inspection_end_time_label : '' }}</strong></div>
                    <div class="workflow-readonly"><span>Representatives</span><strong>{{ $representativesList->implode(', ') ?: '—' }}</strong></div>
                </div>
                <div class="workflow-file-gallery">
                    @include('requests.partials.files', ['files' => $propertyRequest->inspectionFiles, 'gallery' => true, 'emptyMessage' => 'No completed Inspection Request Template is available.'])
                </div>
            </section>
        @endif

        @if($propertyRequest->workOrderFiles->isNotEmpty() || $propertyRequest->serviceReportFiles->isNotEmpty())
            <section class="panel workflow-card">
                <div class="workflow-card-head"><span class="workflow-title-icon">▤</span><h2>Dial-A Images &amp; Documents</h2></div>
                <p class="workflow-help" style="margin-bottom: 12px;">Only Dial-A is responsible for uploading Work Order and Service Report attachments.</p>
                <div class="manager-document-group"><strong>Work Order Attachments</strong>
                    @if($workOrderDone)
                        <div class="workflow-detail-summary">
                            <div><span>Start Date</span><strong>{{ $propertyRequest->work_order_start_date?->format('F d, Y') ?? 'Not recorded' }}{{ $propertyRequest->work_order_start_time_label ? ' at ' . $propertyRequest->work_order_start_time_label : '' }}</strong></div>
                            <div><span>End Date</span><strong>{{ $propertyRequest->work_order_end_date?->format('F d, Y') ?? 'Not recorded' }}{{ $propertyRequest->work_order_end_time_label ? ' at ' . $propertyRequest->work_order_end_time_label : '' }}</strong></div>
                            <div><span>Representatives</span><strong>{{ $workOrderRepresentativesList->implode(', ') ?: 'None recorded' }}</strong></div>
                        </div>
                    @endif
                    <div class="workflow-file-gallery">
                        @include('requests.partials.files', ['files' => $propertyRequest->workOrderFiles, 'gallery' => true, 'emptyMessage' => 'Work Order attachments are not available yet.'])
                    </div>
                </div>
                <div class="manager-document-group"><strong>Service Report Attachments</strong>
                    <div class="workflow-file-gallery">
                        @include('requests.partials.files', ['files' => $propertyRequest->serviceReportFiles, 'gallery' => true, 'emptyMessage' => 'Service Report attachments are not available yet.'])
                    </div>
                </div>
            </section>
        @endif

        @if($propertyRequest->status === 'completed')
            <div style="margin-top: 16px;">
                <a class="button button-primary" href="{{ route('reports.print', ['search' => $propertyRequest->reference_no]) }}" target="_blank" rel="noopener">Print / Publish Report (PDF)</a>
            </div>
        @endif
    @elseif($viewer->isDialA() || $viewer->isDialLead() || $viewer->isHandyman())
        @php
            $statusMsg = (string) session('status', '');
            $noticeStage = match (true) {
                str_starts_with($statusMsg, 'Inspection') => 'inspection',
                str_starts_with($statusMsg, 'Work Order') => 'work-order',
                str_starts_with($statusMsg, 'Service Report') => 'service-report',
                str_contains($statusMsg, 'marked as Completed') => 'completed',
                !$inspectionDone => 'inspection',
                !$workOrderDone => 'work-order',
                !$serviceReportDone => 'service-report',
                default => 'completed',
            };
        @endphp

        <div class="processing-heading">
            <h1>WORKFLOW</h1>
        </div>

        {{-- Stage 1: Inspection (Dial-A) --}}
        @if(session('status') && $noticeStage === 'inspection')
            <div class="alert alert-success workflow-stage-alert">{{ session('status') }}</div>
        @endif
        <section id="inspection" class="panel workflow-stage-card">
            <div class="workflow-stage-head">
                <span class="stage-number {{ $inspectionDone ? 'number-complete' : '' }}">{{ $inspectionDone ? '✓' : '1' }}</span>
                <div>
                    <h2>Inspection</h2>
                </div>
                <span class="stage-status {{ $inspectionDone ? 'stage-complete' : ($inspectionDateSet ? 'stage-active' : ($viewer->isDialA() ? 'stage-active' : 'stage-waiting')) }}" data-inspection-stage-status>{{ $inspectionDone ? 'Completed' : ($inspectionDateSet ? 'On-going' : ($viewer->isDialA() ? 'Ready for Inspection' : 'Awaiting Dial-A')) }}</span>
            </div>
            <div class="workflow-stage-body">
                @if($inspectionDone)
                    <div class="inspection-summary-grid">
                        <div><span>Start Date</span><strong>{{ $propertyRequest->inspection_date?->format('F d, Y') ?? 'Not recorded' }}{{ $propertyRequest->inspection_start_time_label ? ' at ' . $propertyRequest->inspection_start_time_label : '' }}</strong></div>
                        <div><span>End Date</span><strong>{{ ($propertyRequest->inspection_end_date ?? $propertyRequest->inspection_date)?->format('F d, Y') ?? 'Not recorded' }}{{ $propertyRequest->inspection_end_time_label ? ' at ' . $propertyRequest->inspection_end_time_label : '' }}</strong></div>
                        <div><span>Representatives</span><strong>{{ $representativesList->implode(', ') ?: 'None recorded' }}</strong></div>
                    </div>
                    <div class="workflow-file-gallery compact-file-gallery">
                        @include('requests.partials.files', ['files' => $propertyRequest->inspectionFiles, 'gallery' => true, 'emptyMessage' => 'No inspection files or completed Inspection Request Template was retained.'])
                    </div>
                @elseif($viewer->isDialA())
                    @if(!$inspectionDateSet)
                        <form class="inspection-conduct-form" method="POST" action="{{ route('requests.inspection.date', $propertyRequest) }}" data-stage-start-form="inspection">
                            @csrf @method('PATCH')
                            @include('requests.partials.stage-timing-progress', [
                                'stage' => 'inspection',
                                'ariaLabel' => 'Set inspection start date',
                                'propertyRequest' => $propertyRequest,
                            ])
                            <div class="inspection-form-grid stage-timing-details">
                                @include('requests.partials.representative-fields', [
                                    'label' => 'Inspection Representatives',
                                    'inputName' => 'inspection_representatives',
                                    'inputId' => 'inspection_representative',
                                    'values' => $inspectionRepresentativeInputs,
                                    'max' => 3,
                                ])
                            </div>
                            <div class="workflow-form-footer">
                                <button class="button button-primary" type="submit">Set Inspection</button>
                            </div>
                        </form>
                    @else
                        <div class="inspection-template-box">
                            <div>
                                <strong>Inspection Request Template</strong>
                                <p>Download the official Inspection Request Template, or upload inspection photos, documents, or your completed workbook below.</p>
                            </div>
                            <a class="button button-light" href="{{ route('requests.inspection.template') }}">Download Template</a>
                        </div>

                        <form id="inspection-complete-form" class="inspection-conduct-form" method="POST" action="{{ route('requests.inspection.complete', $propertyRequest) }}" enctype="multipart/form-data">
                            @csrf @method('PATCH')
                            @include('requests.partials.stage-timing-progress', [
                                'stage' => 'inspection',
                                'ariaLabel' => 'Inspection timing progress',
                                'propertyRequest' => $propertyRequest,
                                'dateLocked' => true,
                            ])
                            @foreach($inspectionRepresentativeInputs as $representative)
                                <input type="hidden" name="inspection_representatives[]" value="{{ $representative }}">
                            @endforeach
                            <div class="inspection-form-grid stage-timing-details">
                                <div class="form-field inspection-upload-field">
                                    <label for="inspection_files">Upload File<em>*</em></label>
                                    <div class="file-drop">
                                        <input id="inspection_files" name="inspection_files[]" type="file" accept=".jpg,.jpeg,.png,.webp,image/*,.pdf,application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,.xlsx,.csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" multiple required data-file-input data-max-files="5">
                                    </div>
                                    <small>Supported formats: Images (JPG, PNG, WEBP), PDF, Word (.doc, .docx), Excel (.xls, .xlsx, .csv). Up to 5, 20 MB each.</small>
                                </div>
                            </div>
                            <div class="workflow-form-footer">
                                <button class="button button-primary" type="submit" data-timing-submit>Inspection Completed</button>
                            </div>
                        </form>
                    @endif
                @else
                    @if($inspectionDateSet)
                        <div class="locked-stage-message" style="color: #155bc0; background: #e5efff;">Inspection was started on {{ $propertyRequest->inspection_date?->format('F d, Y') }}. On-going by Dial-A.</div>
                    @else
                        <div class="locked-stage-message">🔒 The inspection must be conducted and completed by Dial-A before Work Orders can begin.</div>
                    @endif
                @endif
            </div>
        </section>

        {{-- Stage 2: Work Order (Dial-A) --}}
        @if(session('status') && $noticeStage === 'work-order')
            <div class="alert alert-success workflow-stage-alert">{{ session('status') }}</div>
        @endif
        <section id="work-order" class="panel workflow-stage-card {{ !$inspectionDone ? 'stage-locked-card' : '' }}">
            <div class="workflow-stage-head">
                <span class="stage-number {{ $workOrderDone ? 'number-complete' : '' }}">{{ $workOrderDone ? '✓' : '2' }}</span>
                <div>
                    <h2>Work Order</h2>
                </div>
                <span class="stage-status {{ $workOrderDone ? 'stage-complete' : ($workOrderStarted ? 'stage-active' : ($inspectionDone && $viewer->isDialA() ? 'stage-active' : 'stage-waiting')) }}" data-work-order-stage-status>{{ $workOrderDone ? 'Completed' : ($workOrderStarted ? 'On-going' : ($inspectionDone ? ($viewer->isDialA() ? 'Ready for Work Order' : 'Awaiting Dial-A') : 'Not Started')) }}</span>
            </div>
            <div class="workflow-stage-body">
                @if($workOrderDone)
                    <div class="stage-success-message"> Work Order completed by Dial-A. The Service Report section is now available.</div>
                    <div class="workflow-detail-summary">
                        <div><span>Start Date</span><strong>{{ $propertyRequest->work_order_start_date?->format('F d, Y') ?? 'Not recorded' }}{{ $propertyRequest->work_order_start_time_label ? ' at ' . $propertyRequest->work_order_start_time_label : '' }}</strong></div>
                        <div><span>End Date</span><strong>{{ $propertyRequest->work_order_end_date?->format('F d, Y') ?? 'Not recorded' }}{{ $propertyRequest->work_order_end_time_label ? ' at ' . $propertyRequest->work_order_end_time_label : '' }}</strong></div>
                        <div><span>Representatives</span><strong>{{ $workOrderRepresentativesList->implode(', ') ?: 'None recorded' }}</strong></div>
                    </div>
                    <div class="workflow-file-gallery">
                        @include('requests.partials.files', ['files' => $propertyRequest->workOrderFiles, 'gallery' => true, 'emptyMessage' => 'No Work Order attachments were retained for this request.'])
                    </div>
                @elseif($inspectionDone)
                    @if($viewer->isDialA())
                        @if(!$workOrderStarted)
                            <form class="workflow-details-form" method="POST" action="{{ route('requests.work-order.date', $propertyRequest) }}" data-stage-start-form="work_order">
                                @csrf @method('PATCH')
                                @include('requests.partials.stage-timing-progress', [
                                    'stage' => 'work_order',
                                    'ariaLabel' => 'Set Work Order start date',
                                    'propertyRequest' => $propertyRequest,
                                ])
                                <div class="workflow-details-grid stage-timing-details">
                                    @include('requests.partials.representative-fields', [
                                        'label' => 'Work Order Representatives',
                                        'inputName' => 'work_order_representatives',
                                        'inputId' => 'work_order_representative',
                                        'values' => $workOrderRepresentativeInputs,
                                        'max' => 10,
                                    ])
                                </div>
                                <div class="workflow-form-footer">
                                    <button class="button button-primary" type="submit">Set Work Order</button>
                                </div>
                            </form>
                        @else
                            <form class="workflow-details-form" method="POST" action="{{ route('requests.work-order.complete', $propertyRequest) }}" enctype="multipart/form-data" data-work-order-completion-form>
                                @csrf
                                @include('requests.partials.stage-timing-progress', [
                                    'stage' => 'work_order',
                                    'ariaLabel' => 'Work Order date progress',
                                    'propertyRequest' => $propertyRequest,
                                    'dateLocked' => true,
                                ])
                                @foreach($workOrderRepresentativeInputs as $representative)
                                    <input type="hidden" name="work_order_representatives[]" value="{{ $representative }}">
                                @endforeach
                                <div class="form-field workflow-attachment-field">
                                    <label for="work_order_files">Upload Work Order<em>*</em></label>
                                    <div class="file-drop">
                                        <input id="work_order_files" name="work_order_files[]" type="file" accept=".jpg,.jpeg,.png,.webp,image/*,.pdf,application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,.xlsx,.csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" multiple required data-file-input data-max-files="5">
                                    </div>
                                    <small>Supported formats: Images (JPG, PNG, WEBP), PDF, Word (.doc, .docx), Excel (.xls, .xlsx, .csv). Up to 5, 20 MB each.</small>
                                </div>
                                <div class="workflow-form-footer">
                                    <button class="button button-primary" type="submit" data-timing-submit>Done Work Order</button>
                                </div>
                            </form>
                        @endif
                    @else
                        <div class="locked-stage-message"></div>
                    @endif
                @else
                    <div class="locked-stage-message">Complete the inspection to unlock this stage.</div>
                @endif
            </div>
        </section>

        {{-- Stage 3: Service Report (Dial-A) --}}
        @if(session('status') && $noticeStage === 'service-report')
            <div class="alert alert-success workflow-stage-alert">{{ session('status') }}</div>
        @endif
        <section id="service-report" class="panel workflow-stage-card {{ !$workOrderDone ? 'stage-locked-card' : '' }}">
            <div class="workflow-stage-head">
                <span class="stage-number {{ $isFullyCompleted ? 'number-complete' : (($serviceReportDone || $hasServiceReportFiles) ? 'number-active' : '') }}">{{ $isFullyCompleted ? '✓' : '3' }}</span>
                <div>
                    <h2>Service Report</h2>  
                </div>
                <span class="stage-status {{ $isFullyCompleted ? 'stage-complete' : (($serviceReportDone || $hasServiceReportFiles) ? 'stage-active' : (($workOrderDone && ($propertyRequest->status === 'on_going' || $propertyRequest->status === 'in_progress' || !empty($propertyRequest->service_report_date))) ? 'stage-active' : ($workOrderDone ? ($viewer->isDialA() ? 'stage-active' : 'stage-waiting') : 'stage-waiting'))) }}">{{ $isFullyCompleted ? 'Completed' : (($serviceReportDone || $hasServiceReportFiles) ? 'Awaiting Confirmation' : (($workOrderDone && ($propertyRequest->status === 'on_going' || $propertyRequest->status === 'in_progress' || !empty($propertyRequest->service_report_date))) ? 'On-going' : ($workOrderDone ? ($viewer->isDialA() ? 'Ready for Service Report' : 'Awaiting Dial-A') : 'Not Started'))) }}</span>
            </div>
            <div class="workflow-stage-body">
                @if($isFullyCompleted)
                    <div class="stage-success-message">Service Report completed and request closed by Dial-A.</div>
                    <div class="workflow-file-gallery">
                        @include('requests.partials.files', ['files' => $propertyRequest->serviceReportFiles, 'gallery' => true, 'emptyMessage' => 'No Service Report files were retained for this request.'])
                    </div>
                @elseif($workOrderDone)
                    @if($viewer->isDialA())
                        @if(!$hasServiceReportFiles)
                            {{-- Step 1: Uploading of Service Report attachments --}}
                            <form class="workflow-upload-form" method="POST" action="{{ route('requests.service-report.upload', $propertyRequest) }}" enctype="multipart/form-data">
                                @csrf
                                <div class="form-field">
                                    <label for="service_report_files">Upload Service Report<em>*</em></label>
                                    <div class="file-drop">
                                        <input id="service_report_files" name="service_report_files[]" type="file" accept=".jpg,.jpeg,.png,.webp,image/*,.pdf,application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,.xlsx,.csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" multiple required data-file-input data-max-files="5">
                                    </div>
                                    <small>Supported formats: Images (JPG, PNG, WEBP), PDF, Word (.doc, .docx), Excel (.xls, .xlsx, .csv). Up to 5, 20 MB each.</small>
                                </div>
                                <div class="workflow-form-footer">
                                    <button class="button button-primary" type="submit">Upload Service Report</button>
                                </div>
                            </form>
                        @else

                            <div class="workflow-file-gallery" style="margin-bottom: 16px;">
                                @include('requests.partials.files', ['files' => $propertyRequest->serviceReportFiles, 'gallery' => true, 'emptyMessage' => 'No Service Report files were retained for this request.'])
                            </div>

                            <details style="margin-top: 14px; font-size: 0.66rem;">
                                <summary style="cursor: pointer; color: #2563eb; font-weight: 600;">Upload additional attachments</summary>
                                <form class="workflow-upload-form" method="POST" action="{{ route('requests.service-report.upload', $propertyRequest) }}" enctype="multipart/form-data" style="margin-top: 10px; padding: 12px; background: #f8fafc; border-radius: 6px; border: 1px solid #e2e8f0;">
                                    @csrf
                                    <div class="form-field">
                                        <div class="file-drop">
                                            <input name="service_report_files[]" type="file" accept=".jpg,.jpeg,.png,.webp,image/*,.pdf,application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,.xlsx,.csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" multiple required data-file-input data-max-files="5">
                                        </div>
                                    </div>
                                    <div class="workflow-form-footer">
                                        <button class="button button-light" type="submit">Upload Additional Files</button>
                                    </div>
                                </form>
                            </details>

                            <form class="workflow-upload-form" method="POST" action="{{ route('requests.finish', $propertyRequest) }}" onsubmit="return confirmServiceReportDone(event);">
                                @csrf
                                <div class="workflow-form-footer" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
                                    <button class="button button-primary" type="submit">Done Service Report</button>
                                </div>
                            </form>

                        @endif
                    @else
                        @if($hasServiceReportFiles)
                            <div class="stage-success-message">Service Report attachments have been uploaded. Awaiting Dial-A to confirm Done Service Report.</div>
                            <div class="workflow-file-gallery">
                                @include('requests.partials.files', ['files' => $propertyRequest->serviceReportFiles, 'gallery' => true, 'emptyMessage' => 'No Service Report files were retained for this request.'])
                            </div>
                        @else
                            <div class="locked-stage-message"></div>
                        @endif
                    @endif
                @else
                    <div class="locked-stage-message"></div>
                @endif
            </div>
        </section>

        {{-- Stage 4: Request Completed --}}
        @if(session('status') && $noticeStage === 'completed')
            <div class="alert alert-success workflow-stage-alert">{{ session('status') }}</div>
        @endif
        <section id="request-completed" class="panel workflow-stage-card {{ !($serviceReportDone || $hasServiceReportFiles) ? 'stage-locked-card' : '' }}">
            <div class="workflow-stage-head">
                <span class="stage-number {{ $isFullyCompleted ? 'number-complete' : (($serviceReportDone || $hasServiceReportFiles) ? 'number-active' : 'number-muted') }}">{{ $isFullyCompleted ? '✓' : '4' }}</span>
                <div>
                    <h2>Request Completed</h2>
                </div>
                <span class="stage-status {{ $isFullyCompleted ? 'stage-complete' : (($serviceReportDone || $hasServiceReportFiles) ? 'stage-active' : 'stage-waiting') }}">{{ $isFullyCompleted ? 'Completed' : (($serviceReportDone || $hasServiceReportFiles) ? 'Awaiting Confirmation' : 'Not Started') }}</span>
            </div>
        </section>

    @elseif($viewer->isDealer() && $isFullyCompleted)
        @include('requests.partials.dealer-completed-workflow', [
            'propertyRequest' => $propertyRequest,
            'representativesList' => $representativesList,
            'workOrderRepresentativesList' => $workOrderRepresentativesList,
        ])
    @elseif($viewer->isAdmin())
        <section class="panel monitoring-note wide-note">
            <strong>Administrator workflow controls</strong>
            <p>Administrators can monitor request activities and undo completed workflow steps when a correction is required. Only Dial-A can complete reopened steps and upload replacement documents.</p>
        </section>
        @if($inspectionDone)
            <section class="panel workflow-card">
                <div class="workflow-card-head"><span class="workflow-title-icon">✓</span><h2>Inspection Details</h2></div>
                <div class="inspection-readonly-grid">
                    <div class="workflow-readonly"><span>Start Date</span><strong>{{ $propertyRequest->inspection_date?->format('F d, Y') ?? '—' }}{{ $propertyRequest->inspection_start_time_label ? ' at ' . $propertyRequest->inspection_start_time_label : '' }}</strong></div>
                    <div class="workflow-readonly"><span>End Date</span><strong>{{ ($propertyRequest->inspection_end_date ?? $propertyRequest->inspection_date)?->format('F d, Y') ?? '—' }}{{ $propertyRequest->inspection_end_time_label ? ' at ' . $propertyRequest->inspection_end_time_label : '' }}</strong></div>
                    <div class="workflow-readonly"><span>Representatives</span><strong>{{ $representativesList->implode(', ') ?: '—' }}</strong></div>
                </div>
                <div class="workflow-file-gallery">
                    @include('requests.partials.files', ['files' => $propertyRequest->inspectionFiles, 'gallery' => true, 'emptyMessage' => 'No completed Inspection Request Template is available.'])
                </div>
                <div class="workflow-admin-action">
                    @include('requests.partials.undo-workflow-step', [
                        'stage' => 'inspection',
                        'label' => 'Inspection',
                        'cascadeMessage' => 'This will also reopen Work Order, Service Report, and Request Completion.',
                    ])
                </div>
            </section>
        @endif
        @if($workOrderDone || $serviceReportDone || $propertyRequest->workOrderFiles->isNotEmpty() || $propertyRequest->serviceReportFiles->isNotEmpty())
            <section class="panel workflow-card">
                <div class="workflow-card-head"><span class="workflow-title-icon">▤</span><h2>Dial-A Images &amp; Documents</h2></div>
                <div class="manager-document-group"><strong>Work Order Attachments</strong>
                    @if($workOrderDone)
                        <div class="workflow-detail-summary">
                            <div><span>Start Date</span><strong>{{ $propertyRequest->work_order_start_date?->format('F d, Y') ?? 'Not recorded' }}{{ $propertyRequest->work_order_start_time_label ? ' at ' . $propertyRequest->work_order_start_time_label : '' }}</strong></div>
                            <div><span>End Date</span><strong>{{ $propertyRequest->work_order_end_date?->format('F d, Y') ?? 'Not recorded' }}{{ $propertyRequest->work_order_end_time_label ? ' at ' . $propertyRequest->work_order_end_time_label : '' }}</strong></div>
                            <div><span>Representatives</span><strong>{{ $workOrderRepresentativesList->implode(', ') ?: 'None recorded' }}</strong></div>
                        </div>
                    @endif
                    <div class="workflow-file-gallery">
                        @include('requests.partials.files', ['files' => $propertyRequest->workOrderFiles, 'gallery' => true, 'emptyMessage' => 'No Work Order attachments.'])
                    </div>
                    @if($workOrderDone)
                        @include('requests.partials.undo-workflow-step', [
                            'stage' => 'work-order',
                            'label' => 'Work Order',
                            'cascadeMessage' => 'This will also reopen Service Report and Request Completion.',
                        ])
                    @endif
                </div>
                <div class="manager-document-group"><strong>Service Report Attachments</strong>
                    <div class="workflow-file-gallery">
                        @include('requests.partials.files', ['files' => $propertyRequest->serviceReportFiles, 'gallery' => true, 'emptyMessage' => 'No Service Report attachments.'])
                    </div>
                    @if($serviceReportDone)
                        @include('requests.partials.undo-workflow-step', [
                            'stage' => 'service-report',
                            'label' => 'Service Report',
                            'cascadeMessage' => 'This will also reopen Request Completion.',
                        ])
                    @endif
                </div>
            </section>
        @endif
        @if($isFullyCompleted)
            <section class="panel workflow-card">
                <div class="workflow-card-head"><span class="workflow-title-icon">&#10003;</span><h2>Request Completion</h2></div>
                <div class="workflow-admin-action">
                    <div class="stage-success-message">The request was officially completed on {{ $propertyRequest->completed_at?->format('M d, Y h:i A') ?? 'the recorded completion date' }}.</div>
                    @include('requests.partials.undo-workflow-step', [
                        'stage' => 'completion',
                        'label' => 'Completion',
                        'cascadeMessage' => 'This will reopen the final confirmation step.',
                    ])
                </div>
            </section>
        @endif

        <section class="panel admin-delete-section">
            <div class="workflow-card-head"><span class="workflow-title-icon" style="color:#d71938;">✕</span><h2>Delete Request</h2></div>
            <div class="admin-delete-body">
                <p>Permanently remove this request and all its attachments. <strong>This action cannot be undone.</strong></p>
                <form method="POST"
                      action="{{ route('requests.destroy', $propertyRequest) }}"
                      data-admin-password-form
                      data-action-title="Delete Request {{ $propertyRequest->reference_no }}"
                      data-action-description="This will permanently remove the request, all uploaded files, and related audit entries. This action cannot be undone.">
                    @csrf
                    @method('DELETE')
                    <input type="hidden" name="current_password" data-admin-password-field>
                    <button class="button button-danger" type="submit">
                        <span aria-hidden="true">✕</span> Delete This Request
                    </button>
                </form>
            </div>
        </section>

        @include('requests.partials.admin-password-modal')
    @endif
</div>

@if($viewer->isManager())
    @include('requests.partials.edit-priority-modal')
@endif

@if($viewer->isDealer() && request('priority_notification'))
    @php
        $priorityNotice = $viewer->notifications()->find(request('priority_notification'));
    @endphp
    @if($priorityNotice && ($priorityNotice->data['kind'] ?? null) === 'priority_changed')
        @push('scripts')
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (typeof window.openDealerPriorityNoticeModal === 'function') {
                window.openDealerPriorityNoticeModal({
                    referenceNo: @json($priorityNotice->data['reference_no'] ?? $propertyRequest->reference_no),
                    oldPriority: @json($priorityNotice->data['old_priority'] ?? 'regular'),
                    newPriority: @json($priorityNotice->data['new_priority'] ?? $propertyRequest->priority),
                    remarks: @json($priorityNotice->data['remarks'] ?? $propertyRequest->priority_remarks ?? ''),
                    url: @json(route('requests.show', $propertyRequest))
                });
            }
        });
        </script>
        @endpush
    @endif
@endif

@push('scripts')
<script>
function confirmServiceReportDone(e) {
    var confirmed = confirm("Are you sure you want to confirm Done Service Report?\n\nConfirming will officially mark the request as Completed.");
    if (!confirmed) {
        e.preventDefault();
        return false;
    }
    return true;
}

function confirmFinishRequest(e) {
    var confirmed = confirm("Are you sure you want to confirm Done Service Report and complete request {{ $propertyRequest->reference_no }}?\n\nAs Dial-A, confirming will officially close this request.");
    if (!confirmed) {
        e.preventDefault();
        return false;
    }
    return true;
}

function updateMainStatusBadgeToOngoing() {
    var mainBadge = document.querySelector('.request-information-row .badge[class*="status-"]');
    if (mainBadge && !mainBadge.classList.contains('status-completed')) {
        mainBadge.className = 'badge status-on_going';
        mainBadge.textContent = 'On-going';
    }
}

document.querySelectorAll('[data-stage-start-form]').forEach(function (form) {
    form.addEventListener('submit', function () {
        var deviceNow = new Date();
        var pad = function (value) { return String(value).padStart(2, '0'); };
        var minuteTime = pad(deviceNow.getHours()) + ':' + pad(deviceNow.getMinutes());
        var stage = form.getAttribute('data-stage-start-form');
        var timeInput = form.querySelector(stage === 'inspection'
            ? '[name="inspection_start_time"]'
            : '[name="work_order_start_time"]');

        if (timeInput) {
            timeInput.value = stage === 'inspection'
                ? minuteTime
                : minuteTime + ':' + pad(deviceNow.getSeconds());
        }
    });
});

document.querySelectorAll('#inspection-complete-form').forEach(function (form) {
    function applyInspectionDeviceTime() {
        var deviceNow = new Date();
        var pad = function (value) { return String(value).padStart(2, '0'); };
        var deviceDate = deviceNow.getFullYear() + '-' + pad(deviceNow.getMonth() + 1) + '-' + pad(deviceNow.getDate());
        var deviceTime = pad(deviceNow.getHours()) + ':' + pad(deviceNow.getMinutes());
        var endDateInput = form.querySelector('[name="inspection_end_date"]');
        var endTimeInput = form.querySelector('[name="inspection_end_time"]');
        var startDateInput = form.querySelector('[name="inspection_date"]');

        if (endDateInput) endDateInput.value = deviceDate;
        if (endTimeInput) endTimeInput.value = deviceTime;

        return deviceNow;
    }

    applyInspectionDeviceTime();

    var doneBtn = form.querySelector('[data-timing-submit], button[type="submit"]');
    if (doneBtn) {
        doneBtn.addEventListener('click', applyInspectionDeviceTime);
    }
    form.addEventListener('submit', function (event) {
        var deviceNow = applyInspectionDeviceTime();
        var startDateInput = form.querySelector('[name="inspection_date"]');
        var startTimeInput = form.querySelector('[name="inspection_start_time"]');

        if (startDateInput && startTimeInput && startDateInput.value && startTimeInput.value) {
            var startAt = new Date(startDateInput.value + 'T' + startTimeInput.value + ':00');
            if (!Number.isNaN(startAt.getTime()) && deviceNow.getTime() < startAt.getTime() + 60000) {
                event.preventDefault();
                alert('The inspection cannot be completed at the same time or before its saved start date and time. Please do the task first.');
            }
        }
    });
});

document.querySelectorAll('[data-work-order-completion-form]').forEach(function (form) {
    function fetchAndApplyDeviceTime() {
        var deviceNow = new Date();
        var pad = function (value) { return String(value).padStart(2, '0'); };
        var deviceDate = deviceNow.getFullYear() + '-' + pad(deviceNow.getMonth() + 1) + '-' + pad(deviceNow.getDate());
        var deviceTime = pad(deviceNow.getHours()) + ':' + pad(deviceNow.getMinutes()) + ':' + pad(deviceNow.getSeconds());
        var endDateInput = form.querySelector('[name="work_order_end_date"]');
        var endTimeInput = form.querySelector('[name="work_order_end_time"]');
        var startDateInput = form.querySelector('[name="work_order_start_date"]');

        if (endDateInput) endDateInput.value = deviceDate;
        if (endTimeInput) endTimeInput.value = deviceTime;

        var endHint = form.querySelector('[data-work-order-end-hint]');
        if (endHint) {
            endHint.textContent = 'Device time recorded: ' + deviceDate + ' at ' + deviceTime;
        }

        return deviceNow;
    }

    // Initialize with current device timestamp
    fetchAndApplyDeviceTime();

    // Fetch exact device time immediately when clicking 'Done Work Order' button
    var doneBtn = form.querySelector('[data-timing-submit], button[type="submit"]');
    if (doneBtn) {
        doneBtn.addEventListener('click', function () {
            fetchAndApplyDeviceTime();
        });
    }

    // Also ensure device timestamp is refreshed upon form submission
    form.addEventListener('submit', function (event) {
        var deviceNow = fetchAndApplyDeviceTime();
        var startDateInput = form.querySelector('[name="work_order_start_date"]');
        var startTimeInput = form.querySelector('[name="work_order_start_time"]');

        if (startDateInput && startTimeInput && startDateInput.value && startTimeInput.value) {
            var normalizedStartTime = startTimeInput.value.length === 5
                ? startTimeInput.value + ':00'
                : startTimeInput.value;
            var startAt = new Date(startDateInput.value + 'T' + normalizedStartTime);
            if (!Number.isNaN(startAt.getTime()) && deviceNow.getTime() < startAt.getTime() + 60000) {
                event.preventDefault();
                alert('The Work Order cannot be completed at the same time or before its saved start date and time. Please do the task first.');
            }
        }
    });
});

var srFilesInput = document.getElementById('service_report_files');
if (srFilesInput) {
    srFilesInput.addEventListener('change', function () {
        if (srFilesInput.files && srFilesInput.files.length > 0) {
            var csrfMeta = document.querySelector('meta[name="csrf-token"]');
            var token = csrfMeta ? csrfMeta.getAttribute('content') : '{{ csrf_token() }}';
            fetch('{{ route("requests.service-report.start", $propertyRequest) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({})
            }).then(function (res) {
                return res.json();
            }).then(function (data) {
                var srCard = document.getElementById('service-report');
                if (srCard) {
                    var srBadge = srCard.querySelector('.workflow-stage-head .stage-status');
                    if (srBadge && !srBadge.classList.contains('stage-complete')) {
                        srBadge.textContent = 'On-going';
                        srBadge.classList.add('stage-active');
                        srBadge.classList.remove('stage-waiting');
                    }
                }
                updateMainStatusBadgeToOngoing();
            }).catch(function () {});
        }
    });
}

// Admin password modal for undo & delete actions
(function () {
    var overlay = document.getElementById('adminPasswordOverlay');
    if (!overlay) return;

    var titleEl = document.getElementById('adminPasswordTitle');
    var descEl = document.getElementById('adminPasswordDescription');
    var passwordInput = document.getElementById('adminPasswordInput');
    var confirmBtn = document.getElementById('adminPasswordConfirm');
    var cancelBtn = document.getElementById('adminPasswordCancel');
    var pendingForm = null;

    function openModal(form) {
        pendingForm = form;
        titleEl.textContent = form.dataset.actionTitle || 'Confirm Action';
        descEl.textContent = form.dataset.actionDescription || 'Enter your administrator password to proceed.';
        passwordInput.value = '';
        overlay.style.display = 'flex';
        passwordInput.focus();
    }

    function closeModal() {
        overlay.style.display = 'none';
        pendingForm = null;
        passwordInput.value = '';
    }

    function submitWithPassword() {
        if (!pendingForm || !passwordInput.value.trim()) {
            passwordInput.focus();
            return;
        }
        var hiddenField = pendingForm.querySelector('[data-admin-password-field]');
        if (hiddenField) {
            hiddenField.value = passwordInput.value;
        }
        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Verifying…';
        pendingForm.submit();
    }

    document.querySelectorAll('[data-admin-password-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            var hiddenField = form.querySelector('[data-admin-password-field]');
            if (hiddenField && !hiddenField.value) {
                event.preventDefault();
                openModal(form);
            }
        });
    });

    cancelBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', function (event) {
        if (event.target === overlay) closeModal();
    });
    confirmBtn.addEventListener('click', submitWithPassword);
    passwordInput.addEventListener('keydown', function (event) {
        if (event.key === 'Enter') {
            event.preventDefault();
            submitWithPassword();
        }
    });
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape' && overlay.style.display !== 'none') {
            closeModal();
        }
    });

    @if($errors->has('current_password'))
        var errorForm = document.querySelector('[data-admin-password-form]');
        if (errorForm) openModal(errorForm);
    @endif
})();
</script>
@endpush
@endsection
