{{-- Modal for PM Manager to edit request priority status with password confirmation --}}
<div class="admin-password-overlay" id="editPriorityOverlay" style="display:none;" role="dialog" aria-modal="true" aria-labelledby="editPriorityTitle">
    <div class="admin-password-modal" style="max-width: 440px;">
        <h2 id="editPriorityTitle">Edit Priority Status</h2>
        <p id="editPriorityDescription">Update the priority level for <strong id="editPriorityRef">this request</strong>. Confirmation with your PM Manager password is required.</p>

        <div id="editPriorityAlert" class="alert alert-error" style="display:none; color:#b91c1c; background:#fef2f2; border:1px solid #fecaca; margin-bottom:14px; padding:10px 12px; border-radius:7px; font-size:12px;"></div>

        @if($errors->has('current_password') || $errors->has('priority'))
            <div class="alert alert-error" style="color:#b91c1c; background:#fef2f2; border:1px solid #fecaca; margin-bottom:14px; padding:10px 12px; border-radius:7px; font-size:12px;">
                {{ $errors->first('current_password') ?: $errors->first('priority') }}
            </div>
        @endif

        <form id="editPriorityForm" method="POST" action="">
            @csrf
            @method('PATCH')

            <div class="form-field" style="margin-bottom: 16px;">
                <label for="editPrioritySelect">Priority Status <em style="color:#d71938;">*</em></label>
                <select id="editPrioritySelect" name="priority" required style="width: 100%; min-height: 40px; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 7px; font-size: 13px; background:#fff;">
                    <option value="regular">Regular</option>
                    <option value="urgent">Urgent</option>
                </select>
            </div>

            <div class="form-field" style="margin-bottom: 16px;">
                <label for="editPriorityRemarks">Remarks</label>
                <textarea id="editPriorityRemarks" name="remarks" rows="3" placeholder="Enter reason or remarks for priority change (optional)" style="width: 100%; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 7px; font-size: 13px; resize: vertical; box-sizing: border-box;"></textarea>
            </div>

            <div class="form-field" style="margin-bottom: 20px;">
                <label for="editPriorityPasswordInput">PM Manager Password <em style="color:#d71938;">*</em></label>
                <input id="editPriorityPasswordInput" name="current_password" type="password" autocomplete="current-password" placeholder="Enter your password to authorize" required style="width: 100%; min-height: 40px; padding: 8px 12px; border: 1px solid #cbd5e1; border-radius: 7px; font-size: 13px;">
            </div>

            <div class="admin-password-actions">
                <button class="button button-light" type="button" id="editPriorityCancel">Cancel</button>
                <button class="button button-primary" type="submit" id="editPriorityConfirm">Update Priority</button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var overlay = document.getElementById('editPriorityOverlay');
    if (!overlay) return;

    var form = document.getElementById('editPriorityForm');
    var refEl = document.getElementById('editPriorityRef');
    var selectEl = document.getElementById('editPrioritySelect');
    var remarksInput = document.getElementById('editPriorityRemarks');
    var passwordInput = document.getElementById('editPriorityPasswordInput');
    var alertEl = document.getElementById('editPriorityAlert');
    var confirmBtn = document.getElementById('editPriorityConfirm');
    var cancelBtn = document.getElementById('editPriorityCancel');

    function openModal(btn) {
        var ref = btn.dataset.reference || 'this request';
        var priority = btn.dataset.priority || 'regular';
        var remarks = btn.dataset.remarks || '';
        var actionUrl = btn.dataset.url || '';

        refEl.textContent = ref;
        selectEl.value = priority;
        if (remarksInput) remarksInput.value = remarks;
        form.action = actionUrl;
        passwordInput.value = '';
        alertEl.style.display = 'none';
        alertEl.textContent = '';
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Update Priority';

        overlay.style.display = 'flex';
        passwordInput.focus();
    }

    function closeModal() {
        overlay.style.display = 'none';
        passwordInput.value = '';
        alertEl.style.display = 'none';
        alertEl.textContent = '';
    }

    document.querySelectorAll('[data-open-priority-modal]').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            openModal(btn);
        });
    });

    cancelBtn.addEventListener('click', closeModal);

    overlay.addEventListener('click', function (e) {
        if (e.target === overlay) {
            closeModal();
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && overlay.style.display !== 'none') {
            closeModal();
        }
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        if (!passwordInput.value.trim()) {
            passwordInput.focus();
            return;
        }

        confirmBtn.disabled = true;
        confirmBtn.textContent = 'Updating…';
        alertEl.style.display = 'none';
        alertEl.textContent = '';

        var csrfMeta = document.querySelector('meta[name="csrf-token"]');
        var token = csrfMeta ? csrfMeta.getAttribute('content') : (form.querySelector('input[name="_token"]') ? form.querySelector('input[name="_token"]').value : '');

        var formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': token,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(function (res) {
            return res.json().then(function (data) {
                return { status: res.status, ok: res.ok, data: data };
            }).catch(function () {
                return { status: res.status, ok: res.ok, data: {} };
            });
        })
        .then(function (result) {
            if (result.ok && result.data.success) {
                // Success: reload to update UI state, badges, and notification displays
                window.location.reload();
            } else {
                confirmBtn.disabled = false;
                confirmBtn.textContent = 'Update Priority';

                var errorMsg = 'Failed to update priority.';
                if (result.data) {
                    if (result.data.errors && result.data.errors.current_password) {
                        errorMsg = result.data.errors.current_password[0];
                    } else if (result.data.errors && result.data.errors.priority) {
                        errorMsg = result.data.errors.priority[0];
                    } else if (result.data.message) {
                        errorMsg = result.data.message;
                    }
                }
                alertEl.textContent = errorMsg;
                alertEl.style.display = 'block';
                passwordInput.select();
            }
        })
        .catch(function (err) {
            confirmBtn.disabled = false;
            confirmBtn.textContent = 'Update Priority';
            alertEl.textContent = 'An unexpected error occurred. Please try again.';
            alertEl.style.display = 'block';
        });
    });
});
</script>
