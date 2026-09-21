<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Dealer;
use App\Models\PropertyRequest;
use App\Models\RequestAttachment;
use App\Models\User;
use App\Services\RequestNotificationService;
use App\Support\SimpleXlsxWriter;
use App\Support\PmActionConfirmation;
use Carbon\CarbonImmutable;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PropertyRequestController extends Controller
{
    private const INSPECTION_TEMPLATE_FILENAME = 'Inspection Request Template.xlsx';

    private const TYPES = [
        'General Repairs',
        'Electrical Works',
        'Plumbing Works',
        'Carpentry & Woodworks',
        'Painting',
        'Airconditioning',
    ];

    private const REMARK_SUGGESTIONS = [
        'General Repairs' => 'Describe the damaged item or area, what was observed, when the issue started, and the repair needed.',
        'Electrical Works' => 'Identify the affected lights, outlets, wiring, or equipment; describe the symptoms, power impact, and any safety risk.',
        'Plumbing Works' => 'Identify the leak, blockage, fixture, or water line; describe its location, severity, and effect on operations.',
        'Carpentry & Woodworks' => 'Describe the affected door, cabinet, partition, furniture, or wood fixture, including damage, measurements, and preferred finish.',
        'Painting' => 'Identify the area or surface, approximate size, current condition, preferred color or finish, and any access limitations.',
        'Airconditioning' => 'Identify the unit and location; describe whether it is not cooling, leaking, noisy, or not powering on, and when the issue began.',
    ];

    public function index(Request $request)
    {
        $selectedMonth = $this->resolveSelectedMonth($request);
        $user = $request->user();

        $selectedBranch = $request->string('branch')->trim()->value();
        $selectedAreaRaw = $request->string('area')->trim()->value();
        $selectedArea = $selectedAreaRaw;
        $selectedBrand = $request->string('brand')->trim()->value() ?: $request->string('dealer')->trim()->value();
        $selectedDealerId = $request->string('dealer_id')->trim()->value();

        // Build directory matrix based on user role
        if ($user->isDealer() && $user->dealer) {
            $sameCityDealers = $user->dealer->sameCityDealers();
            $directoryMatrix = $sameCityDealers->map(function ($d) {
                return [
                    'city' => $d->branch,
                    'area' => $d->area,
                    'brand' => $d->brand ?: $d->name,
                ];
            })->unique()->values();
        } else {
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
        }

        if (str_contains($selectedAreaRaw, ' - ')) {
            [$part1, $part2] = explode(' - ', $selectedAreaRaw, 2);
            $part1 = trim($part1);
            $part2 = trim($part2);
            if ($directoryMatrix->pluck('area')->unique()->contains($part2)) {
                $selectedBranch = $part1;
                $selectedArea = $part2;
            } elseif ($directoryMatrix->pluck('area')->unique()->contains($part1)) {
                $selectedArea = $part1;
                $selectedBranch = $part2;
            } else {
                $selectedBranch = $part1;
                $selectedArea = $part2;
            }
        } elseif ($selectedAreaRaw !== '' && $directoryMatrix->pluck('city')->unique()->contains($selectedAreaRaw)) {
            $selectedBranch = $selectedAreaRaw;
            $selectedArea = '';
        }

        // Sanitize selections if any mutually incompatible pairs are passed
        if ($selectedBranch !== '' && $selectedArea !== '') {
            $valid = $directoryMatrix->contains(fn ($row) => $row['city'] === $selectedBranch && $row['area'] === $selectedArea);
            if (! $valid) {
                $selectedArea = '';
            }
        }
        if ($selectedBranch !== '' && $selectedBrand !== '') {
            $valid = $directoryMatrix->contains(fn ($row) => $row['city'] === $selectedBranch && $row['brand'] === $selectedBrand);
            if (! $valid) {
                $selectedBrand = '';
            }
        }
        if ($selectedArea !== '' && $selectedBrand !== '') {
            $valid = $directoryMatrix->contains(fn ($row) => $row['area'] === $selectedArea && $row['brand'] === $selectedBrand);
            if (! $valid) {
                $selectedBrand = '';
            }
        }

        $requests = $this->requestsQuery($request)
            ->paginate(15)
            ->withQueryString();

        // Strict dependent dropdown options
        $branches = $directoryMatrix
            ->when($selectedArea !== '', fn ($c) => $c->filter(fn ($r) => $r['area'] === $selectedArea))
            ->when($selectedBrand !== '', fn ($c) => $c->filter(fn ($r) => $r['brand'] === $selectedBrand))
            ->pluck('city')
            ->unique()
            ->sort()
            ->values();

        $areas = $directoryMatrix
            ->when($selectedBranch !== '', fn ($c) => $c->filter(fn ($r) => $r['city'] === $selectedBranch))
            ->when($selectedBrand !== '', fn ($c) => $c->filter(fn ($r) => $r['brand'] === $selectedBrand))
            ->pluck('area')
            ->unique()
            ->sort()
            ->values();

        $brands = $directoryMatrix
            ->when($selectedBranch !== '', fn ($c) => $c->filter(fn ($r) => $r['city'] === $selectedBranch))
            ->when($selectedArea !== '', fn ($c) => $c->filter(fn ($r) => $r['area'] === $selectedArea))
            ->pluck('brand')
            ->unique()
            ->sort()
            ->values();

        $areasWithCities = $directoryMatrix
            ->when($selectedBrand !== '', fn ($c) => $c->filter(fn ($r) => $r['brand'] === $selectedBrand))
            ->groupBy('area')
            ->map(function ($group) {
                return $group->pluck('city')->unique()->sort()->values();
            })
            ->sortKeys();

        $dealers = Dealer::query()->orderBy('name')->get();

        $baseCounts = PropertyRequest::query();
        if ($user->isDialA()) {
            $baseCounts->where('assignment_type', '!=', 'pending_review');
        }
        if ($user && $user->isDealer()) {
            $baseCounts->where('submitted_by', $user->id);
        }

        $agingCount = (clone $baseCounts)->overdue()->count();
        $counts = [
            'pm_review' => (clone $baseCounts)->where('assignment_type', 'pending_review')->count(),
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

        $agingRequestsEnabled = config('features.aging_requests', false);

        return view('requests.index', compact(
            'requests',
            'dealers',
            'branches',
            'areas',
            'brands',
            'areasWithCities',
            'directoryMatrix',
            'selectedBranch',
            'selectedArea',
            'selectedAreaRaw',
            'selectedBrand',
            'selectedMonth',
            'counts',
            'agingRequestsEnabled'
        ));
    }

    public function export(Request $request)
    {
        $records = $this->requestsQuery($request)->get();

        $headers = [
            'Request No.',
            'Branch',
            'Area',
            'Dealer / Brand',
            'Repair Category',
            'Assigned To',
            'Priority',
            'Status',
            'Submitted Date',
            'Inspection Progress',
            'Work Order Progress',
            'Service Report Progress',
            'Completion Progress',
            'Description',
        ];

        $rows = $records->map(fn (PropertyRequest $item) => [
            $item->reference_no,
            $item->display_branch,
            $item->area ?? $item->dealer?->area ?? '',
            $item->display_dealer_name,
            $item->request_type,
            $item->assignedSupport?->name ?? 'Unassigned',
            $item->priority_label,
            $item->status_label,
            $item->created_at->format('M d, Y h:i A'),
            $item->activity_progress[0]['label'] ?? '',
            $item->activity_progress[1]['label'] ?? '',
            $item->activity_progress[2]['label'] ?? '',
            $item->activity_progress[3]['label'] ?? '',
            $item->description ?? '',
        ])->all();

        $sheetName = $request->user()->isDealer() ? 'My Requests' : 'Requests';
        $widths = [18, 20, 20, 25, 22, 20, 14, 20, 22, 20, 20, 22, 20, 40];
        $workbook = SimpleXlsxWriter::table($headers, $rows, $sheetName, $widths);

        $filename = ($request->user()->isDealer() ? 'My-Requests-' : 'Requests-').now()->format('Ymd-His').'.xlsx';

        return response($workbook, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Content-Length' => (string) strlen($workbook),
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    private function requestsQuery(Request $request): Builder
    {
        $selectedMonth = $this->resolveSelectedMonth($request);
        $monthFrom = CarbonImmutable::createFromFormat('!Y-m', $selectedMonth)->startOfMonth();
        $monthTo = $monthFrom->endOfMonth();

        $user = $request->user();
        $query = PropertyRequest::query()
            ->with(['dealer', 'assignedSupport'])
            ->when($user->isDealer(), fn ($q) => $q->where('submitted_by', $user->id))
            ->when($user->isDialA(), fn ($q) => $q->where('assignment_type', '!=', 'pending_review'))
            ->when(! (in_array('pm_review', [$request->stage, $request->status], true) && ! $request->filled('month') && ! $request->filled('period_month')),
                fn ($q) => $q->whereDate('request_date', '>=', $monthFrom->toDateString())
                    ->whereDate('request_date', '<=', $monthTo->toDateString()));

        $selectedBranch = $request->string('branch')->trim()->value();
        $selectedAreaRaw = $request->string('area')->trim()->value();
        $selectedArea = $selectedAreaRaw;
        $selectedBrand = $request->string('brand')->trim()->value() ?: $request->string('dealer')->trim()->value();
        $selectedDealerId = $request->string('dealer_id')->trim()->value();

        if ($user->isDealer() && $user->dealer) {
            $sameCityDealers = $user->dealer->sameCityDealers();
            $directoryMatrix = $sameCityDealers->map(function ($d) {
                return [
                    'city' => $d->branch,
                    'area' => $d->area,
                    'brand' => $d->brand ?: $d->name,
                ];
            })->unique()->values();
        } else {
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
        }

        if (str_contains($selectedAreaRaw, ' - ')) {
            [$part1, $part2] = explode(' - ', $selectedAreaRaw, 2);
            $part1 = trim($part1);
            $part2 = trim($part2);
            if ($directoryMatrix->pluck('area')->unique()->contains($part2)) {
                $selectedBranch = $part1;
                $selectedArea = $part2;
            } elseif ($directoryMatrix->pluck('area')->unique()->contains($part1)) {
                $selectedArea = $part1;
                $selectedBranch = $part2;
            } else {
                $selectedBranch = $part1;
                $selectedArea = $part2;
            }
        } elseif ($selectedAreaRaw !== '' && $directoryMatrix->pluck('city')->unique()->contains($selectedAreaRaw)) {
            $selectedBranch = $selectedAreaRaw;
            $selectedArea = '';
        }

        if ($selectedBranch !== '' && $selectedArea !== '') {
            $valid = $directoryMatrix->contains(fn ($row) => $row['city'] === $selectedBranch && $row['area'] === $selectedArea);
            if (! $valid) {
                $selectedArea = '';
            }
        }
        if ($selectedBranch !== '' && $selectedBrand !== '') {
            $valid = $directoryMatrix->contains(fn ($row) => $row['city'] === $selectedBranch && $row['brand'] === $selectedBrand);
            if (! $valid) {
                $selectedBrand = '';
            }
        }
        if ($selectedArea !== '' && $selectedBrand !== '') {
            $valid = $directoryMatrix->contains(fn ($row) => $row['area'] === $selectedArea && $row['brand'] === $selectedBrand);
            if (! $valid) {
                $selectedBrand = '';
            }
        }

        return $query
            ->when($request->filled('search'), function ($q) use ($request) {
                $search = $request->string('search')->trim();
                $q->where(function ($nested) use ($search) {
                    $nested->where('reference_no', 'like', "%{$search}%")
                        ->orWhere('request_type', 'like', "%{$search}%")
                        ->orWhere('branch', 'like', "%{$search}%")
                        ->orWhere('area', 'like', "%{$search}%")
                        ->orWhere('dealer_name', 'like', "%{$search}%")
                        ->orWhereHas('dealer', fn ($dealer) => $dealer->where('name', 'like', "%{$search}%")->orWhere('area', 'like', "%{$search}%"));
                });
            })
            ->when($request->filled('stage'), function ($q) use ($request) {
                $stage = $request->stage;
                if ($stage === 'pm_review') {
                    $q->where('assignment_type', 'pending_review');
                } elseif ($stage === 'not_acknowledged' || $stage === 'for_acknowledgement') {
                    $q->notAcknowledged();
                } elseif ($request->filled('status') && $request->status !== 'all') {
                    $q->stageStatus($stage, $request->status);
                } else {
                    $q->atWorkflowStage($stage);
                }
            })
            ->when(!$request->filled('stage') && $request->filled('status'), function ($q) use ($request) {
                $status = $request->status;
                if ($status === 'pm_review') {
                    $q->where('assignment_type', 'pending_review');
                } elseif (in_array($status, ['overdue', 'aging'], true)) {
                    $q->overdue();
                } elseif ($status === 'not_acknowledged' || $status === 'for_acknowledgement') {
                    $q->notAcknowledged();
                } elseif ($status === 'pending') {
                    $q->pendingRequest();
                } elseif (in_array($status, ['on_going', 'in_progress'], true)) {
                    $q->whereIn('status', ['on_going', 'in_progress']);
                } elseif (in_array($status, ['inspection', 'work_order', 'service_report'], true)) {
                    $q->atWorkflowStage($status);
                } else {
                    $q->where('status', $status);
                }
            })
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->priority))
            ->when($selectedDealerId !== '', fn ($q) => $q->where('dealer_id', $selectedDealerId))
            ->when($selectedBranch !== '', function ($q) use ($selectedBranch) {
                $q->where(function ($nested) use ($selectedBranch) {
                    $nested->where('branch', $selectedBranch)
                        ->orWhereHas('dealer', fn ($dealer) => $dealer->where('city', $selectedBranch));
                });
            })
            ->when($selectedArea !== '', function ($q) use ($selectedArea) {
                $q->where(function ($nested) use ($selectedArea) {
                    $nested->where('area', $selectedArea)
                        ->orWhereHas('dealer', fn ($dealer) => $dealer->where('area', $selectedArea));
                });
            })
            ->when($selectedBrand !== '', function ($q) use ($selectedBrand) {
                $q->where(function ($nested) use ($selectedBrand) {
                    $nested->where('dealer_name', $selectedBrand)
                        ->orWhereHas('dealer', fn ($dealer) => $dealer->where('brand', $selectedBrand)->orWhere('name', $selectedBrand));
                });
            })
            ->latest();
    }

    private function resolveSelectedMonth(Request $request): string
    {
        $month = $request->string('month')->trim()->value() ?: $request->string('period_month')->trim()->value();
        if ($month !== '' && preg_match('/^\d{4}-(0[1-9]|1[0-2])$/', $month)) {
            return $month;
        }

        return now()->format('Y-m');
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->isDealer() && $request->user()->dealer, 403);

        $user = $request->user();
        $dealer = $user->dealer;
        $branch = $dealer->branch;
        $area = $dealer->area;
        $sameCityDealers = $dealer->sameCityDealers();

        return view('requests.create', [
            'types' => self::TYPES,
            'remarkSuggestions' => self::REMARK_SUGGESTIONS,
            'branch' => $branch,
            'area' => $area,
            'currentDealer' => $dealer,
            'sameCityDealers' => $sameCityDealers,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        abort_unless($user->isDealer() && $user->dealer, 403);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'designation' => ['required', 'string', 'max:255'],
            'dealer_id' => ['nullable', 'integer', 'exists:dealers,id'],
            'dealer_name' => ['nullable', 'string', 'max:255'],
            'dealer_address' => ['nullable', 'string', 'max:500'],
            'dealer_contact_person' => ['nullable', 'string', 'max:255'],
            'dealer_contact_number' => ['nullable', 'string', 'max:50'],
            'request_type' => ['required', Rule::in(self::TYPES)],
            'description' => ['required', 'string', 'min:10', 'max:5000'],
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['file', 'extensions:jpeg,png,jpg,webp,pdf,doc,docx,xls,xlsx,csv', 'max:10240'],
        ], [
            'attachments.*.extensions' => 'Request attachments must be valid image files (JPG, JPEG, PNG, WEBP), PDF, Word (.doc, .docx), or Excel (.xls, .xlsx, .csv).',
            'attachments.*.max' => 'Each attachment may not exceed 10 MB.',
        ]);

        $propertyRequest = DB::transaction(function () use ($request, $user, $validated) {
            $prefix = 'GPM-'.today()->format('Ymd').'-';
            $lastReference = PropertyRequest::where('reference_no', 'like', $prefix.'%')
                ->lockForUpdate()
                ->orderByDesc('reference_no')
                ->value('reference_no');
            $nextSequence = $lastReference ? ((int) substr($lastReference, -4)) + 1 : 1;
            $reference = $prefix.str_pad((string) $nextSequence, 4, '0', STR_PAD_LEFT);
            $days = 4;

            $chosenDealer = !empty($validated['dealer_id'])
                ? \App\Models\Dealer::find($validated['dealer_id'])
                : $user->dealer;

            // Update dealer contact details if edited
            if ($chosenDealer) {
                $dealerUpdates = array_filter([
                    'point_person_1' => $validated['dealer_contact_person'] ?? null,
                    'contact_1' => $validated['dealer_contact_number'] ?? null,
                    'address' => $validated['dealer_address'] ?? null,
                ], fn ($val) => $val !== null && trim((string)$val) !== '');

                if (!empty($dealerUpdates)) {
                    $chosenDealer->update($dealerUpdates);
                }
            }

            // Strictly lock Branch to City and Area to user's dealership area
            $branch = $user->dealer->branch ?: ($chosenDealer?->branch ?: 'Pasig');
            $area = $user->dealer->area;
            $dealerName = !empty($validated['dealer_name'])
                ? $validated['dealer_name']
                : ($chosenDealer?->dealer_brand ?: ($chosenDealer?->name ?: 'Dealer'));

            $created = PropertyRequest::create([
                'reference_no' => $reference,
                'dealer_id' => $chosenDealer?->id ?? $user->dealer_id,
                'dealer_name' => $dealerName,
                'submitted_by' => $user->id,
                'assigned_support_id' => null,
                'assignment_type' => 'pending_review',
                'submitter_name' => $validated['name'],
                'designation' => $validated['designation'],
                'branch' => $branch,
                'area' => $area,
                'request_type' => $validated['request_type'],
                'priority' => 'regular',
                'description' => $validated['description'],
                'request_date' => today(),
                'due_date' => today()->addDays($days),
                'status' => 'pending',
            ]);

            foreach ($request->file('attachments', []) as $attachment) {
                $path = $attachment->store('request-attachments');
                $created->attachments()->create([
                    'category' => 'request',
                    'path' => $path,
                    'original_name' => $attachment->getClientOriginalName(),
                    'mime_type' => $attachment->getMimeType(),
                    'size' => $attachment->getSize(),
                ]);
            }

            return $created;
        });

        AuditLog::record('request_submitted', "{$propertyRequest->reference_no} was submitted by {$user->name} for {$propertyRequest->dealer_name} ({$propertyRequest->branch}).", $propertyRequest);

        return redirect()->route('requests.show', $propertyRequest)->with('status', 'Request submitted successfully. Awaiting PM review of priority, remarks, and assignment.');
    }

    public function show(Request $request, PropertyRequest $propertyRequest)
    {
        $this->ensureCanView($request, $propertyRequest);

        if ($request->user()->isDialA()) {
            $request->user()->unreadNotifications()
                ->where('property_request_id', $propertyRequest->id)
                ->where('data->kind', 'new_request')
                ->update(['read_at' => now()]);
        }

        $propertyRequest->load([
            'dealer',
            'submitter',
            'assignedSupport',
            'assignedManager',
            'requestFiles',
            'inspectionFiles',
            'workOrderFiles',
            'serviceReportFiles',
            'inHouseCompletionFiles',
        ]);

        return view('requests.show', compact('propertyRequest'));
    }

    public function inspectionRequestTemplate(Request $request)
    {
        abort_unless($request->user()->isDialA(), 403, 'Only Dial-A users may download the Inspection Request Template.');

        $templatePath = resource_path('templates/'.self::INSPECTION_TEMPLATE_FILENAME);
        abort_unless(is_file($templatePath), 404, 'The Inspection Request Template is unavailable.');

        return response()->download($templatePath, self::INSPECTION_TEMPLATE_FILENAME, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    public function updateSchedule(Request $request, PropertyRequest $propertyRequest)
    {
        return DB::transaction(function () use ($request, $propertyRequest) {
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless($request->user()->isDialA() && $propertyRequest->isAssignedTo($request->user()), 403);
            abort_if($propertyRequest->status === 'completed' || $propertyRequest->completed_at, 422, 'Reopen the request before changing its schedule.');
            $validated = $request->validate([
                'inspection_date' => ['sometimes', 'nullable', 'date_format:Y-m-d'],
                'work_order_start_date' => ['sometimes', 'nullable', 'date_format:Y-m-d'],
                'service_report_date' => ['sometimes', 'nullable', 'date_format:Y-m-d'],
            ]);
            foreach (\App\Services\RequestNotificationService::STAGES as [$label, $schedule, $completed]) {
                if ($propertyRequest->$completed && array_key_exists($schedule, $validated)
                    && $validated[$schedule] !== $propertyRequest->$schedule?->format('Y-m-d')) {
                    throw ValidationException::withMessages([$schedule => "Reopen {$label} before changing its date."]);
                }
            }
            $before = $propertyRequest->only(array_keys($validated));
            DB::transaction(function () use ($propertyRequest, $validated, $before) {
                $propertyRequest->update($validated);
                if ($propertyRequest->wasChanged(array_keys($validated))) {
                    AuditLog::record('request_schedule_changed', "{$propertyRequest->reference_no} schedule was updated.", $propertyRequest, [
                        'before' => $before, 'after' => $validated,
                    ]);
                }
            });

            return back()->with('status', 'Schedule saved. Any changes have been sent to the dealer and PM managers.');
        });
    }

    public function assignToMe(Request $request, PropertyRequest $propertyRequest)
    {
        return DB::transaction(function () use ($request, $propertyRequest) {
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless($request->user()->isSupport(), 403);

            if ($propertyRequest->assigned_support_id && ! $propertyRequest->isAssignedTo($request->user())) {
                throw ValidationException::withMessages([
                    'assignment' => 'This request is already assigned to another Dial-A user.',
                ]);
            }

            $propertyRequest->update([
                'assigned_support_id' => $request->user()->id,
                'status' => $propertyRequest->status === 'pending' ? 'on_going' : $propertyRequest->status,
            ]);
            AuditLog::record('request_assigned', "{$propertyRequest->reference_no} was assigned to {$request->user()->name}.", $propertyRequest);

            return back()->with('status', 'Request assigned to you.');
        });
    }

    public function updateStatus(Request $request, PropertyRequest $propertyRequest)
    {
        return DB::transaction(function () use ($request, $propertyRequest) {
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless($request->user()->isSupport(), 403);
            $this->ensureAssignedSupport($request, $propertyRequest);

            $validated = $request->validate([
                'status' => ['required', Rule::in(['pending', 'on_going', 'in_progress', 'awaiting_dealer'])],
                'status_note' => ['nullable', 'string', 'max:1000'],
            ]);

            $oldStatus = $propertyRequest->status;
            $propertyRequest->update([
                'status' => $validated['status'],
                'assigned_support_id' => $propertyRequest->assigned_support_id ?: $request->user()->id,
                'completed_at' => null,
            ]);

            AuditLog::record(
                'request_status_updated',
                "{$propertyRequest->reference_no} changed from ".str_replace('_', ' ', $oldStatus).' to '.str_replace('_', ' ', $validated['status']).'.',
                $propertyRequest,
                ['note' => $validated['status_note'] ?? null]
            );

            return back()->with('status', 'Request status updated.');
        });
    }

    public function updatePriority(Request $request, PropertyRequest $propertyRequest)
    {
        abort_unless($request->user()->isManager() || $request->user()->isAdmin(), 403, 'Only PM Managers can edit priority status.');
        abort_if($propertyRequest->isAwaitingPmReview(), 422, 'Complete the PM review with priority, remarks, and assignment first.');
        PmActionConfirmation::validate($request);

        $validated = $request->validate([
            'priority' => ['required', Rule::in(['regular', 'urgent'])],
            'remarks' => ['required', 'string', 'max:1000'],
        ], [
            'priority.required' => 'Please select a priority status.',
            'priority.in' => 'Selected priority is invalid.',
            'remarks.max' => 'Remarks may not exceed 1000 characters.',
            'current_password.required' => 'Your password is required to update priority status.',
            'current_password.current_password' => 'The password you entered is incorrect.',
        ]);

        $oldPriority = $propertyRequest->priority;
        $newPriority = $validated['priority'];
        $remarks = isset($validated['remarks']) && trim($validated['remarks']) !== '' ? trim($validated['remarks']) : null;
        $priorityChanged = $oldPriority !== $newPriority;
        $remarksChanged = $propertyRequest->priority_remarks !== $remarks;

        if ($priorityChanged || $remarksChanged) {
            $propertyRequest->update([
                'priority' => $newPriority,
                'priority_remarks' => $remarks,
            ]);

            AuditLog::record(
                'priority_status_updated',
                "{$propertyRequest->reference_no} priority status changed from ".ucfirst($oldPriority)." to ".ucfirst($newPriority)." by {$request->user()->role_label} {$request->user()->name}." . ($remarks ? " Remarks: {$remarks}" : ''),
                $propertyRequest,
                [
                    'old_priority' => $oldPriority,
                    'new_priority' => $newPriority,
                    'remarks' => $remarks,
                    'updated_by_id' => $request->user()->id,
                    'updated_by_name' => $request->user()->name,
                ]
            );

            app(RequestNotificationService::class)->priorityChanged($propertyRequest, $oldPriority, $newPriority, $remarks);

            $statusMessage = "Priority for {$propertyRequest->reference_no} updated to " . ucfirst($newPriority) . " and dealer has been notified.";
        } else {
            $statusMessage = "Priority for {$propertyRequest->reference_no} remained unchanged (" . ucfirst($newPriority) . ").";
        }

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $statusMessage,
                'priority' => $newPriority,
                'priority_label' => ucfirst($newPriority),
                'remarks' => $remarks,
            ]);
        }

        return back()->with('status', $statusMessage);
    }

    public function acknowledge(Request $request, PropertyRequest $propertyRequest)
    {
        abort(403, 'PM Manager has view-only access and acknowledgement approval has been removed.');
    }

    public function assignInspection(Request $request, PropertyRequest $propertyRequest)
    {
        abort(403, 'PM Manager has view-only access and acknowledgement approval has been removed.');
    }

    public function saveInspectionDate(Request $request, PropertyRequest $propertyRequest)
    {
        return DB::transaction(function () use ($request, $propertyRequest) {
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless($request->user()->isDialA() && $propertyRequest->isAssignedTo($request->user()), 403, 'Only Dial-A is authorized to conduct and complete inspections.');

            if ($propertyRequest->inspection_completed_at) {
                throw ValidationException::withMessages(['inspection_date' => 'The inspection has already been completed.']);
            }

            $validated = $request->validate([
                'inspection_date' => ['required', 'date_format:Y-m-d'],
                'inspection_start_time' => ['nullable', 'string'],
                'inspection_representatives' => ['required', 'array', 'min:1', 'max:3'],
                'inspection_representatives.*' => ['required', 'string', 'max:255', 'distinct:ignore_case'],
            ], [
                'inspection_date.required' => 'Select the inspection date.',
                'inspection_date.date_format' => 'Enter a valid inspection date (YYYY-MM-DD).',
                'inspection_representatives.required' => 'Add at least one inspection representative.',
                'inspection_representatives.max' => 'You may add up to three inspection representatives.',
                'inspection_representatives.*.required' => 'Each inspection representative must have a name.',
                'inspection_representatives.*.distinct' => 'Each inspection representative must be unique.',
            ]);

            $inspectionRepresentatives = array_values(array_map(
                fn (string $representative) => trim($representative),
                $validated['inspection_representatives']
            ));

            $startTime = $propertyRequest->inspection_start_time;
            if (!$startTime) {
                $timeInput = $request->input('inspection_start_time');
                if (is_string($timeInput) && preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', trim($timeInput))) {
                    $parts = explode(':', trim($timeInput));
                    $h = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                    $m = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                    $s = isset($parts[2]) ? str_pad($parts[2], 2, '0', STR_PAD_LEFT) : '00';
                    $startTime = "{$h}:{$m}:{$s}";
                } else {
                    $startTime = now()->format('H:i:s');
                }
            }

            $updates = [
                'inspection_date' => $validated['inspection_date'],
                'inspection_start_time' => $startTime,
                'representative_1' => $inspectionRepresentatives[0] ?? null,
                'representative_2' => $inspectionRepresentatives[1] ?? null,
                'representative_3' => $inspectionRepresentatives[2] ?? null,
                'status' => 'on_going',
            ];

            $propertyRequest->update($updates);

            AuditLog::record(
                'inspection_date_set',
                "{$propertyRequest->reference_no} inspection start date set to " . Carbon::parse($validated['inspection_date'])->format('F d, Y') . " and status set to On-going by Dial-A {$request->user()->name}.",
                $propertyRequest,
                [
                    'inspection_date' => $validated['inspection_date'],
                    'inspection_start_time' => $startTime,
                    'inspection_representatives' => $inspectionRepresentatives,
                ]
            );

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'status' => $propertyRequest->status,
                    'inspection_date' => $validated['inspection_date'],
                    'inspection_start_time' => $startTime,
                    'message' => 'Inspection start date saved. Request is On-going.',
                ]);
            }

            return redirect()->route('requests.show', $propertyRequest)->withFragment('inspection')
                ->with('status', 'Inspection start date saved. Request is On-going.');
        });
    }

    public function completeInspection(Request $request, PropertyRequest $propertyRequest)
    {
        return DB::transaction(function () use ($request, $propertyRequest) {
            // Reload inside the lock: two submissions may have bound the same unfinished request.
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless($request->user()->isDialA() && $propertyRequest->isAssignedTo($request->user()), 403, 'Only Dial-A is authorized to conduct and complete inspections.');

            if ($propertyRequest->inspection_completed_at) {
                throw ValidationException::withMessages(['inspection' => 'The inspection has already been completed.']);
            }

            if (! $request->filled('inspection_date') && $propertyRequest->inspection_date) {
                $request->merge(['inspection_date' => $propertyRequest->inspection_date->format('Y-m-d')]);
            }

            // The saved start timestamp is authoritative. Hidden form fields must
            // not be able to move it backwards to make an early completion valid.
            if ($propertyRequest->inspection_date && (! $request->filled('inspection_date') || $request->input('inspection_date') < $propertyRequest->inspection_date->format('Y-m-d'))) {
                $request->merge(['inspection_date' => $propertyRequest->inspection_date->format('Y-m-d')]);
            }
            if ($propertyRequest->inspection_start_time && (! $request->filled('inspection_start_time') || $request->input('inspection_start_time') < substr($propertyRequest->inspection_start_time, 0, 5))) {
                $request->merge(['inspection_start_time' => substr($propertyRequest->inspection_start_time, 0, 5)]);
            }

            $now = now();
            $nowDate = $now->toDateString();
            $nowTime = $now->format('H:i:s');

            if (! $request->filled('inspection_end_date')) {
                if ($propertyRequest->inspection_end_date) {
                    $request->merge(['inspection_end_date' => $propertyRequest->inspection_end_date->format('Y-m-d')]);
                } elseif ($request->filled('inspection_date') && $request->input('inspection_date') > $nowDate) {
                    $request->merge(['inspection_end_date' => $request->input('inspection_date')]);
                } else {
                    $request->merge(['inspection_end_date' => $nowDate]);
                }
            }

            if (! $request->filled('inspection_start_time') && $propertyRequest->inspection_start_time) {
                $request->merge(['inspection_start_time' => substr($propertyRequest->inspection_start_time, 0, 5)]);
            }

            // Browser device times and SQL TIME values may include seconds. The
            // inspection form intentionally works at minute precision (H:i).
            foreach (['inspection_start_time', 'inspection_end_time'] as $timeField) {
                $timeValue = $request->input($timeField);
                if (is_string($timeValue) && preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d(?::[0-5]\d)?$/', trim($timeValue))) {
                    $request->merge([$timeField => substr(trim($timeValue), 0, 5)]);
                }
            }

            if (! $request->filled('inspection_end_time')) {
                $endTime = substr($nowTime, 0, 5);
                $request->merge(['inspection_end_time' => $endTime]);
            }

            if (!$request->has('inspection_representatives') && ($request->has('representative_1') || $request->has('representative_2') || $request->has('representative_3'))) {
                $representatives = array_filter([
                    $request->input('representative_1'),
                    $request->input('representative_2'),
                    $request->input('representative_3'),
                ], fn ($val) => !is_null($val) && trim((string)$val) !== '');
                $request->merge(['inspection_representatives' => array_values($representatives)]);
            }

            $isSingleFile = $request->hasFile('inspection_file') && ! $request->hasFile('inspection_files');

            $rules = [
                'inspection_date' => ['required', 'date_format:Y-m-d'],
                'inspection_end_date' => ['required', 'date_format:Y-m-d', 'after_or_equal:inspection_date'],
                'inspection_start_time' => ['nullable', 'date_format:H:i'],
                'inspection_end_time' => ['nullable', 'date_format:H:i'],
                'inspection_representatives' => ['required', 'array', 'min:1', 'max:3'],
                'inspection_representatives.*' => ['required', 'string', 'max:255', 'distinct:ignore_case'],
            ];

            $messages = [
                'inspection_date.required' => 'Select the inspection start date.',
                'inspection_date.date_format' => 'Enter a valid inspection start date (YYYY-MM-DD).',
                'inspection_end_date.required' => 'Select the inspection end date.',
                'inspection_end_date.date_format' => 'Enter a valid inspection end date (YYYY-MM-DD).',
                'inspection_end_date.after_or_equal' => 'The inspection end date must be on or after the start date.',
                'inspection_representatives.required' => 'Add at least one inspection representative.',
                'inspection_representatives.max' => 'You may add up to three inspection representatives.',
                'inspection_representatives.*.required' => 'Each inspection representative must have a name.',
                'inspection_representatives.*.distinct' => 'Each inspection representative must be unique.',
            ];

            if ($isSingleFile) {
                $rules['inspection_file'] = ['required', 'file', 'extensions:jpeg,png,jpg,webp,pdf,doc,docx,xls,xlsx,csv', 'max:20480'];
                $messages['inspection_file.required'] = 'Please upload at least one Inspection file.';
                $messages['inspection_file.extensions'] = 'Inspection attachments must be image files (JPG, PNG, WEBP), PDF, Word (.doc, .docx), or Excel (.xls, .xlsx, .csv).';
                $messages['inspection_file.max'] = 'Each Inspection attachment may not exceed 20 MB.';
            } else {
                $rules['inspection_files'] = ['required', 'array', 'min:1', 'max:5'];
                $rules['inspection_files.*'] = ['required', 'file', 'extensions:jpeg,png,jpg,webp,pdf,doc,docx,xls,xlsx,csv', 'max:20480'];
                $messages['inspection_files.required'] = 'Please upload at least one Inspection file.';
                $messages['inspection_files.*.extensions'] = 'Inspection attachments must be image files (JPG, PNG, WEBP), PDF, Word (.doc, .docx), or Excel (.xls, .xlsx, .csv).';
                $messages['inspection_files.*.max'] = 'Each Inspection attachment may not exceed 20 MB.';
            }

            $validator = Validator::make($request->all(), $rules, $messages);

            $validator->after(function ($validator) use ($request, $now) {
                if (! $request->hasFile('inspection_files') && ! $request->hasFile('inspection_file')) {
                    $validator->errors()->add('inspection_file', 'Please upload at least one Inspection file.');
                    $validator->errors()->add('inspection_files', 'Please upload at least one Inspection file.');
                }

                if ($request->filled('inspection_date') && $request->filled('inspection_start_time')
                    && $request->filled('inspection_end_date') && $request->filled('inspection_end_time')) {
                    try {
                        $startAt = Carbon::parse($request->input('inspection_date').' '.$request->input('inspection_start_time'));
                        $endAt = Carbon::parse($request->input('inspection_end_date').' '.$request->input('inspection_end_time'));
                        $earliestCompletion = $startAt->copy()->addMinute();

                        if ($endAt->lt($earliestCompletion) || $now->lt($earliestCompletion)) {
                            $validator->errors()->add(
                                'inspection_end_time',
                                'The inspection cannot be completed at the same time or before its saved start date and time. Please do the task first.'
                            );
                        }
                    } catch (\Throwable) {
                        // The format rules above provide the field-specific error.
                    }
                }
            });

            $validated = $validator->validate();

            $inspectionRepresentatives = array_values(array_map(
                fn (string $representative) => trim($representative),
                $validated['inspection_representatives']
            ));

            $inspectionEndDate = $validated['inspection_end_date'];
            $inspectionEndTime = !empty($validated['inspection_end_time']) ? $validated['inspection_end_time'] : $nowTime;

            $updates = [
                'inspection_date' => $validated['inspection_date'],
                'inspection_end_date' => $inspectionEndDate,
                'representative_1' => $inspectionRepresentatives[0] ?? null,
                'representative_2' => $inspectionRepresentatives[1] ?? null,
                'representative_3' => $inspectionRepresentatives[2] ?? null,
                'inspection_completed_at' => $now,
                'status' => 'on_going',
            ];
            if (!empty($validated['inspection_start_time'])) {
                $updates['inspection_start_time'] = $validated['inspection_start_time'];
            }
            $updates['inspection_end_time'] = $inspectionEndTime;

            $filesToStore = [];
            if ($request->hasFile('inspection_files')) {
                $files = $request->file('inspection_files');
                $filesToStore = is_array($files) ? $files : [$files];
            } elseif ($request->hasFile('inspection_file')) {
                $filesToStore = [$request->file('inspection_file')];
            }

            $this->storeWorkflowFiles($propertyRequest, $filesToStore, 'inspection');
            $propertyRequest->update($updates);

            $reps = array_values(array_filter([
                $propertyRequest->representative_1,
                $propertyRequest->representative_2,
                $propertyRequest->representative_3,
            ]));

            AuditLog::record(
                'inspection_completed',
                "{$propertyRequest->reference_no} inspection was conducted and completed by Dial-A {$request->user()->name}" . ($reps ? ' with representative(s): ' . implode(', ', $reps) : '') . '.',
                $propertyRequest,
                [
                    'inspection_date' => $propertyRequest->inspection_date?->format('Y-m-d'),
                    'inspection_end_date' => $propertyRequest->inspection_end_date?->format('Y-m-d'),
                    'inspection_start_time' => $propertyRequest->inspection_start_time,
                    'inspection_end_time' => $propertyRequest->inspection_end_time,
                    'representatives' => $reps,
                    'inspection_attachment' => !empty($filesToStore) ? $filesToStore[0]->getClientOriginalName() : null,
                    'inspection_attachments_count' => count($filesToStore),
                    'template_confirmed' => true,
                    'inspection_completed_at' => $propertyRequest->inspection_completed_at,
                ]
            );

            return redirect()->route('requests.show', $propertyRequest)->withFragment('inspection')
                ->with('status', 'Inspection marked as completed. The Work Order stage is now available for Dial-A.');
        });
    }

    public function saveWorkOrderDate(Request $request, PropertyRequest $propertyRequest)
    {
        return DB::transaction(function () use ($request, $propertyRequest) {
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless($request->user()->isDialA() && $propertyRequest->isAssignedTo($request->user()), 403, 'Only Dial-A is authorized to update Work Order details.');

            if (! $propertyRequest->inspection_completed_at) {
                throw ValidationException::withMessages(['work_order_start_date' => 'Complete the inspection before setting Work Order start date.']);
            }

            if ($propertyRequest->work_order_completed_at) {
                throw ValidationException::withMessages(['work_order_start_date' => 'The Work Order has already been completed.']);
            }

            $validated = $request->validate([
                'work_order_start_date' => ['required', 'date_format:Y-m-d'],
                'work_order_start_time' => ['nullable', 'string'],
                'work_order_representatives' => ['required', 'array', 'min:1', 'max:10'],
                'work_order_representatives.*' => ['required', 'string', 'max:255', 'distinct:ignore_case'],
            ], [
                'work_order_start_date.required' => 'Select the Work Order start date.',
                'work_order_start_date.date_format' => 'Enter a valid Work Order start date (YYYY-MM-DD).',
                'work_order_representatives.required' => 'Add at least one Work Order representative.',
                'work_order_representatives.max' => 'You may add up to ten Work Order representatives.',
                'work_order_representatives.*.required' => 'Each Work Order representative must have a name.',
                'work_order_representatives.*.distinct' => 'Each Work Order representative must be unique.',
            ]);

            $workOrderRepresentatives = array_values(array_map(
                fn (string $representative) => trim($representative),
                $validated['work_order_representatives']
            ));

            $startTime = $propertyRequest->work_order_start_time;
            if (!$startTime) {
                $timeInput = $request->input('work_order_start_time');
                if (is_string($timeInput) && preg_match('/^\d{1,2}:\d{2}(:\d{2})?$/', trim($timeInput))) {
                    $parts = explode(':', trim($timeInput));
                    $h = str_pad($parts[0], 2, '0', STR_PAD_LEFT);
                    $m = str_pad($parts[1], 2, '0', STR_PAD_LEFT);
                    $s = isset($parts[2]) ? str_pad($parts[2], 2, '0', STR_PAD_LEFT) : '00';
                    $startTime = "{$h}:{$m}:{$s}";
                } else {
                    $startTime = now()->format('H:i:s');
                }
            }

            $updates = [
                'work_order_start_date' => $validated['work_order_start_date'],
                'work_order_start_time' => $startTime,
                'work_order_representatives' => $workOrderRepresentatives,
                'status' => 'on_going',
            ];

            $propertyRequest->update($updates);

            AuditLog::record(
                'work_order_date_set',
                "{$propertyRequest->reference_no} Work Order start date set to " . Carbon::parse($validated['work_order_start_date'])->format('F d, Y') . " and status set to On-going by Dial-A {$request->user()->name}.",
                $propertyRequest,
                [
                    'work_order_start_date' => $validated['work_order_start_date'],
                    'work_order_start_time' => $startTime,
                    'work_order_representatives' => $workOrderRepresentatives,
                ]
            );

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'status' => $propertyRequest->status,
                    'work_order_start_date' => $validated['work_order_start_date'],
                    'work_order_start_time' => $startTime,
                    'message' => 'Work Order start date saved. Request is On-going.',
                ]);
            }

            return redirect()->route('requests.show', $propertyRequest)->withFragment('work-order')
                ->with('status', 'Work Order start date saved. Request is On-going.');
        });
    }

    public function completeWorkOrder(Request $request, PropertyRequest $propertyRequest)
    {
        return DB::transaction(function () use ($request, $propertyRequest) {
            // Reload inside the lock: two submissions may have bound the same unfinished request.
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless($request->user()->isDialA() && $propertyRequest->isAssignedTo($request->user()), 403, 'Only Dial-A is authorized to upload Work Order attachments.');

            if (! $propertyRequest->inspection_completed_at) {
                throw ValidationException::withMessages(['work_order' => 'Complete the inspection before submitting a Work Order.']);
            }

            if ($propertyRequest->work_order_completed_at) {
                throw ValidationException::withMessages(['work_order' => 'The Work Order has already been completed.']);
            }

            // The browser supplies the device-local completion timestamp at submit time.
            // Fall back to the application clock for end time when JavaScript is unavailable.
            $completionNow = now();
            $woEndTimeInput = $request->input('work_order_end_time');
            if (is_string($woEndTimeInput) && strlen(trim($woEndTimeInput)) === 5) {
                $request->merge(['work_order_end_time' => trim($woEndTimeInput) . ':00']);
            }
            if (! $request->filled('work_order_end_time')) {
                $request->merge([
                    'work_order_end_time' => $completionNow->format('H:i:s'),
                ]);
            }

            $validated = $request->validate([
                'work_order_start_date' => ['required', 'date'],
                'work_order_end_date' => ['required', 'date', 'after_or_equal:work_order_start_date'],
                'work_order_end_time' => ['required', 'date_format:H:i:s'],
                'work_order_representatives' => ['required', 'array', 'min:1', 'max:10'],
                'work_order_representatives.*' => ['required', 'string', 'max:255', 'distinct:ignore_case'],
                'work_order_files' => ['required', 'array', 'min:1', 'max:5'],
                'work_order_files.*' => ['required', 'file', 'extensions:jpeg,png,jpg,webp,pdf,doc,docx,xls,xlsx,csv', 'max:20480'],
            ], [
                'work_order_start_date.required' => 'Select the Work Order start date.',
                'work_order_end_date.required' => 'Select the Work Order end date.',
                'work_order_end_date.after_or_equal' => 'The Work Order end date must be on or after the start date.',
                'work_order_representatives.required' => 'Add at least one Work Order representative.',
                'work_order_representatives.max' => 'You may add up to ten Work Order representatives.',
                'work_order_representatives.*.required' => 'Each Work Order representative must have a name.',
                'work_order_representatives.*.distinct' => 'Each Work Order representative must be unique.',
                'work_order_files.required' => 'Please upload at least one Work Order file.',
                'work_order_files.*.extensions' => 'Work Order attachments must be image files (JPG, PNG, WEBP), PDF, Word (.doc, .docx), or Excel (.xls, .xlsx, .csv).',
                'work_order_files.*.max' => 'Each Work Order attachment may not exceed 20 MB.',
            ]);

            // Always compare against the persisted start timestamp, not the
            // editable request payload. Completion requires a full minute.
            $workOrderStartDate = $propertyRequest->work_order_start_date?->format('Y-m-d')
                ?? $validated['work_order_start_date'];
            $workOrderStartTime = $propertyRequest->work_order_start_time
                ?: ($request->filled('work_order_start_time') ? $request->input('work_order_start_time') : null);

            if ($workOrderStartTime) {
                $workOrderStartAt = Carbon::parse($workOrderStartDate.' '.$workOrderStartTime);
                $workOrderEndAt = Carbon::parse($validated['work_order_end_date'].' '.$validated['work_order_end_time']);
                $earliestWorkOrderCompletion = $workOrderStartAt->copy()->addMinute();

                if ($workOrderEndAt->lt($earliestWorkOrderCompletion) || $completionNow->lt($earliestWorkOrderCompletion)) {
                    throw ValidationException::withMessages([
                        'work_order_end_time' => 'The Work Order cannot be completed at the same time or before its saved start date and time. Please do the task first.',
                    ]);
                }
            } else {
                // Compatibility fallback for older records created before Work
                // Order start times were stored.
                $workOrderStartTime = $propertyRequest->inspection_end_time ?: $completionNow->format('H:i:s');
            }

            $this->storeWorkflowFiles($propertyRequest, $validated['work_order_files'], 'work_order');
            $workOrderRepresentatives = array_values(array_map(
                fn (string $representative) => trim($representative),
                $validated['work_order_representatives']
            ));

            $now = $completionNow;
            $nowTime = $now->format('H:i:s');

            $workOrderEndTime = $validated['work_order_end_time'];

            $completedAt = $now;
            try {
                $completedAt = \Illuminate\Support\Carbon::parse("{$validated['work_order_end_date']} {$workOrderEndTime}");
            } catch (\Throwable) {
                $completedAt = $now;
            }

            $propertyRequest->update([
                'work_order_start_date' => $workOrderStartDate,
                'work_order_end_date' => $validated['work_order_end_date'],
                'work_order_start_time' => $workOrderStartTime,
                'work_order_end_time' => $workOrderEndTime,
                'work_order_representatives' => $workOrderRepresentatives,
                'work_order_completed_at' => $completedAt,
                'status' => 'on_going',
            ]);

            AuditLog::record(
                'work_order_completed',
                "{$propertyRequest->reference_no} Work Order attachments were uploaded and completed by Dial-A {$request->user()->name}.",
                $propertyRequest,
                [
                    'work_order_start_date' => $propertyRequest->work_order_start_date?->format('Y-m-d'),
                    'work_order_end_date' => $propertyRequest->work_order_end_date?->format('Y-m-d'),
                    'work_order_start_time' => $propertyRequest->work_order_start_time,
                    'work_order_end_time' => $propertyRequest->work_order_end_time,
                    'representatives' => $workOrderRepresentatives,
                ]
            );

            return redirect()->route('requests.show', $propertyRequest)->withFragment('work-order')
                ->with('status', 'Work Order completed. The Service Report step is now available for Dial-A.');
        });
    }

    public function startServiceReport(Request $request, PropertyRequest $propertyRequest)
    {
        return DB::transaction(function () use ($request, $propertyRequest) {
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless($request->user()->isDialA(), 403, 'Only Dial-A is authorized to upload Service Report attachments.');

            if (! $propertyRequest->work_order_completed_at) {
                throw ValidationException::withMessages(['service_report' => 'Complete the Work Order before initiating a Service Report.']);
            }

            if ($propertyRequest->service_report_completed_at) {
                throw ValidationException::withMessages(['service_report' => 'The Service Report has already been completed.']);
            }

            $propertyRequest->update([
                'service_report_date' => $propertyRequest->service_report_date ?: today()->toDateString(),
                'status' => 'on_going',
            ]);

            AuditLog::record(
                'service_report_ongoing',
                "{$propertyRequest->reference_no} Service Report file upload initiated and marked as On-going by Dial-A {$request->user()->name}.",
                $propertyRequest,
                [
                    'service_report_date' => $propertyRequest->service_report_date?->format('Y-m-d'),
                ]
            );

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'status' => $propertyRequest->status,
                    'service_report_date' => $propertyRequest->service_report_date?->format('Y-m-d'),
                    'message' => 'Service Report file upload initiated. Request is On-going.',
                ]);
            }

            return redirect()->route('requests.show', $propertyRequest)->withFragment('service-report')
                ->with('status', 'Service Report marked as On-going.');
        });
    }

    public function uploadServiceReport(Request $request, PropertyRequest $propertyRequest)
    {
        return DB::transaction(function () use ($request, $propertyRequest) {
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless($request->user()->isDialA(), 403, 'Only Dial-A is strictly authorized to upload Service Report attachments.');

            if (! $propertyRequest->work_order_completed_at) {
                throw ValidationException::withMessages(['service_report' => 'Complete the Work Order before submitting a Service Report.']);
            }

            if ($propertyRequest->status === 'completed' && $propertyRequest->completed_at) {
                throw ValidationException::withMessages(['service_report' => 'The request has already been completed.']);
            }

            $validated = $request->validate([
                'service_report_files' => ['required', 'array', 'min:1', 'max:5'],
                'service_report_files.*' => ['required', 'file', 'extensions:jpeg,png,jpg,webp,pdf,doc,docx,xls,xlsx,csv', 'max:20480'],
            ], [
                'service_report_files.required' => 'Please upload at least one Service Report file.',
                'service_report_files.*.extensions' => 'Service Report attachments must be image files (JPG, PNG, WEBP), PDF, Word (.doc, .docx), or Excel (.xls, .xlsx, .csv).',
                'service_report_files.*.max' => 'Each Service Report attachment may not exceed 20 MB.',
            ]);

            $this->storeWorkflowFiles($propertyRequest, $validated['service_report_files'], 'service_report');
            $propertyRequest->update([
                'service_report_date' => $propertyRequest->service_report_date ?: today()->toDateString(),
                'service_report_completed_at' => now(),
                'completion_notified_at' => now(),
            ]);

            AuditLog::record(
                'service_report_uploaded',
                "{$propertyRequest->reference_no} Service Report attachments were uploaded by Dial-A {$request->user()->name}.",
                $propertyRequest
            );

            return redirect()->route('requests.show', $propertyRequest)->withFragment('service-report')
                ->with('status', 'Service Report attachments uploaded. Please confirm Done Service Report to complete this request.');
        });
    }

    public function completeServiceReport(Request $request, PropertyRequest $propertyRequest)
    {
        return DB::transaction(function () use ($request, $propertyRequest) {
            // Reload inside the lock: two submissions may have bound the same unfinished request.
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless($request->user()->isDialA(), 403, 'Only Dial-A is strictly authorized to upload Service Report attachments.');

            if (! $propertyRequest->work_order_completed_at) {
                throw ValidationException::withMessages(['service_report' => 'Complete the Work Order before submitting a Service Report.']);
            }

            if ($propertyRequest->service_report_completed_at && $propertyRequest->completed_at && $propertyRequest->status === 'completed') {
                throw ValidationException::withMessages(['service_report' => 'The Service Report has already been completed.']);
            }

            if ($request->hasFile('service_report_files')) {
                $validated = $request->validate([
                    'service_report_files' => ['required', 'array', 'min:1', 'max:5'],
                    'service_report_files.*' => ['required', 'file', 'extensions:jpeg,png,jpg,webp,pdf,doc,docx,xls,xlsx,csv', 'max:20480'],
                ], [
                    'service_report_files.required' => 'Please upload at least one Service Report file.',
                    'service_report_files.*.extensions' => 'Service Report attachments must be image files (JPG, PNG, WEBP), PDF, Word (.doc, .docx), or Excel (.xls, .xlsx, .csv).',
                    'service_report_files.*.max' => 'Each Service Report attachment may not exceed 20 MB.',
                ]);

                $this->storeWorkflowFiles($propertyRequest, $validated['service_report_files'], 'service_report');
            } elseif ($propertyRequest->serviceReportFiles()->doesntExist()) {
                throw ValidationException::withMessages(['service_report' => 'Please upload at least one Service Report file.']);
            }

            $propertyRequest->update([
                'service_report_date' => $propertyRequest->service_report_date ?: today()->toDateString(),
                'service_report_completed_at' => $propertyRequest->service_report_completed_at ?: now(),
                'completed_at' => now(),
                'status' => 'completed',
            ]);

            AuditLog::record(
                'service_report_completed',
                "{$propertyRequest->reference_no} Service Report was completed by Dial-A {$request->user()->name}.",
                $propertyRequest
            );

            AuditLog::record(
                'request_completed',
                "{$propertyRequest->reference_no} was reviewed, confirmed, and officially marked completed by Dial-A {$request->user()->name}.",
                $propertyRequest
            );

            return redirect()->route('requests.show', $propertyRequest)->withFragment('service-report')
                ->with('status', 'Service Report completed and request marked as Completed.');
        });
    }

    public function notifyDialLead(Request $request, PropertyRequest $propertyRequest)
    {
        return DB::transaction(function () use ($request, $propertyRequest) {
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless($request->user()->isDialA(), 403, 'Only Dial-A can notify.');

            if (! $propertyRequest->service_report_completed_at) {
                throw ValidationException::withMessages([
                    'notification' => 'The Service Report must be completed before notification.',
                ]);
            }

            if ($propertyRequest->status === 'completed' || $propertyRequest->completed_at) {
                throw ValidationException::withMessages([
                    'notification' => 'This request has already been completed.',
                ]);
            }

            $propertyRequest->update([
                'completion_notified_at' => now(),
            ]);

            AuditLog::record(
                'dial_lead_notified',
                "{$propertyRequest->reference_no}: Dial-A {$request->user()->name} confirmed ready for completion.",
                $propertyRequest
            );

            return back()->with('status', "Notification recorded. Request is ready to be finished.");
        });
    }

    public function finishRequest(Request $request, PropertyRequest $propertyRequest)
    {
        return DB::transaction(function () use ($request, $propertyRequest) {
            // Reload inside the lock: two submissions may have bound the same unfinished request.
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless($request->user()->isDialA() && $propertyRequest->isAssignedTo($request->user()), 403, 'Only Dial-A is authorized to finish and complete a request.');

            if (! $propertyRequest->service_report_completed_at && $propertyRequest->serviceReportFiles()->doesntExist()) {
                throw ValidationException::withMessages([
                    'completion' => 'The Service Report must be completed before the request can be finished.',
                ]);
            }

            if ($propertyRequest->status === 'completed' && $propertyRequest->completed_at) {
                return back()->with('status', 'Request successfully confirmed and marked as Completed.');
            }

            $propertyRequest->update([
                'service_report_completed_at' => $propertyRequest->service_report_completed_at ?: now(),
                'status' => 'completed',
                'completed_at' => now(),
            ]);

            AuditLog::record(
                'request_completed',
                "{$propertyRequest->reference_no} was reviewed, confirmed, and officially marked completed by Dial-A {$request->user()->name}.",
                $propertyRequest
            );

            return back()->with('status', 'Request successfully confirmed and marked as Completed.');
        });
    }

    public function undoWorkflowStage(Request $request, PropertyRequest $propertyRequest, string $stage)
    {
        return DB::transaction(function () use ($request, $propertyRequest, $stage) {
            $propertyRequest = PropertyRequest::whereKey($propertyRequest->id)->lockForUpdate()->firstOrFail();
            $this->ensureDialAWorkflow($propertyRequest);

            abort_unless(
                $request->user()->isAdmin(),
                403,
                'Only an administrator is authorized to undo workflow steps.'
            );

            $request->validate([
                'current_password' => ['required', 'current_password'],
            ], [
                'current_password.required' => 'Your administrator password is required to undo a workflow step.',
                'current_password.current_password' => 'The password you entered is incorrect.',
            ]);

            $stages = [
                'inspection' => [
                    'label' => 'Inspection',
                    'completed' => (bool) $propertyRequest->inspection_completed_at || $propertyRequest->status === 'completed',
                    'updates' => [
                        'inspection_completed_at' => null,
                        'work_order_completed_at' => null,
                        'service_report_completed_at' => null,
                        'completion_notified_at' => null,
                        'completed_at' => null,
                        'status' => 'pending',
                    ],
                    'reopened' => ['Inspection', 'Work Order', 'Service Report', 'Request Completion'],
                ],
                'work-order' => [
                    'label' => 'Work Order',
                    'completed' => (bool) $propertyRequest->work_order_completed_at || $propertyRequest->status === 'completed',
                    'updates' => [
                        'work_order_completed_at' => null,
                        'service_report_completed_at' => null,
                        'completion_notified_at' => null,
                        'completed_at' => null,
                        'status' => 'on_going',
                    ],
                    'reopened' => ['Work Order', 'Service Report', 'Request Completion'],
                ],
                'service-report' => [
                    'label' => 'Service Report',
                    'completed' => (bool) $propertyRequest->service_report_completed_at || $propertyRequest->status === 'completed',
                    'updates' => [
                        'service_report_completed_at' => null,
                        'completion_notified_at' => null,
                        'completed_at' => null,
                        'status' => 'on_going',
                    ],
                    'reopened' => ['Service Report', 'Request Completion'],
                ],
                'completion' => [
                    'label' => 'Request Completion',
                    'completed' => (bool) $propertyRequest->completed_at || $propertyRequest->status === 'completed',
                    'updates' => [
                        'completed_at' => null,
                        'status' => 'on_going',
                    ],
                    'reopened' => ['Request Completion'],
                ],
            ];

            abort_unless(isset($stages[$stage]), 404);
            $selectedStage = $stages[$stage];

            if (! $selectedStage['completed']) {
                throw ValidationException::withMessages([
                    'workflow' => "The {$selectedStage['label']} step is not completed and cannot be undone.",
                ]);
            }

            $previousState = [
                'status' => $propertyRequest->status,
                'inspection_completed_at' => $propertyRequest->inspection_completed_at?->toIso8601String(),
                'work_order_completed_at' => $propertyRequest->work_order_completed_at?->toIso8601String(),
                'service_report_completed_at' => $propertyRequest->service_report_completed_at?->toIso8601String(),
                'completed_at' => $propertyRequest->completed_at?->toIso8601String(),
            ];

            DB::transaction(function () use ($request, $propertyRequest, $selectedStage, $stage, $previousState) {
                $propertyRequest->update($selectedStage['updates']);

                AuditLog::record(
                    'workflow_step_undone',
                    "{$propertyRequest->reference_no} {$selectedStage['label']} was undone by administrator {$request->user()->name}.",
                    $propertyRequest,
                    [
                        'stage' => $stage,
                        'reopened_stages' => $selectedStage['reopened'],
                        'uploaded_files_retained' => true,
                        'previous_state' => $previousState,
                    ]
                );
            });

            return back()->with(
                'status',
                "{$selectedStage['label']} was undone. Uploaded files and entered details were kept."
            );
        });
    }

    public function attachment(Request $request, RequestAttachment $attachment)
    {
        $this->ensureCanView($request, $attachment->propertyRequest);
        abort_unless(Storage::exists($attachment->path), 404);

        if (! $attachment->isImage()) {
            return response()->download(
                Storage::path($attachment->path),
                $attachment->original_name,
                ['Content-Type' => $attachment->mime_type]
            );
        }

        return response()->file(Storage::path($attachment->path), [
            'Content-Type' => $attachment->mime_type,
            'Content-Disposition' => 'inline; filename="'.addslashes($attachment->original_name).'"',
        ]);
    }

    public function destroyRequest(Request $request, PropertyRequest $propertyRequest)
    {
        abort_unless(
            $request->user()->isAdmin(),
            403,
            'Only an administrator is authorized to delete requests.'
        );

        $request->validate([
            'current_password' => ['required', 'current_password'],
        ], [
            'current_password.required' => 'Your administrator password is required to delete a request.',
            'current_password.current_password' => 'The password you entered is incorrect.',
        ]);

        $referenceNo = $propertyRequest->reference_no;
        $attachmentPaths = $propertyRequest->attachments()->pluck('path')->all();
        $deletedCounts = [
            'attachments' => count($attachmentPaths),
        ];

        DB::transaction(function () use ($propertyRequest) {
            AuditLog::where('subject_type', 'PropertyRequest')
                ->where('subject_id', $propertyRequest->id)
                ->delete();

            $propertyRequest->notifications()->delete();
            $propertyRequest->delete();
        });

        if ($attachmentPaths !== []) {
            Storage::delete($attachmentPaths);
        }

        AuditLog::record(
            'request_deleted',
            "{$referenceNo} was permanently deleted by administrator {$request->user()->name}.",
            null,
            [
                'reference_no' => $referenceNo,
                'attachments_removed' => $deletedCounts['attachments'],
            ]
        );

        return redirect()->route('requests.index')->with(
            'status',
            "Request {$referenceNo} and {$deletedCounts['attachments']} attachment(s) were permanently deleted."
        );
    }

    private function ensureCanView(Request $request, PropertyRequest $propertyRequest): void
    {
        abort_if($request->user()->isDialA() && $propertyRequest->isAwaitingPmReview(), 403, 'This request is awaiting PM review.');
        if ($request->user()->isDealer() && $propertyRequest->submitted_by !== $request->user()->id) {
            abort(403);
        }
    }

    private function ensureDialAWorkflow(PropertyRequest $propertyRequest): void
    {
        abort_unless($propertyRequest->assignment_type === 'dial_a', 403, 'The PM team must review and assign this request to Dial-A before work can begin.');
    }

    private function ensureAssignedSupport(Request $request, PropertyRequest $propertyRequest): void
    {
        $this->ensureDialAWorkflow($propertyRequest);
        abort_unless($propertyRequest->isAssignedTo($request->user()), 403, 'Only the assigned Dial-A user may update this workflow.');
    }

    /** @param array<int, UploadedFile> $files */
    private function storeWorkflowFiles(PropertyRequest $propertyRequest, array $files, string $category): void
    {
        foreach ($files as $file) {
            $path = $file->store('request-attachments/'.$category);
            $propertyRequest->attachments()->create([
                'category' => $category,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $file->getSize(),
            ]);
        }
    }
}
