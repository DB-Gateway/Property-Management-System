<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dealer Directory · Gateway PMS</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; color: #172b42; font: 10px Arial, sans-serif; background: #eef2f6; }
        .print-actions { position: sticky; top: 0; z-index: 2; display: flex; justify-content: flex-end; gap: 8px; padding: 10px; background: #061a2a; }
        .print-actions button, .print-actions a { padding: 9px 13px; border: 0; border-radius: 5px; color: #fff; background: #2463ff; font-weight: bold; text-decoration: none; cursor: pointer; }
        .print-actions a { background: #526b85; }
        .directory-page { width: min(100%, 1400px); min-height: 100vh; margin: 0 auto; padding: 28px; background: #fff; }
        .directory-head { display: flex; align-items: flex-start; justify-content: space-between; gap: 24px; padding-bottom: 16px; border-bottom: 3px solid #ef1838; }
        .brand-wordmark { width: auto; max-width: 135px; height: 24px; object-fit: contain; filter: brightness(0); }
        .brand-subtitle { margin-top: 4px; color: #607895; font-size: 8px; font-weight: bold; letter-spacing: 1px; }
        .directory-title { text-align: right; }
        .directory-title h1 { margin: 0 0 6px; color: #061a2a; font-size: 21px; }
        .directory-title p { margin: 3px 0; color: #61748b; }
        .filters { margin: 16px 0 12px; padding: 9px 11px; color: #4d647e; border: 1px solid #dbe4ee; background: #f8fafc; }
        .filters span + span::before { content: " · "; color: #9aa9ba; }
        .filters strong { color: #172b42; }
        .result-count { margin: 0 0 10px; color: #607895; }
        table { width: 100%; border-collapse: collapse; }
        th { padding: 7px 5px; color: #fff; background: #102e4c; font-size: 7.5px; letter-spacing: .25px; text-align: left; text-transform: uppercase; }
        td { padding: 7px 5px; border: 1px solid #dce5ef; vertical-align: top; line-height: 1.35; }
        tbody tr:nth-child(even) { background: #f8fafc; }
        .number { width: 38px; text-align: center; }
        .identity { min-width: 115px; }
        .identity strong, .identity small { display: block; }
        .identity small { margin-top: 2px; color: #61748b; }
        .contact { white-space: nowrap; }
        .empty { padding: 24px; text-align: center; color: #61748b; }
        .footer { display: flex; justify-content: space-between; margin-top: 14px; padding-top: 9px; color: #6a7f98; border-top: 1px solid #dce5ef; font-size: 8px; }
        @page { size: landscape; margin: 9mm; }
        @media print {
            body { background: #fff; }
            .print-actions { display: none; }
            .directory-head { display: none; }
            .filters { display: none; }
            .result-count { display: none; }
            .directory-page { width: 100%; min-height: auto; margin: 0; padding: 0; }
            thead { display: table-header-group; }
            tr { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="print-actions">
        <a href="{{ route('dealers.index', request()->only(['search', 'city', 'area', 'brand'])) }}">Back to Directory</a>
        <button type="button" onclick="window.print()">Print</button>
    </div>

    <main class="directory-page">
        <header class="directory-head">
            <div>
                <img class="brand-wordmark" src="{{ asset('images/no-bg-gateway-logo.png') }}" alt="Gateway">
                <div class="brand-subtitle">PROPERTY MANAGEMENT SYSTEM</div>
            </div>
            <div class="directory-title">
            </div>
        </header>

        @if($activeFilters)
            <div class="filters">
                @foreach($activeFilters as $label => $value)
                    <span><strong>{{ $label }}:</strong> {{ $value }}</span>
                @endforeach
            </div>
        @endif

        <p class="result-count">{{ number_format($dealers->count()) }} {{ $dealers->count() === 1 ? 'dealer' : 'dealers' }}</p>

        <table>
            <thead>
                <tr>
                    <th class="number">No.</th>
                    <th>Dealer / Brand</th>
                    <th>Branch (City)</th>
                    <th>Area</th>
                    <th>Address</th>
                    <th>Point Person 1</th>
                    <th>Contact</th>
                    <th>Point Person 2</th>
                    <th>Contact</th>
                </tr>
            </thead>
            <tbody>
                @forelse($dealers as $dealer)
                    <tr>
                        <td class="number">{{ $dealer->source_no }}</td>
                        <td class="identity"><strong>{{ $dealer->brand ?: $dealer->name }}</strong><small>{{ $dealer->name }}</small></td>
                        <td>{{ $dealer->city ?: '—' }}</td>
                        <td>{{ $dealer->area ?: '—' }}</td>
                        <td>{{ $dealer->address ?: '—' }}</td>
                        <td>{{ $dealer->point_person_1 ?: '—' }}</td>
                        <td class="contact">{{ $dealer->contact_1 ?: '—' }}</td>
                        <td>{{ $dealer->point_person_2 ?: '—' }}</td>
                        <td class="contact">{{ $dealer->contact_2 ?: '—' }}</td>
                    </tr>
                @empty
                    <tr><td class="empty" colspan="9">No dealers found matching the selected filters.</td></tr>
                @endforelse
            </tbody>
        </table>

        <footer class="footer">
            <span>Gateway Property Management System</span>
            <span>Dealer Directory</span>
        </footer>
    </main>
</body>
</html>
