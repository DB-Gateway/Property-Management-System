<div class="form-section">
    <div class="form-grid two-col">
        <div class="form-field">
            <label for="source_no">Dealer No. <em>*</em></label>
            <input id="source_no" name="source_no" type="number" min="1" max="4294967295" step="1" value="{{ old('source_no', $dealer->source_no) }}" required>
        </div>
        <div class="form-field">
            <label for="name">Dealer Name <em>*</em></label>
            <input id="name" name="name" maxlength="255" value="{{ old('name', $dealer->name) }}" placeholder="e.g. Mitsubishi Pasig" required>
        </div>
        <div class="form-field">
            <label for="brand">Brand <em>*</em></label>
            <input id="brand" name="brand" list="dealer-brands" maxlength="255" value="{{ old('brand', $dealer->brand) }}" placeholder="Select or enter a brand" required>
            <datalist id="dealer-brands">@foreach($brands as $brand)<option value="{{ $brand }}">@endforeach</datalist>
        </div>
        <div class="form-field">
            <label for="city">Branch (City)</label>
            <input id="city" name="city" list="dealer-cities" maxlength="100" value="{{ old('city', $dealer->city) }}" placeholder="Select or enter a city">
            <datalist id="dealer-cities">@foreach($cities as $city)<option value="{{ $city }}">@endforeach</datalist>
        </div>
        <div class="form-field">
            <label for="area">Area <em>*</em></label>
            <input id="area" name="area" list="dealer-areas" maxlength="255" value="{{ old('area', $dealer->area) }}" placeholder="Select or enter an area" required>
            <datalist id="dealer-areas">@foreach($areas as $area)<option value="{{ $area }}">@endforeach</datalist>
        </div>
        <div class="form-field dealer-form-wide">
            <label for="address">Address</label>
            <textarea id="address" name="address" rows="3" maxlength="10000" placeholder="Street, barangay, city, and province">{{ old('address', $dealer->address) }}</textarea>
        </div>
    </div>
</div>

<div class="form-section no-border">
    <h2>Contact Information</h2>
    <div class="form-grid two-col">
        <div class="form-field">
            <label for="point_person_1">Point Person 1</label>
            <input id="point_person_1" name="point_person_1" maxlength="255" value="{{ old('point_person_1', $dealer->point_person_1) }}" placeholder="Name and designation">
        </div>
        <div class="form-field">
            <label for="contact_1">Contact 1</label>
            <input id="contact_1" name="contact_1" type="tel" maxlength="50" value="{{ old('contact_1', $dealer->contact_1) }}" placeholder="e.g. 0917 123 4567">
        </div>
        <div class="form-field">
            <label for="point_person_2">Point Person 2</label>
            <input id="point_person_2" name="point_person_2" maxlength="255" value="{{ old('point_person_2', $dealer->point_person_2) }}" placeholder="Name and designation">
        </div>
        <div class="form-field">
            <label for="contact_2">Contact 2</label>
            <input id="contact_2" name="contact_2" type="tel" maxlength="50" value="{{ old('contact_2', $dealer->contact_2) }}" placeholder="e.g. +63 917 123 4567">
        </div>
    </div>
</div>
