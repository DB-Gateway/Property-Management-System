<dialog id="pmConfirmationDialog" class="pm-confirmation-dialog"
        aria-labelledby="pmConfirmationTitle" aria-describedby="pmConfirmationSummary">
    <form id="pmConfirmationForm">
        <h2 id="pmConfirmationTitle">Confirm Action</h2>
        <p id="pmConfirmationSummary"></p>
        <p class="assignment-form-help">Signed in as {{ auth()->user()->name }} ({{ auth()->user()->role_label }}).</p>
        <div id="pmConfirmationError" class="alert alert-error" role="alert" hidden></div>
        <div id="pmPasswordFields">
            <div class="form-field">
                <label for="pmConfirmationPassword">Your {{ auth()->user()->role_label }} password</label>
                <input id="pmConfirmationPassword" name="current_password" type="password" autocomplete="current-password" required>
            </div>
        </div>
        <div class="admin-password-actions">
            <button id="pmConfirmationCancel" class="button button-light" type="button">Cancel</button>
            <button id="pmConfirmationSubmit" class="button button-primary" type="submit">Confirm</button>
        </div>
    </form>
</dialog>
<script src="{{ asset('js/pm-confirmation.js') }}?v={{ filemtime(public_path('js/pm-confirmation.js')) }}" defer></script>
