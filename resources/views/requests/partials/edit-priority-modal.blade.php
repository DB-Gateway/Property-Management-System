<div class="admin-password-overlay" id="editPriorityOverlay" style="display:none" role="dialog" aria-modal="true" aria-labelledby="editPriorityTitle">
    <div class="admin-password-modal">
        <h2 id="editPriorityTitle">Edit Priority Status</h2>
        <p>Update priority for <strong id="editPriorityRef">this request</strong> and explain your decision to the dealer.</p>
        <form id="editPriorityForm" method="POST" action="" data-pm-confirm-form data-pm-action="Confirm Priority Decision">
            @csrf @method('PATCH')
            <div class="form-field"><label for="editPrioritySelect">Priority</label><select id="editPrioritySelect" name="priority" required><option value="regular">Regular</option><option value="urgent">Urgent</option></select></div>
            <div class="form-field"><label for="editPriorityRemarks">Decision remarks for the dealer</label><textarea id="editPriorityRemarks" name="remarks" rows="3" maxlength="1000" required></textarea></div>
            <div class="admin-password-actions"><button class="button button-light" type="button" id="editPriorityCancel">Cancel</button><button class="button button-primary" type="submit">Continue</button></div>
        </form>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const overlay = document.getElementById('editPriorityOverlay');
    if (!overlay) return;
    const form = document.getElementById('editPriorityForm');
    let previousFocus;
    function close() { overlay.style.display = 'none'; previousFocus?.focus(); }
    document.querySelectorAll('[data-open-priority-modal]').forEach(button => button.addEventListener('click', function () {
        previousFocus = button;
        document.getElementById('editPriorityRef').textContent = button.dataset.reference;
        document.getElementById('editPrioritySelect').value = button.dataset.priority || 'regular';
        document.getElementById('editPriorityRemarks').value = button.dataset.remarks || '';
        form.action = button.dataset.url;
        overlay.style.display = 'flex';
        document.getElementById('editPrioritySelect').focus();
    }));
    document.getElementById('editPriorityCancel').addEventListener('click', close);
    overlay.addEventListener('click', event => { if (event.target === overlay) close(); });
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !document.getElementById('pmConfirmationDialog')?.open) close();
    });
});
</script>
