document.addEventListener('DOMContentLoaded', () => {
    const role = document.querySelector('[data-user-role]');
    const dealer = document.querySelector('[data-user-dealer]');
    const updateBranchRequirement = () => {
        if (!role || !dealer) return;
        dealer.required = role.value === 'dealer';
        const marker = document.querySelector('[data-dealer-required]');
        if (marker) marker.hidden = !dealer.required;
    };
    role?.addEventListener('change', updateBranchRequirement);
    updateBranchRequirement();

    const deleteDialog = document.querySelector('[data-delete-dialog]');
    let deleteForm = null;
    document.querySelectorAll('[data-delete-user]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            if (form.dataset.confirmed === 'true') return;
            event.preventDefault();
            deleteForm = form;
            deleteDialog.querySelector('[data-delete-user-name]').textContent = form.dataset.deleteUser;
            deleteDialog.showModal();
        });
    });
    document.querySelector('[data-delete-cancel]')?.addEventListener('click', () => deleteDialog.close());
    document.querySelector('[data-delete-confirm]')?.addEventListener('click', () => {
        if (!deleteForm) return;
        deleteForm.dataset.confirmed = 'true';
        deleteForm.requestSubmit();
    });

    const passwordDialog = document.querySelector('[data-first-login-dialog]');
    if (passwordDialog && typeof passwordDialog.showModal === 'function') {
        passwordDialog.removeAttribute('open');
        passwordDialog.showModal();
        passwordDialog.addEventListener('cancel', (event) => event.preventDefault());
    }
});
