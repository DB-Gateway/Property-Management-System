<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Dealer;
use App\Services\DealerSpreadsheet;
use App\Support\DealerData;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class DealerDirectoryController extends Controller
{
    public function index(Request $request)
    {
        abort_if($request->user()->isDealer(), 403);

        [$selectedCity, $selectedArea, $selectedBrand, $selectedAreaRaw] = $this->selections($request);

        $dealers = $this->directoryQuery($request, $selectedCity, $selectedArea, $selectedBrand)
            ->paginate(15)
            ->withQueryString();

        // Strict dependent query for cities: filtered by area and brand (if selected)
        $cities = Dealer::query()
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->when($selectedArea !== '', fn ($query) => $query->where('area', $selectedArea))
            ->when($selectedBrand !== '', fn ($query) => $query->where('brand', $selectedBrand))
            ->distinct()
            ->orderBy('city')
            ->pluck('city');

        $areas = Dealer::query()
            ->when($selectedCity !== '', fn ($query) => $query->where('city', $selectedCity))
            ->when($selectedBrand !== '', fn ($query) => $query->where('brand', $selectedBrand))
            ->distinct()
            ->orderBy('area')
            ->pluck('area');

        $brands = Dealer::query()
            ->when($selectedCity !== '', fn ($query) => $query->where('city', $selectedCity))
            ->when($selectedArea !== '', fn ($query) => $query->where('area', $selectedArea))
            ->distinct()
            ->orderBy('brand')
            ->pluck('brand');

        $directoryMatrix = Dealer::query()
            ->select('city', 'area', 'brand')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->distinct()
            ->orderBy('city')
            ->get();

        $areasWithCities = Dealer::query()
            ->select('city', 'area')
            ->whereNotNull('city')
            ->where('city', '!=', '')
            ->when($selectedBrand !== '', fn ($query) => $query->where('brand', $selectedBrand))
            ->distinct()
            ->orderBy('area')
            ->orderBy('city')
            ->get()
            ->groupBy('area')
            ->map(function ($group) {
                return $group->pluck('city')->unique()->sort()->values();
            })
            ->sortKeys();

        return view('dealers.index', compact(
            'dealers', 'cities', 'areas', 'brands', 'directoryMatrix',
            'areasWithCities', 'selectedCity', 'selectedArea', 'selectedAreaRaw', 'selectedBrand'
        ));
    }

    public function create(Request $request)
    {
        abort_unless($request->user()->isManager(), 403);

        return view('dealers.create', $this->formData(new Dealer([
            'source_no' => (int) Dealer::max('source_no') + 1,
        ])));
    }

    public function store(Request $request)
    {
        abort_unless($request->user()->isManager(), 403);
        $data = $this->validatedDealer($request);

        try {
            DB::transaction(function () use ($data) {
                $dealer = Dealer::create($data);
                AuditLog::record('dealer_created', "Created dealer {$dealer->name}.", $dealer);
            });
        } catch (UniqueConstraintViolationException $exception) {
            throw ValidationException::withMessages(['source_no' => 'This dealer number is already in use. Choose another number.']);
        }

        return to_route('dealers.index')->with('status', 'Dealer created successfully.');
    }

    public function edit(Request $request, Dealer $dealer)
    {
        abort_unless($request->user()->canManageDealers(), 403);

        return view('dealers.edit', $this->formData($dealer));
    }

    public function update(Request $request, Dealer $dealer)
    {
        abort_unless($request->user()->canManageDealers(), 403);
        $data = $this->validatedDealer($request, $dealer);

        try {
            DB::transaction(function () use ($dealer, $data) {
                $dealer->update($data);
                AuditLog::record('dealer_updated', "Updated dealer {$dealer->name}.", $dealer);
            });
        } catch (UniqueConstraintViolationException $exception) {
            throw ValidationException::withMessages(['source_no' => 'This dealer number is already in use. Choose another number.']);
        }

        return to_route('dealers.index')->with('status', 'Dealer updated successfully.');
    }

    public function importForm(Request $request)
    {
        abort_unless($request->user()->isManager(), 403);

        return view('dealers.import', ['headers' => DealerData::HEADERS]);
    }

    public function import(Request $request, DealerSpreadsheet $spreadsheet)
    {
        abort_unless($request->user()->isManager(), 403);
        $request->validate([
            'file' => ['required', 'file', 'extensions:xlsx', 'mimes:xlsx', 'max:5120'],
            'update_existing' => ['sometimes', 'boolean'],
        ]);

        try {
            $counts = $spreadsheet->import($request->file('file')->getRealPath(), $request->boolean('update_existing'));
        } catch (UniqueConstraintViolationException $exception) {
            throw ValidationException::withMessages(['file' => 'A dealer number was added by another user during import. No changes were saved. Please import again.']);
        }

        return to_route('dealers.index')->with('status', "Import complete: {$counts['created']} created, {$counts['updated']} updated, {$counts['skipped']} skipped.");
    }

    public function export(Request $request, DealerSpreadsheet $spreadsheet)
    {
        abort_if($request->user()->isDealer(), 403);
        $dealers = $this->directoryQuery($request, ...$this->selections($request))->cursor();

        return $this->workbookResponse($spreadsheet->export($dealers), 'Dealer-Directory-'.now()->format('Ymd-His').'.xlsx');
    }

    public function template(Request $request, DealerSpreadsheet $spreadsheet)
    {
        abort_unless($request->user()->isManager(), 403);

        return $this->workbookResponse($spreadsheet->export([]), 'Dealer-Import-Template.xlsx');
    }

    public function printable(Request $request)
    {
        abort_if($request->user()->isDealer(), 403);

        [$selectedCity, $selectedArea, $selectedBrand, $selectedAreaRaw] = $this->selections($request);

        $dealers = $this->directoryQuery($request, $selectedCity, $selectedArea, $selectedBrand, $selectedAreaRaw)->get();

        $activeFilters = array_filter([
            'Search' => $request->string('search')->trim()->value(),
            'Branch (City)' => $selectedCity,
            'Area' => $selectedArea,
            'Brand' => $selectedBrand,
        ], fn ($val) => $val !== '');

        return view('dealers.print', compact(
            'dealers',
            'selectedCity',
            'selectedArea',
            'selectedAreaRaw',
            'selectedBrand',
            'activeFilters'
        ));
    }

    private function workbookResponse(string $workbook, string $filename)
    {
        return response($workbook, 200, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
            'Content-Length' => (string) strlen($workbook),
            'Cache-Control' => 'private, no-store, max-age=0',
        ]);
    }

    private function validatedDealer(Request $request, ?Dealer $dealer = null): array
    {
        $rules = DealerData::rules();
        $rules['source_no'][] = Rule::unique('dealers', 'source_no')->ignore($dealer?->id);

        return $request->validate($rules, [], DealerData::HEADERS);
    }

    private function formData(Dealer $dealer): array
    {
        return [
            'dealer' => $dealer,
            'cities' => Dealer::whereNotNull('city')->distinct()->orderBy('city')->pluck('city'),
            'areas' => Dealer::distinct()->orderBy('area')->pluck('area'),
            'brands' => Dealer::distinct()->orderBy('brand')->pluck('brand'),
        ];
    }

    private function selections(Request $request): array
    {
        $selectedCity = $request->string('city')->trim()->value();
        $selectedAreaRaw = $request->string('area')->trim()->value();
        $selectedArea = $selectedAreaRaw;
        $selectedBrand = $request->string('brand')->trim()->value() ?: $request->string('dealer')->trim()->value();

        if (str_contains($selectedAreaRaw, ' - ')) {
            [$part1, $part2] = explode(' - ', $selectedAreaRaw, 2);
            $part1 = trim($part1);
            $part2 = trim($part2);
            $allAreas = Dealer::distinct()->pluck('area');
            if ($allAreas->contains($part2)) {
                $selectedCity = $part1;
                $selectedArea = $part2;
            } elseif ($allAreas->contains($part1)) {
                $selectedArea = $part1;
                $selectedCity = $part2;
            } else {
                $selectedCity = $part1;
                $selectedArea = $part2;
            }
        } elseif ($selectedAreaRaw !== '') {
            $allCities = Dealer::whereNotNull('city')->where('city', '!=', '')->distinct()->pluck('city');
            if ($allCities->contains($selectedAreaRaw)) {
                $selectedCity = $selectedAreaRaw;
                $selectedArea = '';
            }
        }

        // Sanitize selections if any mutually incompatible pairs are passed in request
        if ($selectedCity !== '' && $selectedArea !== '') {
            $isCityInArea = Dealer::query()
                ->where('city', $selectedCity)
                ->where('area', $selectedArea)
                ->exists();
            if (! $isCityInArea) {
                $selectedArea = '';
            }
        }

        if ($selectedCity !== '' && $selectedBrand !== '') {
            $isBrandInCity = Dealer::query()
                ->where('city', $selectedCity)
                ->where('brand', $selectedBrand)
                ->exists();
            if (! $isBrandInCity) {
                $selectedBrand = '';
            }
        }

        if ($selectedArea !== '' && $selectedBrand !== '') {
            $isBrandInArea = Dealer::query()
                ->where('area', $selectedArea)
                ->where('brand', $selectedBrand)
                ->exists();
            if (! $isBrandInArea) {
                $selectedBrand = '';
            }
        }

        return [$selectedCity, $selectedArea, $selectedBrand, $selectedAreaRaw];
    }

    private function directoryQuery(Request $request, string $selectedCity, string $selectedArea, string $selectedBrand, string $selectedAreaRaw = ''): Builder
    {
        return Dealer::query()
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->trim();
                $query->where(function ($nested) use ($search) {
                    $nested->where('name', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        ->orWhere('city', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('point_person_1', 'like', "%{$search}%")
                        ->orWhere('point_person_2', 'like', "%{$search}%");
                });
            })
            ->when($selectedCity !== '', fn ($query) => $query->where('city', $selectedCity))
            ->when($selectedArea !== '', fn ($query) => $query->where('area', $selectedArea))
            ->when($selectedBrand !== '', fn ($query) => $query->where('brand', $selectedBrand))
            ->orderBy('source_no');
    }
}
