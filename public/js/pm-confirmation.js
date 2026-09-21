(() => {
    'use strict';
    const dialog = document.getElementById('pmConfirmationDialog');
    if (!dialog) return;
    const confirmation = document.getElementById('pmConfirmationForm');
    const password = document.getElementById('pmConfirmationPassword');
    const error = document.getElementById('pmConfirmationError');
    const submit = document.getElementById('pmConfirmationSubmit');
    const cancel = document.getElementById('pmConfirmationCancel');
    let pendingForm = null;
    let returnFocus = null;
    let busy = false;

    function open(form) {
        if (!form.reportValidity() || busy) return;
        pendingForm = form;
        returnFocus = document.activeElement;
        password.value = '';
        error.hidden = true;
        document.getElementById('pmConfirmationTitle').textContent = form.dataset.pmAction || 'Confirm Action';
        const parts = [];
        for (const [name, label] of [['priority', 'Priority'], ['assignment_type', 'Assign to']]) {
            const field = form.elements.namedItem(name);
            if (field?.selectedOptions?.length) parts.push(`${label}: ${field.selectedOptions[0].textContent}`);
        }
        const remarks = form.elements.namedItem('remarks');
        if (remarks?.value) parts.push(`Remarks: ${remarks.value}`);
        const wo = form.elements.namedItem('in_house_work_order');
        if (wo?.value) {
            const trimmed = wo.value.trim();
            parts.push(`Work Order: ${trimmed.length > 80 ? trimmed.slice(0, 77) + '…' : trimmed}`);
        }
        const fileInput = form.querySelector('input[type="file"][name^="completion_files"]');
        if (fileInput?.files?.length) {
            parts.push(`Report Files: ${fileInput.files.length} file(s) selected`);
        }
        document.getElementById('pmConfirmationSummary').textContent = parts.join('\n') || 'Please confirm before saving this action.';
        dialog.showModal();
        password.focus();
    }

    window.openPmConfirmation = open;
    document.addEventListener('submit', event => {
        if (!event.target.matches('[data-pm-confirm-form]')) return;
        event.preventDefault();
        open(event.target);
    });
    cancel.addEventListener('click', () => { if (!busy) dialog.close(); });
    dialog.addEventListener('cancel', event => { if (busy) event.preventDefault(); });
    dialog.addEventListener('close', () => {
        password.value = '';
        pendingForm = null;
        returnFocus?.focus();
    });
    confirmation.addEventListener('submit', async event => {
        event.preventDefault();
        if (!pendingForm || busy || !confirmation.reportValidity()) return;
        busy = true;
        submit.disabled = cancel.disabled = true;
        submit.textContent = 'Confirming…';
        error.hidden = true;
        const body = new FormData(pendingForm);
        body.set('current_password', password.value);
        try {
            const response = await fetch(pendingForm.action, {
                method: 'POST', credentials: 'same-origin', body,
                headers: { Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
            });
            const result = await response.json();
            if (!response.ok || !result.success) {
                error.textContent = result.errors ? Object.values(result.errors).flat().join('\n') : (result.message || 'Unable to save this action. Please try again.');
                error.hidden = false;
                if (result.errors?.current_password) {
                    password.value = '';
                    password.focus();
                }
                return;
            }
            password.value = '';
            if (result.redirect) {
                const target = new URL(result.redirect, window.location.href);
                const current = new URL(window.location.href);
                if (target.origin === current.origin && target.pathname === current.pathname && target.search === current.search) {
                    window.history.replaceState(null, '', target.href);
                    window.location.reload();
                } else {
                    window.location.assign(target.href);
                }
            } else window.location.reload();
        } catch (_) {
            error.textContent = 'Unable to confirm. Check your connection or refresh the page to sign in again.';
            error.hidden = false;
        } finally {
            busy = false;
            submit.disabled = cancel.disabled = false;
            submit.textContent = 'Confirm';
        }
    });
})();
