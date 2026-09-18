{{-- Shared admin password modal – included once on the request show page --}}
<div class="admin-password-overlay" id="adminPasswordOverlay" style="display:none" role="dialog" aria-modal="true" aria-labelledby="adminPasswordTitle">
    <div class="admin-password-modal">
        <h2 id="adminPasswordTitle"></h2>
        <p id="adminPasswordDescription"></p>

        @if($errors->has('current_password'))
            <div class="alert alert-error" style="color:#b91c1c;background:#fef2f2;border:1px solid #fecaca;margin-bottom:14px;">
                {{ $errors->first('current_password') }}
            </div>
        @endif

        <div class="form-field">
            <label for="adminPasswordInput">Administrator password</label>
            <input id="adminPasswordInput" type="password" autocomplete="current-password" required>
        </div>
        <div class="admin-password-actions">
            <button class="button button-light" type="button" id="adminPasswordCancel">Cancel</button>
            <button class="button button-danger" type="button" id="adminPasswordConfirm">Confirm</button>
        </div>
    </div>
</div>
