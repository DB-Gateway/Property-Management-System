@php
    $representativeValues = collect($values ?? [])->filter(fn ($value) => trim((string) $value) !== '')->values();
    $representativeMaximum = $max ?? 10;
    $representativeInputId = $inputId ?? 'representative';
@endphp

<div class="form-field representative-field" data-representative-group data-max-representatives="{{ $representativeMaximum }}" data-input-name="{{ $inputName }}[]" data-input-id="{{ $representativeInputId }}">
    <label>{{ $label ?? 'Representatives' }} <em>*</em></label>
    <div class="representative-list" data-representative-list>
        @foreach($representativeValues as $representative)
            <div class="representative-row" data-representative-row>
                <input id="{{ $representativeInputId }}_{{ $loop->iteration }}" name="{{ $inputName }}[]" type="text" value="{{ $representative }}" placeholder="Enter representative name" maxlength="255" required>
                <button class="representative-remove" type="button" data-remove-representative aria-label="Remove representative" @disabled($representativeValues->count() === 1)>Remove</button>
            </div>
        @endforeach
    </div>
    <button class="button button-light representative-add" type="button" data-add-representative>+ Add Representative</button>
</div>
