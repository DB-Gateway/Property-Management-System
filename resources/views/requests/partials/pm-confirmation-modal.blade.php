<dialog id="pmConfirmationDialog" class="pm-confirmation-dialog"
        data-quick-confirm="{{ \App\Support\PmActionConfirmation::available(request()) ? '1' : '0' }}"
        aria-labelledby="pmConfirmationTitle" aria-describedby="pmConfirmationSummary">
    <form id="pmConfirmationForm">
        <h2 id="pmConfirmationTitle">Confirm Action</h2>
        <p id="pmConfirmationSummary"></p>
        <p class="assignment-form-help">Signed in as {{ auth()->user()->name }} ({{ auth()->user()->role_label }}).</p>
        <div id="pmConfirmationError" class="alert alert-error" role="alert" hidden></div>
        <div id="pmPasswordFields">
            <div class="form-field">
                <label for="pmConfirmationPassword">Your {{ auth()->user()->role_label }} password</label>
                <input id="pmConfirmationPassword" name="current_password" type="password" autocomplete="current-password">
            </div>
            <label class="pm-remember-option"><input id="pmRememberConfirmation" type="checkbox" checked> <strong>Quick Tap:</strong> Enable one-click confirmation for this sign-in</label>
            <p class="assignment-form-help">Quick Tap saves verification until you sign out, change password, or 12 hours pass. Every action still prompts for confirmation.</p>
        </div>
        <div id="pmQuickConfirmation" hidden>
            <div style="padding:10px 14px; background:#f0fdf4; border:1px solid #bbf7d0; border-radius:8px; margin:12px 0;">
                <strong style="color:#166534; font-size:12px; display:block; margin-bottom:4px;">⚡ Quick Tap (One-Click) Active</strong>
                <p style="margin:0; font-size:13px; color:#14532d;">Your account was verified earlier. Confirm this action with one click.</p>
            </div>
            <button id="pmUsePassword" class="button button-light" type="button" style="font-size:12px; padding:4px 10px;">Use password instead</button>
        </div>
        <div class="admin-password-actions">
            <button id="pmConfirmationCancel" class="button button-light" type="button">Cancel</button>
            <button id="pmConfirmationSubmit" class="button button-primary" type="submit">Confirm</button>
        </div>
    </form>
</dialog>
<script src="{{ asset('js/pm-confirmation.js') }}?v={{ filemtime(public_path('js/pm-confirmation.js')) }}" defer></script>
