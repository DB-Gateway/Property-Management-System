<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Completed Requests Operations Report · Gateway PMS</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; color: #172b42; font: 11px Arial, sans-serif; background: #eef2f6; }
        .report-page { width: min(100%, 1400px); min-height: 100vh; margin: 0 auto; padding: 28px; background: #fff; }
        .report-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; padding-bottom: 16px; border-bottom: 3px solid #ef1838; }
        .brand { display: flex; align-items: center; gap: 13px; }
        .brand-text { display: flex; flex-direction: column; justify-content: center; }
        .brand-wordmark {
            height: 22px;
            width: auto;
            max-width: 130px;
            object-fit: contain;
            display: block;
            filter: brightness(0);
            -webkit-filter: brightness(0);
            print-color-adjust: exact;
            -webkit-print-color-adjust: exact;
        }
        .brand span { display: block; margin-top: 3px; color: #607895; font-size: 8.5px; letter-spacing: 1.1px; font-weight: bold; }
        .report-title { text-align: right; }
        .report-title h1 { margin: 0 0 6px; color: #061a2a; font-size: 21px; }
        .report-title p { margin: 3px 0; color: #61748b; }
        .summary { display: grid; grid-template-columns: repeat(5, 1fr); gap: 9px; margin: 17px 0; }
        .summary div { padding: 10px 12px; border: 1px solid #dbe4ee; background: #f7f9fc; }
        .summary span { display: block; color: #687d96; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .summary strong { display: block; margin-top: 5px; font-size: 20px; }
        .filters { margin: 0 0 14px; padding: 9px 11px; color: #4d647e; border: 1px solid #dbe4ee; background: #f8fafc; }
        .filters strong { color: #172b42; }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 8px 6px; color: #fff; background: #102e4c; font-size: 8px; letter-spacing: .3px; text-align: left; text-transform: uppercase; }
        td { padding: 8px 6px; border: 1px solid #dce5ef; vertical-align: top; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        .nowrap { white-space: nowrap; }
        .description { max-width: 220px; white-space: pre-line; color: #435b75; font-size: 10px; margin-top: 4px; }
        .print-attachments-wrap { min-width: 140px; max-width: 210px; }
        .print-file-text-list { list-style: none; margin: 2px 0 0; padding: 0; }
        .print-file-text-item { display: flex; align-items: baseline; flex-wrap: wrap; gap: 4px; margin-bottom: 4px; font-size: 8.5px; line-height: 1.3; }
        .print-file-text-item:last-child { margin-bottom: 0; }
        .print-file-badge { display: inline-block; padding: 1px 4px; border-radius: 3px; font-size: 7px; font-weight: bold; letter-spacing: .3px; background: #e2e8f0; color: #1e293b; text-transform: uppercase; }
        .print-file-badge.badge-img { background: #dbeafe; color: #1e40af; }
        .print-file-name { color: #1e3a5f; font-weight: 600; word-break: break-word; }
        .print-file-size { color: #64748b; font-size: 7.5px; }
        .print-page-tag { display: inline-block; font-size: 7.5px; color: #0f766e; font-weight: 600; }
        .print-none-text { color: #8fa0b5; font-style: italic; font-size: 8.5px; }
        .print-attachment-section { margin-bottom: 6px; }
        .print-attachment-section:last-child { margin-bottom: 0; }
        .print-attachment-heading { font-weight: bold; font-size: 8px; color: #0d2c4b; text-transform: uppercase; letter-spacing: .3px; margin-bottom: 2px; }
        .report-footer { display: flex; justify-content: space-between; gap: 20px; margin-top: 16px; padding-top: 10px; color: #6a7f98; border-top: 1px solid #dce5ef; font-size: 9px; }
        .print-actions { position: sticky; z-index: 2; top: 0; display: flex; justify-content: flex-end; gap: 8px; padding: 10px; background: #061a2a; }
        .print-actions button, .print-actions a { padding: 9px 13px; border: 0; border-radius: 5px; color: #fff; background: #2463ff; font-weight: bold; text-decoration: none; cursor: pointer; }
        .print-actions a { background: #526b85; }
        .badge { display: inline-block; padding: 2px 5px; border-radius: 4px; font-size: 8px; font-weight: bold; text-transform: uppercase; }
        .badge-urgent { background: #fde8eb; color: #d71938; }
        .badge-regular { background: #e8f0fe; color: #1a56db; }
        .badge-done { background: #e6f7ec; color: #0f8541; }

        /* Dedicated Attachment Paper Pages */
        .attachment-single-page {
            width: min(100%, 1400px);
            min-height: 100vh;
            margin: 28px auto 0;
            padding: 28px;
            background: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            box-sizing: border-box;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.06);
            page-break-before: always;
            break-before: page;
            page-break-inside: avoid;
            break-inside: avoid;
        }
        .attachment-main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 20px 0;
            margin: auto 0;
        }
        .attachment-title-card {
            margin-bottom: 18px;
            max-width: 900px;
        }
        .attachment-meta-pill-group {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-bottom: 8px;
            flex-wrap: wrap;
        }
        .attachment-pill {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 4px;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: .4px;
            text-transform: uppercase;
        }
        .pill-request { background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd; }
        .pill-work-order { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .pill-service-report { background: #dcfce7; color: #15803d; border: 1px solid #bbf7d0; }
        .pill-reference { background: #f1f5f9; color: #334155; border: 1px solid #cbd5e1; }
        .pill-dealer { background: #f8fafc; color: #475569; border: 1px solid #e2e8f0; }
        .attachment-main-title {
            margin: 6px 0 8px;
            font-size: 17px;
            font-weight: bold;
            color: #061a2a;
            letter-spacing: .2px;
            word-break: break-word;
        }
        .attachment-details-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 10px;
            color: #556980;
            flex-wrap: wrap;
        }
        .attachment-image-display {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            margin-top: 10px;
        }
        .attachment-image-display img.print-actual-picture {
            max-width: 90%;
            max-height: 520px;
            width: auto;
            height: auto;
            object-fit: contain;
            border-radius: 6px;
            border: 1px solid #c8d5e2;
            box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
            background: #f8fafc;
        }

        @page { size: landscape; margin: 10mm; }
        @media print {
            body { background: #fff; }
            .report-page {
                width: 100% !important;
                min-height: auto !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
            }
            .print-actions { display: none !important; }
            thead { display: table-header-group; }
            tr { break-inside: avoid; }
            .attachment-single-page {
                page-break-before: always !important;
                break-before: page !important;
                page-break-after: avoid !important;
                break-after: avoid !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
                width: 100% !important;
                height: 185mm !important;
                min-height: 185mm !important;
                max-height: 185mm !important;
                padding: 0 !important;
                margin: 0 !important;
                box-shadow: none !important;
                border: none !important;
                display: flex !important;
                flex-direction: column !important;
                justify-content: space-between !important;
            }
            .attachment-main-content {
                flex: 1 !important;
                display: flex !important;
                flex-direction: column !important;
                align-items: center !important;
                justify-content: center !important;
                margin: auto 0 !important;
                padding: 8px 0 !important;
            }
            .attachment-image-display img.print-actual-picture {
                max-width: 92% !important;
                max-height: 125mm !important;
                object-fit: contain !important;
                box-shadow: none !important;
                border: 1px solid #94a3b8 !important;
                print-color-adjust: exact !important;
                -webkit-print-color-adjust: exact !important;
            }
            .brand-wordmark {
                filter: brightness(0) !important;
                -webkit-filter: brightness(0) !important;
                print-color-adjust: exact !important;
                -webkit-print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <a href="{{ route('reports.index', $filters) }}">Back to Reports</a>
        <button type="button" onclick="window.print()">Print / Publish Report (PDF)</button>
    </div>

    @php
        $allImageAttachments = collect();
        foreach ($requests as $item) {
            foreach ($item->requestFiles as $file) {
                if ($file->isImage()) {
                    $allImageAttachments->push([
                        'file' => $file,
                        'request' => $item,
                        'category_title' => 'Request Attachment',
                        'category_badge' => 'Request Photo Attachment',
                        'pill_class' => 'pill-request',
                    ]);
                }
            }
            foreach ($item->workOrderFiles as $file) {
                if ($file->isImage()) {
                    $allImageAttachments->push([
                        'file' => $file,
                        'request' => $item,
                        'category_title' => 'Dial-A Work Order Attachment',
                        'category_badge' => 'Work Order Attachment',
                        'pill_class' => 'pill-work-order',
                    ]);
                }
            }
            foreach ($item->serviceReportFiles as $file) {
                if ($file->isImage()) {
                    $allImageAttachments->push([
                        'file' => $file,
                        'request' => $item,
                        'category_title' => 'Dial-A Service Report Attachment',
                        'category_badge' => 'Service Report Attachment',
                        'pill_class' => 'pill-service-report',
                    ]);
                }
            }
        }
    @endphp

    <main class="report-page">
        <header class="report-head">
            <div class="brand">
                <div class="brand-text">
                    <img class="brand-wordmark" src="{{ asset('images/no-bg-gateway-logo.png') }}" alt="GATEWAY">
                    <span>PROPERTY MANAGEMENT SYSTEM</span>
                </div>
            </div>
            <div class="report-title">
                <h1>Requests Operations Report</h1>
                <p>Published {{ now()->format('M d, Y h:i A') }}</p>
                <p>Prepared by {{ $generatedBy->name }}, PM Manager</p>
            </div>
        </header>

        <section class="summary">
            <div><span>Total Requests</span><strong>{{ $summary['total'] }}</strong></div>
            <div><span>Urgent Priority</span><strong>{{ $summary['urgent'] }}</strong></div>
            <div><span>Regular Priority</span><strong>{{ $summary['regular'] }}</strong></div>
            <div><span>Request Attachments</span><strong>{{ $summary['request_attachments'] ?? $requests->sum(fn($r) => $r->requestFiles->count()) }}</strong></div>
            <div><span>Dial-A Attachments</span><strong>{{ $summary['dial_a_attachments'] }}</strong><span style="font-size: 8px; color: #72869c; display: block; margin-top: 2px;">({{ $summary['work_order_files'] }} WO · {{ $summary['service_report_files'] }} SR)</span></div>
        </section>

        <p class="filters"><strong>Filters:</strong>
            @if(!empty($filters['stage']) && $filters['stage'] !== 'all')
                Stage: {{ ucfirst(str_replace('_', ' ', $filters['stage'])) }} &middot;
            @endif
            Status: {{ !empty($filters['status']) && $filters['status'] !== 'all' ? (in_array($filters['status'], ['not_acknowledged', 'for_acknowledgement'], true) ? 'For Acknowledgement' : (in_array($filters['status'], ['in_progress', 'work_in_progress']) ? 'Work in Progress' : ($filters['status'] === 'aging' ? 'Aging Request' : ucfirst(str_replace('_', ' ', $filters['status']))))) : 'All Statuses' }}
            @if($dateFilterLabel)
                &middot; Date: {{ $dateFilterLabel }}
            @endif
            @php($activeFilters = collect($filters)->except(['stage', 'status', 'date_period', 'period_day', 'period_week', 'period_month'])->filter(fn($value) => $value !== null && $value !== ''))
            @foreach($activeFilters as $name => $value)
                · {{ str($name)->replace('_', ' ')->title() }}: {{ $name === 'assigned_support_id' ? ($requests->firstWhere('assigned_support_id', $value)?->assignedSupport?->name ?? $value) : $value }}
            @endforeach
        </p>

        <table>
            <thead>
                <tr>
                    <th>Reference</th>
                    <th>Requested / Completed</th>
                    <th>Dealer / Area</th>
                    <th>Request Details</th>
                    <th>Request Photos / Attachments</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Assigned Dial-A</th>
                    <th>Dial-A Attachments (Work Order &amp; Service Report)</th>
                    <th>Submitted By</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $item)
                    <tr>
                        <td class="nowrap"><strong>{{ $item->reference_no }}</strong></td>
                        <td class="nowrap">
                            <div><strong>Req:</strong> {{ $item->request_date->format('M d, Y') }}</div>
                            <div><strong>Done:</strong> {{ $item->completed_at?->format('M d, Y') ?? '—' }}</div>
                        </td>
                        <td>
                            <strong>{{ $item->display_dealer_name }}</strong><br>
                            <span style="font-size: 8.5px; color: #526b85;">{{ $item->display_branch }} · {{ $item->area }}</span>
                        </td>
                        <td>
                            <strong>{{ $item->request_type }}</strong>
                            <div class="description">{{ $item->description }}</div>
                        </td>
                        <td class="print-attachments-wrap">
                            @if($item->requestFiles->isNotEmpty())
                                <ul class="print-file-text-list">
                                    @foreach($item->requestFiles as $file)
                                        <li class="print-file-text-item">
                                            <span class="print-file-badge {{ $file->isImage() ? 'badge-img' : ($file->isPdf() ? 'badge-pdf' : ($file->isExcel() ? 'badge-excel' : ($file->isWord() ? 'badge-word' : ''))) }}">{{ $file->fileTypeLabel() }}</span>
                                            <span class="print-file-name" title="{{ $file->original_name }}">{{ Str::limit($file->original_name, 24) }}</span>
                                            <span class="print-file-size">({{ number_format($file->size / 1024, 1) }} KB)</span>
                                            @if($file->isImage())
                                                <span class="print-page-tag" title="Image attached on dedicated page">↳ Attached Page</span>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="print-none-text">No request attachments</span>
                            @endif
                        </td>
                        <td><span class="badge badge-{{ $item->priority }}">{{ ucfirst($item->priority) }}</span></td>
                        <td><span class="badge badge-done">Completed</span></td>
                        <td>{{ $item->assignedSupport?->name ?? 'Unassigned' }}</td>
                        <td class="print-attachments-wrap">
                            @if($item->workOrderFiles->isNotEmpty())
                                <div class="print-attachment-section">
                                    <div class="print-attachment-heading">Work Order ({{ $item->workOrderFiles->count() }}):</div>
                                    <ul class="print-file-text-list">
                                        @foreach($item->workOrderFiles as $file)
                                            <li class="print-file-text-item">
                                                <span class="print-file-badge {{ $file->isImage() ? 'badge-img' : ($file->isPdf() ? 'badge-pdf' : ($file->isExcel() ? 'badge-excel' : ($file->isWord() ? 'badge-word' : ''))) }}">{{ $file->fileTypeLabel() }}</span>
                                                <span class="print-file-name" title="{{ $file->original_name }}">{{ Str::limit($file->original_name, 22) }}</span>
                                                <span class="print-file-size">({{ number_format($file->size / 1024, 1) }} KB)</span>
                                                @if($file->isImage())
                                                    <span class="print-page-tag" title="Image attached on dedicated page">↳ Attached Page</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($item->serviceReportFiles->isNotEmpty())
                                <div class="print-attachment-section">
                                    <div class="print-attachment-heading">Service Report ({{ $item->serviceReportFiles->count() }}):</div>
                                    <ul class="print-file-text-list">
                                        @foreach($item->serviceReportFiles as $file)
                                            <li class="print-file-text-item">
                                                <span class="print-file-badge {{ $file->isImage() ? 'badge-img' : ($file->isPdf() ? 'badge-pdf' : ($file->isExcel() ? 'badge-excel' : ($file->isWord() ? 'badge-word' : ''))) }}">{{ $file->fileTypeLabel() }}</span>
                                                <span class="print-file-name" title="{{ $file->original_name }}">{{ Str::limit($file->original_name, 22) }}</span>
                                                <span class="print-file-size">({{ number_format($file->size / 1024, 1) }} KB)</span>
                                                @if($file->isImage())
                                                    <span class="print-page-tag" title="Image attached on dedicated page">↳ Attached Page</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if($item->workOrderFiles->isEmpty() && $item->serviceReportFiles->isEmpty())
                                <span class="print-none-text">No Dial-A attachments</span>
                            @endif
                        </td>
                        <td>
                            {{ $item->submitter_name }}<br>
                            <small style="color: #6a7f98;">{{ $item->designation }}</small>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" style="text-align: center; padding: 20px; color: #6a7f98;">No completed requests match the selected report filters.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <footer class="report-footer">
            <span>Gateway Property Management System · PM Manager Report</span>
            <span>{{ number_format($requests->count()) }} completed {{ Str::plural('request', $requests->count()) }}</span>
        </footer>
    </main>

    @foreach($allImageAttachments as $attachment)
        <section class="attachment-single-page">
            <header class="report-head">
                <div class="brand">
                    <div class="brand-text">
                        <img class="brand-wordmark" src="{{ asset('images/no-bg-gateway-logo.png') }}" alt="GATEWAY">
                        <span>PROPERTY MANAGEMENT SYSTEM</span>
                    </div>
                </div>
                <div class="report-title">
                    <h1>Completed Requests Operations Report</h1>
                    <p>Attachment Exhibit · Image {{ $loop->iteration }} of {{ $allImageAttachments->count() }}</p>
                    <p>Ref: <strong>{{ $attachment['request']->reference_no }}</strong> · {{ $attachment['request']->dealer->name }}</p>
                </div>
            </header>

            <div class="attachment-main-content">
                <div class="attachment-title-card">
                    <div class="attachment-meta-pill-group">
                        <span class="attachment-pill {{ $attachment['pill_class'] }}">{{ $attachment['category_badge'] }}</span>
                        <span class="attachment-pill pill-reference">Ref: {{ $attachment['request']->reference_no }}</span>
                        <span class="attachment-pill pill-dealer">{{ $attachment['request']->dealer->name }} ({{ $attachment['request']->area }})</span>
                    </div>
                    <h2 class="attachment-main-title">
                        {{ $attachment['category_title'] }}: {{ $attachment['file']->original_name }}
                    </h2>
                    <div class="attachment-details-row">
                        <span><strong>Request Type:</strong> {{ $attachment['request']->request_type }}</span>
                        <span>&bull;</span>
                        <span><strong>Submitted By:</strong> {{ $attachment['request']->submitter_name }} ({{ $attachment['request']->designation }})</span>
                        <span>&bull;</span>
                        <span><strong>Assigned Dial-A:</strong> {{ $attachment['request']->assignedSupport?->name ?? 'Unassigned' }}</span>
                        <span>&bull;</span>
                        <span><strong>File Size:</strong> {{ number_format($attachment['file']->size / 1024, 1) }} KB</span>
                    </div>
                </div>

                <div class="attachment-image-display">
                    <img class="print-actual-picture" src="{{ route('attachments.show', $attachment['file']) }}" alt="{{ $attachment['file']->original_name }}" loading="eager">
                </div>
            </div>

            <footer class="report-footer">
                <span>Gateway Property Management System · Attachment Exhibit (Image {{ $loop->iteration }} of {{ $allImageAttachments->count() }})</span>
                <span>{{ $attachment['file']->original_name }} · Ref: {{ $attachment['request']->reference_no }}</span>
            </footer>
        </section>
    @endforeach
</body>
</html>
