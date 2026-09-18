@extends('layouts.app')

@section('title', 'Submit Request')

@section('content')

<form class="request-form panel form-panel" method="POST" action="{{ route('requests.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-section">
        <h2>Submitted By</h2>
        <div class="form-grid two-col">
            <div class="form-field"><label for="name">Name <em>*</em></label><input id="name" name="name" value="{{ old('name', auth()->user()->name) }}" required></div>
            <div class="form-field"><label for="designation">Designation <em>*</em></label><input id="designation" name="designation" value="{{ old('designation', auth()->user()->designation) }}" required></div>
        </div>
    </div>

    <div class="form-section">
        <h2>Branch Information</h2>
        <div class="form-grid three-col">
            <div class="readonly-field"><span>BRANCH</span><strong>{{ $branch }}</strong></div>
            <div class="readonly-field"><span>AREA</span><strong>{{ $area }}</strong></div>
            <div class="form-field"><label>Request Date <em>*</em></label><input value="{{ today()->format('d/m/Y') }}" readonly></div>
        </div>
    </div>

    <div class="form-section">
        <h2>Dealer Information</h2>
        <div class="form-grid">
            <div class="form-field">
                <label for="dealer_select">Select Branch: {{ $branch }}</label>
                <select id="dealer_select" disabled>
                    @foreach($sameCityDealers as $d)
                        <option value="{{ $d->id }}" @selected($currentDealer->id == $d->id)>
                            {{ $d->brand }} — {{ $d->name }}
                        </option>
                    @endforeach
                </select>
                <input type="hidden" name="dealer_id" value="{{ $currentDealer->id }}">
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="form-grid two-col">
            <div class="form-field"><label for="request_type">Type of Inspection Request <em>*</em></label>
                <select id="request_type" name="request_type" required>
                    <option value="">Select request type</option>
                    @foreach($types as $type)<option value="{{ $type }}" data-suggestion="{{ $remarkSuggestions[$type] ?? '' }}" @selected(old('request_type') === $type)>{{ $type }}</option>@endforeach
                </select>
            </div>
            <div class="form-field"><label for="priority">Request Priority <em>*</em></label>
                <select id="priority" name="priority" required>
                    @foreach(['regular' => 'Regular', 'urgent' => 'Urgent'] as $value => $label)<option value="{{ $value }}" @selected(old('priority', 'regular') === $value)>{{ $label }}</option>@endforeach
                </select>
            </div>
        </div>
    </div>

    <div class="form-section">
        <div class="form-field">
            <label for="description">Description <em>*</em> <span style="font-size: 11px; font-weight: normal; color: #64748b;">(Suggested remarks)</span></label>
            <textarea id="description" name="description" rows="7" data-request-description required>{{ old('description') }}</textarea>
        </div>
    </div>

    <div class="form-section no-border">
        <div class="form-field"><label for="attachments">Attachments <span class="attachment-limit">Optional · Up to 5 files</span></label>
            <div class="file-drop">
                <input id="attachments" name="attachments[]" type="file" accept=".jpg,.jpeg,.png,.webp,image/*,.pdf,application/pdf,.doc,.docx,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,.xls,.xlsx,.csv,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" multiple data-file-input data-max-files="5">
            </div>
            <ul class="selected-file-list" data-file-list></ul>
            <div class="attachment-preview-grid" data-attachment-preview></div>
        </div>
    </div>

    <div class="form-actions"><a class="button button-light" href="{{ route('requests.index') }}">Cancel</a><button class="button button-primary" type="submit">Submit Request</button></div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('attachments');
    const previewGrid = document.querySelector('[data-attachment-preview]');
    if (!fileInput || !previewGrid) return;

    function formatSize(bytes) {
        return bytes >= 1048576
            ? (bytes / 1048576).toFixed(1) + ' MB'
            : Math.max(1, Math.round(bytes / 1024)) + ' KB';
    }

    fileInput.addEventListener('change', function () {
        previewGrid.replaceChildren();
        const files = Array.from(fileInput.files);
        if (!files.length) return;

        files.forEach(function (file) {
            const card = document.createElement('div');
            card.className = 'attachment-preview-card';

            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.alt = file.name;
                    card.prepend(img);
                };
                reader.readAsDataURL(file);
            } else {
                const icon = document.createElement('span');
                icon.className = 'attachment-preview-icon';
                icon.textContent = file.name.split('.').pop().toUpperCase();
                card.append(icon);
            }

            const info = document.createElement('span');
            info.className = 'attachment-preview-name';
            info.textContent = file.name;

            const size = document.createElement('small');
            size.textContent = formatSize(file.size);

            card.append(info, size);
            previewGrid.append(card);
        });
    });
});
</script>
@endsection
