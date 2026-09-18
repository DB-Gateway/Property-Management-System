@extends('layouts.app')

@section('title', 'Import Dealers')

@section('content')
<div class="page-heading narrow-heading">
    <p class="eyebrow">DEALER DIRECTORY</p>
    <h1>Import Dealers</h1>
    <p>Add or update dealer records from an Excel workbook.</p>
</div>

<form class="panel form-panel" method="POST" action="{{ route('dealers.import.store') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-section">
        <h2>1. Prepare Your Workbook</h2>
        <p class="section-help">Download the blank template, or edit an export from the Dealer Directory.</p>
        <a class="button button-light" href="{{ route('dealers.template') }}">Download Excel Template</a>
        <ul class="dealer-import-help">
            <li>Keep the column headings in row 1 of the first worksheet. Enter one dealer per row, starting at row 2.</li>
            <li>Dealer No., Dealer Name, Area, and Brand are required. Use a unique positive dealer number for each row.</li>
            <li>Keep contact numbers as text to preserve leading zeros and the + sign. Use plain values instead of formulas.</li>
            <li>Upload an .xlsx file up to 5 MB, with a maximum of 5,000 dealer rows.</li>
        </ul>
        <details class="dealer-import-help">
            <summary>View template columns</summary>
            <p>{{ implode(' · ', array_values($headers)) }}</p>
        </details>
    </div>

    <div class="form-section no-border">
        <h2>2. Upload Excel File</h2>
        <div class="form-field">
            <label for="file">Excel Workbook <em>*</em></label>
            <input id="file" name="file" type="file" accept=".xlsx,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" required aria-describedby="dealer-import-note">
        </div>
        <label class="check-label dealer-import-option" for="update_existing">
            <input id="update_existing" name="update_existing" type="checkbox" value="1" @checked(old('update_existing'))>
            <span>Update existing dealers with matching Dealer No.</span>
        </label>
        <p class="dealer-import-help" id="dealer-import-note">Existing dealer numbers are skipped unless this option is selected. When updating, all imported fields replace the saved values, including blank optional fields. New dealer numbers are always added.</p>
        <p class="dealer-import-help">If any row is invalid, no changes are saved. You will see the row numbers to correct.</p>
    </div>

    <div class="form-actions">
        <a class="button button-light" href="{{ route('dealers.index') }}">Cancel</a>
        <button class="button button-primary" type="submit">Import Dealers</button>
    </div>
</form>
@endsection
