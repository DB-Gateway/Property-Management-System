@php
    $stage = $stage ?? 'inspection';
    $dateLocked = $dateLocked ?? false;
@endphp

@if($stage === 'inspection')
    <div class="stage-timing-progress-card" data-stage-timing-flow aria-label="{{ $ariaLabel ?? 'Inspection timing progress' }}">
        <div class="stage-timing-grid">
            {{-- Column 1: Start Date (Dial-A selection) --}}
            <div class="stage-timing-col">
                <label for="inspection_date">Start Date <em>*</em></label>
                @if($dateLocked)
                    <div class="stage-timing-input-wrap">
                        <input type="text" readonly class="timing-locked-input" value="{{ $propertyRequest->inspection_date?->format('Y-m-d') }}">
                    </div>
                    <input type="hidden" id="inspection_date" name="inspection_date" value="{{ $propertyRequest->inspection_date?->format('Y-m-d') }}">
                    <span class="stage-timing-hint-text">Inspection start date has been set</span>
                @else
                    <div class="stage-timing-input-wrap">
                        <input
                            id="inspection_date"
                            name="inspection_date"
                            type="date"
                            value="{{ old('inspection_date', '') }}"
                            data-timing-input="start"
                            required
                        >
                    </div>
                    <span class="stage-timing-hint-text">Select the date inspection starts</span>
                @endif
                <input type="hidden" id="inspection_start_time" name="inspection_start_time" value="{{ substr((string) old('inspection_start_time', $propertyRequest->inspection_start_time ?? ''), 0, 5) }}">
                <input type="hidden" id="inspection_end_date" name="inspection_end_date" value="{{ old('inspection_end_date', $propertyRequest->inspection_end_date?->format('Y-m-d') ?? '') }}">
                <input type="hidden" id="inspection_end_time" name="inspection_end_time" value="{{ substr((string) old('inspection_end_time', $propertyRequest->inspection_end_time ?? ''), 0, 5) }}">
                @error('inspection_date')
                    <small class="stage-timing-error">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
@elseif($stage === 'work_order')
    @php
        $inheritedWoDate = $propertyRequest->work_order_start_date?->format('Y-m-d')
            ?? $propertyRequest->inspection_end_date?->format('Y-m-d')
            ?? $propertyRequest->inspection_date?->format('Y-m-d')
            ?? '';
        $woStartDate = old('work_order_start_date', $inheritedWoDate);
        $inheritedWoTime = $propertyRequest->work_order_start_time
            ?? $propertyRequest->inspection_end_time
            ?? $propertyRequest->inspection_start_time
            ?? '';
        $woStartTime = old('work_order_start_time', $inheritedWoTime);
        $isInherited = (bool) $propertyRequest->inspection_completed_at && ! $propertyRequest->work_order_start_date;
    @endphp

    <div class="stage-timing-progress-card" data-stage-timing-flow aria-label="{{ $ariaLabel ?? 'Work Order date progress' }}">
        <div class="stage-timing-grid">
            {{-- Column 1: Start Date (Dial-A selection) --}}
            <div class="stage-timing-col">
                <label for="work_order_start_date">Work Order Start Date <em>*</em></label>
                @if($dateLocked)
                    <div class="stage-timing-input-wrap">
                        <input type="text" readonly class="timing-locked-input" value="{{ $propertyRequest->work_order_start_date?->format('Y-m-d') }}">
                    </div>
                    <input type="hidden" id="work_order_start_date" name="work_order_start_date" value="{{ $propertyRequest->work_order_start_date?->format('Y-m-d') }}">
                    <span class="stage-timing-hint-text">Work Order start date has been set</span>
                @else
                    <div class="stage-timing-input-wrap">
                        <input
                            id="work_order_start_date"
                            name="work_order_start_date"
                            type="date"
                            value="{{ $woStartDate }}"
                            data-timing-input="start"
                            required
                        >
                    </div>
                    <span class="stage-timing-hint-text">{{ $isInherited ? 'Inherited from completed Inspection' : 'Select the date work order starts' }}</span>
                @endif
                <input type="hidden" id="work_order_start_time" name="work_order_start_time" value="{{ $woStartTime }}">
                <input type="hidden" id="work_order_end_date" name="work_order_end_date" value="{{ old('work_order_end_date', $propertyRequest->work_order_end_date?->format('Y-m-d') ?? '') }}">
                <input type="hidden" id="work_order_end_time" name="work_order_end_time" value="{{ old('work_order_end_time', $propertyRequest->work_order_end_time ?? '') }}">
                @error('work_order_start_date')
                    <small class="stage-timing-error">{{ $message }}</small>
                @enderror
                @error('work_order_start_time')
                    <small class="stage-timing-error">{{ $message }}</small>
                @enderror
            </div>
        </div>
    </div>
@endif
