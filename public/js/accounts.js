document.addEventListener('DOMContentLoaded', () => {
    const role = document.querySelector('[data-user-role]');
    const dealer = document.querySelector('[data-user-dealer]');
    const dealerField = document.querySelector('[data-dealer-field]');
    const dealerInformation = document.querySelector('[data-dealer-information]');
    const dealerEditLink = document.querySelector('[data-dealer-edit-link]');
    const setDealerText = (field, value) => {
        const element = document.querySelector(`[data-dealer-info-${field}]`);
        if (element) element.textContent = value || '—';
    };
    const updateDealerInformation = () => {
        if (!dealer || !dealerInformation) return;
        const option = dealer.selectedOptions[0];
        const hasDealer = role?.value === 'dealer' && Boolean(option?.value);
        dealerInformation.hidden = !hasDealer;
        if (!hasDealer) return;

        setDealerText('name', option.dataset.dealerName);
        setDealerText('brand', option.dataset.dealerBrand);
        setDealerText('city', option.dataset.dealerCity);
        setDealerText('area', option.dataset.dealerArea);
        setDealerText('address', option.dataset.dealerAddress);
        setDealerText('contact', option.dataset.dealerContact);

        if (dealerEditLink) {
            const mayEditDealer = dealer.dataset.userEditing === 'true' && Boolean(option.dataset.dealerEditUrl);
            dealerEditLink.hidden = !mayEditDealer;
            if (mayEditDealer) dealerEditLink.href = option.dataset.dealerEditUrl;
        }
    };
    const updateBranchRequirement = () => {
        if (!role || !dealer) return;
        const isDealer = role.value === 'dealer';
        dealer.required = isDealer;
        if (dealerField) dealerField.hidden = !isDealer;
        const marker = document.querySelector('[data-dealer-required]');
        if (marker) marker.hidden = !dealer.required;
        updateDealerInformation();
    };
    role?.addEventListener('change', updateBranchRequirement);
    dealer?.addEventListener('change', updateDealerInformation);
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
