<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\RequestAttachment;
use App\Models\User;
use App\Support\SimpleXlsxWriter;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $this->managerOnly($request);
        $filters = $this->filters($request);

        $selectedBranch = trim((string) ($filters['branch'] ?? ''));
        $selectedArea = trim((string) ($filters['area'] ?? ''));
        $selectedBrand = trim((string) ($filters['brand'] ?? ''));

        $directoryMatrix = Dealer::query()
            ->select('city', 'area', 'brand')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->get()
            ->map(fn ($d) => [
                'city' => $d->city,
                'area' => $d->area,
                'brand' => $d->brand,
            ]);

        // Sanitize selections if any mutually incompatible pairs are passed
        if ($selectedBranch !== '' && $selectedArea !== '') {
            $valid = $directoryMatrix->contains(fn ($row) => $row['city'] === $selectedBranch && $row['area'] === $selectedArea);
            if (! $valid) {
                $selectedArea = '';
                $filters['area'] = '';
            }
        }
        if ($selectedBranch !== '' && $selectedBrand !== '') {
            $valid = $directoryMatrix->contains(fn ($row) => $row['city'] === $selectedBranch && $row['brand'] === $selectedBrand);
            if (! $valid) {
                $selectedBrand = '';
                $filters['brand'] = '';
            }
        }
        if ($selectedArea !== '' && $selectedBrand !== '') {
            $valid = $directoryMatrix->contains(fn ($row) => $row['area'] === $selectedArea && $row['brand'] === $selectedBrand);
            if (! $valid) {
                $selectedBrand = '';
                $filters['brand'] = '';
            }
        }

        $query = $this->reportQuery($filters);
        $summary = $this->summary(clone $query);
        $requests = $query->paginate(20)->withQueryString();

        $baseCounts = PropertyRequest::query();
        $agingCount = (clone $baseCounts)->overdue()->count();
        $counts = [
            'total' => (clone $baseCounts)->count(),
            'not_acknowledged' => (clone $baseCounts)->notAcknowledged()->count(),
            'inspection_pending' => (clone $baseCounts)->stageStatus('inspection', 'pending')->count(),
            'inspection_ongoing' => (clone $baseCounts)->stageStatus('inspection', 'on_going')->count(),
            'inspection_completed' => (clone $baseCounts)->stageStatus('inspection', 'completed')->count(),
            'work_order_pending' => (clone $baseCounts)->stageStatus('work_order', 'pending')->count(),
            'work_order_ongoing' => (clone $baseCounts)->stageStatus('work_order', 'on_going')->count(),
            'work_order_completed' => (clone $baseCounts)->stageStatus('work_order', 'completed')->count(),
            'service_report_pending' => (clone $baseCounts)->stageStatus('service_report', 'pending')->count(),
            'service_report_ongoing' => (clone $baseCounts)->stageStatus('service_report', 'on_going')->count(),
            'service_report_completed' => (clone $baseCounts)->stageStatus('service_report', 'completed')->count(),
            'pending' => (clone $baseCounts)->pendingRequest()->count(),
            'completed' => (clone $baseCounts)->where('status', 'completed')->count(),
            'aging' => $agingCount,
            'overdue' => $agingCount,
        ];

        foreach (['inspection', 'work_order', 'service_report'] as $stage) {
            $counts[$stage] = $counts["{$stage}_pending"]
                + $counts["{$stage}_ongoing"]
                + $counts["{$stage}_completed"];
        }

        $branches = $directoryMatrix
            ->when($selectedArea !== '', fn ($c) => $c->filter(fn ($r) => $r['area'] === $selectedArea))
            ->when($selectedBrand !== '', fn ($c) => $c->filter(fn ($r) => $r['brand'] === $selectedBrand))
            ->pluck('city')->unique()->sort()->values();

        $areas = $directoryMatrix
            ->when($selectedBranch !== '', fn ($c) => $c->filter(fn ($r) => $r['city'] === $selectedBranch))
            ->when($selectedBrand !== '', fn ($c) => $c->filter(fn ($r) => $r['brand'] === $selectedBrand))
            ->pluck('area')->unique()->sort()->values();

        $brands = $directoryMatrix
            ->when($selectedBranch !== '', fn ($c) => $c->filter(fn ($r) => $r['city'] === $selectedBranch))
            ->when($selectedArea !== '', fn ($c) => $c->filter(fn ($r) => $r['area'] === $selectedArea))
            ->pluck('brand')->unique()->sort()->values();

        return view('reports.index', [
            'requests' => $requests,
            'summary' => $summary,
            'counts' => $counts,
            'agingRequestsEnabled' => config('features.aging_requests', false),
            'filters' => $filters,
            'dateFilterLabel' => $this->dateFilterLabel($filters),
            'branches' => $branches,
            'areas' => $areas,
            'brands' => $brands,
            'directoryMatrix' => $directoryMatrix,
            'selectedBranch' => $selectedBranch,
            'selectedArea' => $selectedArea,
            'selectedBrand' => $selectedBrand,
            'supportUsers' => User::query()->where('role', 'pm_support')->where('is_active', true)->orderBy('name')->get(),
            'requestTypes' => PropertyRequest::query()->distinct()->orderBy('request_type')->pluck('request_type'),
        ]);
    }

    public function printable(Request $request)
    {
        $this->managerOnly($request);
        $filters = $this->filters($request);
        $query = $this->reportQuery($filters);
        $records = $query->get();
        $summary = $this->summary($this->reportQuery($filters));

        AuditLog::record(
            'report_published',
            "PM Manager {$request->user()->name} published an operations report ({$records->count()} records).",
            null,
            $filters
        );

        return view('reports.print', [
            'requests' => $records,
            'summary' => $summary,
            'filters' => $filters,
            'dateFilterLabel' => $this->dateFilterLabel($filters),
            'generatedBy' => $request->user(),
        ]);
    }

    public function download(Request $request)
    {
        $this->managerOnly($request);
        $filters = $this->filters($request);
        $query = $this->reportQuery($filters);
        $summary = $this->summary(clone $query);
        $records = $query->get();

        $rows = $records->map(fn (PropertyRequest $item) => [
            $item->reference_no,
            $item->request_date->format('Y-m-d'),
            $item->ageing_due_date->format('Y-m-d'),
            $item->display_dealer_name.' ('.$item->display_branch.')',
            $item->area,
            $item->request_type,
            ucfirst($item->priority),
            $item->status_label,
            $item->activity_progress[0]['label'],
            $item->activity_progress[1]['label'],
            $item->activity_progress[2]['label'],
            $item->activity_progress[3]['label'],
            $item->submitter_name,
            $item->designation,
            $item->assignedSupport?->name ?? 'Designated Dial-Lead',
            $item->assignedManager?->name ?? '',
            $item->approved_date?->format('Y-m-d') ?? '',
            $item->inspection_date?->format('Y-m-d') ?? '',
            $item->representative_1 ?? '',
            $item->representative_2 ?? '',
            $item->representative_3 ?? '',
            $item->inspection_completed_at?->format('Y-m-d H:i') ?? '',
            $item->work_order_completed_at?->format('Y-m-d H:i') ?? '',
            $item->service_report_completed_at?->format('Y-m-d H:i') ?? '',
            $item->completed_at?->format('Y-m-d H:i') ?? '',
            $item->description,
        ])->all();

        $subtitle = 'Generated by '.$request->user()->name.' on '.now()->format('M d, Y h:i A');
        $workbook = SimpleXlsxWriter::make(
            'Gateway PMS - Request Operations Report',
            $subtitle,
            [
                'Total' => $summary['total'],
                'Urgent' => $summary['urgent'],
                'Regular' => $summary['regular'],
                'Attachments' => $summary['dial_a_attachments'],
            ],
            ['Reference', 'Request Date', 'Due Date', 'Dealer / Branch', 'Area', 'Request Type', 'Priority', 'Status', 'Inspection Progress', 'Work Order Progress', 'Service Report Progress', 'Completion Progress', 'Submitted By', 'Designation', 'Dial-Lead', 'Inspection Date', 'Representative 1', 'Representative 2', 'Representative 3', 'Inspection Completed', 'Work Order Completed', 'Service Report Completed', 'Request Completed', 'Description'],
            $rows,
        );

        $filename = 'Gateway-PM-Request-Report-'.now()->format('Ymd-His').'.xlsx';

        return response($workbook, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Content-Length' => (string) strlen($workbook),
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    private function managerOnly(Request $request): void
    {
        abort_unless($request->user()->isManager(), 403);
    }

    /** @return array<string, mixed> */
    private function filters(Request $request): array
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', Rule::in(['not_acknowledged', 'for_acknowledgement', 'pending', 'on_going', 'in_progress', 'work_in_progress', 'awaiting_dealer', 'completed', 'overdue', 'aging'])],
            'date_period' => ['nullable', Rule::in(['day', 'week', 'month'])],
            'period_day' => ['nullable', 'required_if:date_period,day', Rule::in([
                'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday',
            ])],
            'period_week' => [
                'nullable',
                'required_if:date_period,week',
                'regex:/^\d{4}-W(0[1-9]|[1-4]\d|5[0-3])$/',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    if (! is_string($value) || ! preg_match('/^(\d{4})-W(\d{2})$/', $value, $matches)) {
                        return;
                    }

                    $weekStart = CarbonImmutable::now()->setISODate((int) $matches[1], (int) $matches[2]);
                    if ($weekStart->format('o-\WW') !== $value) {
                        $fail('Please choose a valid calendar week.');
                    }
                },
            ],
            'period_month' => ['nullable', 'required_if:date_period,month', 'regex:/^\d{4}-(0[1-9]|1[0-2])$/'],
            'priority' => ['nullable', Rule::in(['urgent', 'regular'])],
            'branch' => ['nullable', 'string', 'max:255'],
            'area' => ['nullable', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'request_type' => ['nullable', 'string', 'max:255'],
            'assigned_support_id' => [
                'nullable',
                'integer',
                Rule::exists('users', 'id')->where(fn ($query) => $query->where('role', 'pm_support')),
            ],
        ]);

        foreach (['day', 'week', 'month'] as $period) {
            if (($filters['date_period'] ?? null) !== $period) {
                unset($filters['period_'.$period]);
            }
        }

        if (empty($filters['date_period'])) {
            $filters['date_period'] = 'month';
            $filters['period_month'] = now()->format('Y-m');
        } elseif ($filters['date_period'] === 'month' && empty($filters['period_month'])) {
            $filters['period_month'] = now()->format('Y-m');
        }

        return $filters;
    }

    /** @param array<string, mixed> $filters */
    private function reportQuery(array $filters): Builder
    {
        $query = PropertyRequest::query()
            ->with(['dealer', 'assignedSupport', 'assignedManager', 'requestFiles', 'workOrderFiles', 'serviceReportFiles'])
            ->when($filters['status'] ?? null, function (Builder $query, string $status) {
                if ($status === 'overdue' || $status === 'aging') {
                    $query->overdue();
                } elseif ($status === 'not_acknowledged' || $status === 'for_acknowledgement') {
                    $query->notAcknowledged();
                } elseif ($status === 'in_progress' || $status === 'work_in_progress' || $status === 'on_going') {
                    $query->whereIn('status', ['on_going', 'in_progress']);
                } elseif ($status === 'pending') {
                    $query->pendingRequest();
                } else {
                    $query->where('status', $status);
                }
            }, function (Builder $query) {
                $query->where('status', 'completed');
            })
            ->when($filters['search'] ?? null, function (Builder $query, string $search) {
                $query->where(function (Builder $nested) use ($search) {
                    $nested->where('reference_no', 'like', "%{$search}%")
                        ->orWhere('request_type', 'like', "%{$search}%")
                        ->orWhere('submitter_name', 'like', "%{$search}%")
                        ->orWhere('branch', 'like', "%{$search}%")
                        ->orWhereHas('dealer', fn (Builder $dealer) => $dealer->where('name', 'like', "%{$search}%"));
                });
            });

        $this->applyDatePeriod($query, $filters);

        return $query
            ->when($filters['priority'] ?? null, fn (Builder $query, string $priority) => $query->where('priority', $priority))
            ->when($filters['branch'] ?? null, function (Builder $query, string $branch) {
                $query->where(function (Builder $nested) use ($branch) {
                    $nested->where('branch', $branch)
                        ->orWhereHas('dealer', fn (Builder $dealer) => $dealer->where('city', $branch));
                });
            })
            ->when($filters['area'] ?? null, function (Builder $query, string $area) {
                $query->where(function (Builder $nested) use ($area) {
                    $nested->where('area', $area)
                        ->orWhereHas('dealer', fn (Builder $dealer) => $dealer->where('area', $area));
                });
            })
            ->when($filters['brand'] ?? null, function (Builder $query, string $brand) {
                $query->where(function (Builder $nested) use ($brand) {
                    $nested->where('dealer_name', $brand)
                        ->orWhereHas('dealer', fn (Builder $dealer) => $dealer->where('brand', $brand));
                });
            })
            ->when($filters['request_type'] ?? null, fn (Builder $query, string $type) => $query->where('request_type', $type))
            ->when($filters['assigned_support_id'] ?? null, fn (Builder $query, int|string $supportId) => $query->where('assigned_support_id', $supportId))
            ->orderByDesc('completed_at')
            ->orderByDesc('request_date')
            ->orderByDesc('id');
    }

    /** @param array<string, mixed> $filters */
    private function applyDatePeriod(Builder $query, array $filters): void
    {
        $period = $filters['date_period'] ?? null;

        if ($period === 'day' && ! empty($filters['period_day'])) {
            $weekday = [
                'sunday' => 0,
                'monday' => 1,
                'tuesday' => 2,
                'wednesday' => 3,
                'thursday' => 4,
                'friday' => 5,
                'saturday' => 6,
            ][$filters['period_day']];

            match ($query->getConnection()->getDriverName()) {
                'sqlite' => $query->whereRaw("CAST(strftime('%w', request_date) AS INTEGER) = ?", [$weekday]),
                'pgsql' => $query->whereRaw('EXTRACT(DOW FROM request_date) = ?', [$weekday]),
                'sqlsrv' => $query->whereRaw("DATEDIFF(day, '19000107', request_date) % 7 = ?", [$weekday]),
                default => $query->whereRaw('DAYOFWEEK(request_date) = ?', [$weekday + 1]),
            };

            $activeMonth = ! empty($filters['period_month']) ? $filters['period_month'] : now()->format('Y-m');
            $from = CarbonImmutable::createFromFormat('!Y-m', $activeMonth)->startOfMonth();
            $query->whereDate('request_date', '>=', $from->toDateString())
                ->whereDate('request_date', '<=', $from->endOfMonth()->toDateString());

            return;
        }

        if ($period === 'week' && ! empty($filters['period_week'])) {
            preg_match('/^(\d{4})-W(\d{2})$/', $filters['period_week'], $matches);
            $from = CarbonImmutable::now()->setISODate((int) $matches[1], (int) $matches[2])->startOfWeek();
            $query->whereDate('request_date', '>=', $from->toDateString())
                ->whereDate('request_date', '<=', $from->addDays(6)->toDateString());

            return;
        }

        $activeMonth = ! empty($filters['period_month']) ? $filters['period_month'] : now()->format('Y-m');
        $from = CarbonImmutable::createFromFormat('!Y-m', $activeMonth)->startOfMonth();
        $query->whereDate('request_date', '>=', $from->toDateString())
            ->whereDate('request_date', '<=', $from->endOfMonth()->toDateString());
    }

    /** @param array<string, mixed> $filters */
    private function dateFilterLabel(array $filters): ?string
    {
        $period = $filters['date_period'] ?? null;

        if ($period === 'day' && ! empty($filters['period_day'])) {
            return 'Every '.ucfirst($filters['period_day']);
        }

        if ($period === 'week' && ! empty($filters['period_week'])) {
            preg_match('/^(\d{4})-W(\d{2})$/', $filters['period_week'], $matches);
            $from = CarbonImmutable::now()->setISODate((int) $matches[1], (int) $matches[2])->startOfWeek();
            $to = $from->addDays(6);

            return $from->format('M d').' - '.$to->format('M d, Y');
        }

        $activeMonth = ! empty($filters['period_month']) ? $filters['period_month'] : now()->format('Y-m');
        return CarbonImmutable::createFromFormat('!Y-m', $activeMonth)->format('F Y');
    }

    /** @return array{total: int, urgent: int, regular: int, request_attachments: int, dial_a_attachments: int, work_order_files: int, service_report_files: int, all_attachments: int} */
    private function summary(Builder $query): array
    {
        $ids = (clone $query)->pluck('id');

        $attachmentCounts = RequestAttachment::query()
            ->whereIn('property_request_id', $ids)
            ->whereIn('category', ['request', 'work_order', 'service_report'])
            ->selectRaw('category, count(*) as count')
            ->groupBy('category')
            ->pluck('count', 'category');

        $reqCount = (int) ($attachmentCounts['request'] ?? 0);
        $woCount = (int) ($attachmentCounts['work_order'] ?? 0);
        $srCount = (int) ($attachmentCounts['service_report'] ?? 0);

        return [
            'total' => (clone $query)->count(),
            'urgent' => (clone $query)->where('priority', 'urgent')->count(),
            'regular' => (clone $query)->where('priority', 'regular')->count(),
            'request_attachments' => $reqCount,
            'dial_a_attachments' => $woCount + $srCount,
            'work_order_files' => $woCount,
            'service_report_files' => $srCount,
            'all_attachments' => $reqCount + $woCount + $srCount,
        ];
    }
}
