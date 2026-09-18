document.addEventListener('DOMContentLoaded', () => {
    const profileToggle = document.querySelector('[data-profile-toggle]');
    const profileMenu = document.querySelector('[data-profile-menu]');

    profileToggle?.addEventListener('click', (event) => {
        event.stopPropagation();
        const open = profileMenu.classList.toggle('open');
        profileToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
    });

    document.addEventListener('click', (event) => {
        if (profileMenu && !profileMenu.contains(event.target)) {
            profileMenu.classList.remove('open');
            profileToggle?.setAttribute('aria-expanded', 'false');
        }
    });

    // Responsive Sidebar Controller (Desktop toggle & Mobile off-canvas drawer)
    const sidebar = document.querySelector('#sidebar');
    const SIDEBAR_STORAGE_KEY = 'pms_sidebar_collapsed';
    const isMobileBreakpoint = () => window.innerWidth <= 780;

    const updateToggleState = (isCollapsed) => {
        document.querySelectorAll('[data-sidebar-toggle]').forEach((toggle) => {
            if (isMobileBreakpoint()) {
                const isOpen = document.body.classList.contains('sidebar-mobile-open');
                toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
                toggle.setAttribute('title', isOpen ? 'Close sidebar' : 'Open sidebar');
            } else {
                toggle.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
                toggle.setAttribute('title', isCollapsed ? 'Expand sidebar' : 'Collapse sidebar');
            }
        });
    };

    const initSidebarState = () => {
        if (!isMobileBreakpoint()) {
            const shouldCollapse = localStorage.getItem(SIDEBAR_STORAGE_KEY) === '1';
            document.body.classList.toggle('sidebar-collapsed', shouldCollapse);
            document.documentElement.classList.toggle('sidebar-collapsed', shouldCollapse);
            updateToggleState(shouldCollapse);
        } else {
            document.body.classList.remove('sidebar-collapsed');
            document.documentElement.classList.remove('sidebar-collapsed');
            updateToggleState(false);
        }
    };

    const toggleSidebar = () => {
        if (isMobileBreakpoint()) {
            const isOpening = !document.body.classList.contains('sidebar-mobile-open');
            document.body.classList.toggle('sidebar-mobile-open', isOpening);
            sidebar?.classList.toggle('open', isOpening);
            updateToggleState(false);
        } else {
            const isCollapsed = !document.body.classList.contains('sidebar-collapsed');
            document.body.classList.toggle('sidebar-collapsed', isCollapsed);
            document.documentElement.classList.toggle('sidebar-collapsed', isCollapsed);
            try {
                localStorage.setItem(SIDEBAR_STORAGE_KEY, isCollapsed ? '1' : '0');
            } catch (e) {}
            updateToggleState(isCollapsed);
        }
    };

    const closeMobileSidebar = () => {
        if (isMobileBreakpoint() && document.body.classList.contains('sidebar-mobile-open')) {
            document.body.classList.remove('sidebar-mobile-open');
            sidebar?.classList.remove('open');
            updateToggleState(false);
        }
    };

    document.querySelectorAll('[data-sidebar-toggle]').forEach((toggle) => {
        toggle.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            toggleSidebar();
        });
    });

    // Close mobile drawer when clicking navigation links on mobile
    sidebar?.querySelectorAll('.nav-link, .logout-link').forEach((link) => {
        link.addEventListener('click', () => {
            if (isMobileBreakpoint()) {
                closeMobileSidebar();
            }
        });
    });

    // Close mobile drawer when Escape key is pressed
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape') {
            closeMobileSidebar();
        }
    });

    // Handle responsive window resize
    let sidebarResizeTimer;
    window.addEventListener('resize', () => {
        clearTimeout(sidebarResizeTimer);
        sidebarResizeTimer = setTimeout(() => {
            initSidebarState();
            if (!isMobileBreakpoint()) {
                document.body.classList.remove('sidebar-mobile-open');
                sidebar?.classList.remove('open');
            }
        }, 120);
    });

    initSidebarState();

    document.querySelectorAll('[data-file-input]').forEach((fileInput) => {
        const fileLabel = fileInput.closest('.file-drop')?.querySelector('[data-file-label]');
        const fileList = fileInput.closest('.form-field')?.querySelector('[data-file-list]');
        const maxFiles = Number(fileInput.dataset.maxFiles || 10);
        const emptyLabel = fileLabel?.textContent || 'Choose files';
        fileInput.addEventListener('change', () => {
            let files = Array.from(fileInput.files);

            if (files.length > maxFiles) {
                fileInput.value = '';
                files = [];
                if (fileLabel) {
                    fileLabel.textContent = `Please choose no more than ${maxFiles} files.`;
                }
            }

            const count = files.length;
            if (fileLabel) {
                if (count || fileLabel.textContent.indexOf('Please choose') === -1) {
                    fileLabel.textContent = count ? `${count} file${count === 1 ? '' : 's'} selected` : emptyLabel;
                }
            }
            if (fileList) {
                fileList.replaceChildren();
                files.forEach((file) => {
                    const size = file.size >= 1048576
                        ? `${(file.size / 1048576).toFixed(1)} MB`
                        : `${Math.max(1, Math.round(file.size / 1024))} KB`;
                    const item = document.createElement('li');
                    const name = document.createElement('span');
                    const fileSize = document.createElement('small');
                    name.textContent = file.name;
                    fileSize.textContent = size;
                    item.append(name, fileSize);
                    fileList.append(item);
                });
            }
        });
    });

    document.querySelectorAll('[data-representative-group]').forEach((group) => {
        const list = group.querySelector('[data-representative-list]');
        const addButton = group.querySelector('[data-add-representative]');
        const maximum = Number(group.dataset.maxRepresentatives || 10);
        const inputName = group.dataset.inputName;
        const inputId = group.dataset.inputId || 'representative';

        const updateRepresentativeControls = () => {
            const rows = Array.from(list?.querySelectorAll('[data-representative-row]') || []);
            rows.forEach((row, index) => {
                const input = row.querySelector('input');
                const removeButton = row.querySelector('[data-remove-representative]');
                if (input) input.id = `${inputId}_${index + 1}`;
                if (removeButton) removeButton.disabled = rows.length === 1;
            });
            if (addButton) addButton.disabled = rows.length >= maximum;
        };

        addButton?.addEventListener('click', () => {
            const rowCount = list?.querySelectorAll('[data-representative-row]').length || 0;
            if (!list || !inputName || rowCount >= maximum) return;

            const row = document.createElement('div');
            row.className = 'representative-row';
            row.dataset.representativeRow = '';

            const input = document.createElement('input');
            input.type = 'text';
            input.name = inputName;
            input.placeholder = 'Enter representative name';
            input.maxLength = 255;
            input.required = true;

            const removeButton = document.createElement('button');
            removeButton.className = 'representative-remove';
            removeButton.type = 'button';
            removeButton.dataset.removeRepresentative = '';
            removeButton.setAttribute('aria-label', 'Remove representative');
            removeButton.textContent = 'Remove';

            row.append(input, removeButton);
            list.append(row);
            updateRepresentativeControls();
            input.focus();
        });

        list?.addEventListener('click', (event) => {
            const removeButton = event.target.closest('[data-remove-representative]');
            const rows = list.querySelectorAll('[data-representative-row]');
            if (!removeButton || rows.length === 1) return;
            removeButton.closest('[data-representative-row]')?.remove();
            updateRepresentativeControls();
        });

        updateRepresentativeControls();
    });

    const requestType = document.querySelector('#request_type');
    const remarkSuggestion = document.querySelector('[data-remark-suggestion]');
    const remarkText = document.querySelector('[data-remark-text]');
    const requestDescription = document.querySelector('[data-request-description]');

    const updateRemarkSuggestion = () => {
        const suggestion = requestType?.selectedOptions[0]?.dataset.suggestion || '';
        if (remarkSuggestion && remarkText) {
            remarkSuggestion.hidden = !suggestion;
            remarkText.textContent = suggestion;
        }
    };

    requestType?.addEventListener('change', updateRemarkSuggestion);
    updateRemarkSuggestion();

    const priority = document.querySelector('#priority');
    const priorityHelp = document.querySelector('[data-priority-help]');
    const updatePriorityHelp = () => {
        if (!priorityHelp) return;
        priorityHelp.textContent = priority?.value === 'urgent'
            ? 'Urgent is for immediate operational disruption, productivity impact, or safety risk.'
            : 'Regular requests may be scheduled accordingly.';
    };
    priority?.addEventListener('change', updatePriorityHelp);
    updatePriorityHelp();

    const resetForm = document.querySelector('[data-reset-form]');
    const resetConfirmation = document.querySelector('[data-reset-confirmation]');
    const resetButton = document.querySelector('[data-reset-button]');

    const updateResetButton = () => {
        if (resetButton && resetConfirmation) {
            resetButton.disabled = resetConfirmation.value !== 'RESET REQUESTS';
        }
    };

    resetConfirmation?.addEventListener('input', updateResetButton);
    updateResetButton();

    resetForm?.addEventListener('submit', (event) => {
        if (resetConfirmation?.value !== 'RESET REQUESTS') {
            event.preventDefault();
        }
    });

    document.querySelectorAll('[data-date-period-selector]').forEach((selector) => {
        const form = selector.closest('form');
        const controls = Array.from(form?.querySelectorAll('[data-date-period-control]') || []);

        const syncDatePeriod = () => {
            const selectedPeriod = selector.value;

            controls.forEach((control) => {
                const isSelected = control.dataset.datePeriodControl === selectedPeriod;
                control.hidden = !isSelected;
                control.setAttribute('aria-hidden', isSelected ? 'false' : 'true');

                control.querySelectorAll('[data-date-period-input]').forEach((input) => {
                    input.disabled = !isSelected;
                });
            });

            selector.closest('.rfg-field')?.classList.toggle('is-active', Boolean(selectedPeriod));
        };

        selector.addEventListener('change', syncDatePeriod);
        syncDatePeriod();
    });

    // Universal Cascading Dependent Dropdowns for Branch, Area, and Brand
    document.querySelectorAll('[data-cascading-filter-form]').forEach((form) => {
        const matrixEl = form.querySelector('[data-cascading-matrix]') || form.closest('section, main, body')?.querySelector('[data-cascading-matrix]');
        if (!matrixEl) return;

        let matrix = [];
        try {
            matrix = JSON.parse(matrixEl.textContent || '[]');
        } catch (e) {
            console.error('Failed to parse cascading matrix data', e);
            return;
        }

        const branchSelect = form.querySelector('[data-cascading="branch"]') || form.querySelector('select[name="branch"], select[name="city"]');
        const areaSelect = form.querySelector('[data-cascading="combined-area"]') || form.querySelector('[data-cascading="area"]') || form.querySelector('select[name="area"]');
        const brandSelect = form.querySelector('[data-cascading="brand"]') || form.querySelector('select[name="brand"]');

        if (!branchSelect && !areaSelect && !brandSelect) return;

        const isCombinedArea = !!form.querySelector('[data-cascading="combined-area"]') || (!branchSelect && !!areaSelect);

        if (isCombinedArea) {
            const populateCombinedArea = (selectEl, availableRows, currentVal) => {
                if (!selectEl) return;
                const frag = document.createDocumentFragment();

                const defaultOpt = document.createElement('option');
                defaultOpt.value = '';
                defaultOpt.textContent = selectEl.dataset.defaultLabel || 'All Areas';
                frag.appendChild(defaultOpt);

                const areasMap = {};
                availableRows.forEach((row) => {
                    const area = row.area;
                    const city = row.city || row.branch;
                    if (!area) return;
                    if (!areasMap[area]) {
                        areasMap[area] = new Set();
                    }
                    if (city) {
                        areasMap[area].add(city);
                    }
                });

                const sortedAreas = Object.keys(areasMap).sort((a, b) => a.localeCompare(b, undefined, { sensitivity: 'base' }));
                let hasCurrentVal = false;

                sortedAreas.forEach((area) => {
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = area;

                    const areaOpt = document.createElement('option');
                    areaOpt.value = area;
                    areaOpt.textContent = area + ' (All)';
                    if (area === currentVal) {
                        areaOpt.selected = true;
                        hasCurrentVal = true;
                    }
                    optgroup.appendChild(areaOpt);

                    const sortedCities = Array.from(areasMap[area]).sort((a, b) => a.localeCompare(b, undefined, { sensitivity: 'base' }));
                    sortedCities.forEach((city) => {
                        const opt = document.createElement('option');
                        const combinedVal = city + ' - ' + area;
                        opt.value = combinedVal;
                        opt.textContent = combinedVal;
                        if (combinedVal === currentVal) {
                            opt.selected = true;
                            hasCurrentVal = true;
                        }
                        optgroup.appendChild(opt);
                    });

                    frag.appendChild(optgroup);
                });

                selectEl.innerHTML = '';
                selectEl.appendChild(frag);

                if (currentVal && !hasCurrentVal) {
                    selectEl.value = '';
                } else {
                    selectEl.value = currentVal;
                }
            };

            const populateBrands = (selectEl, validBrands, currentVal) => {
                if (!selectEl) return;
                const frag = document.createDocumentFragment();

                const defaultOpt = document.createElement('option');
                defaultOpt.value = '';
                defaultOpt.textContent = selectEl.dataset.defaultLabel || 'All Dealers';
                frag.appendChild(defaultOpt);

                let hasCurrentVal = false;
                validBrands.forEach((brand) => {
                    const opt = document.createElement('option');
                    opt.value = brand;
                    opt.textContent = brand;
                    if (brand === currentVal) {
                        opt.selected = true;
                        hasCurrentVal = true;
                    }
                    frag.appendChild(opt);
                });

                selectEl.innerHTML = '';
                selectEl.appendChild(frag);

                if (currentVal && !hasCurrentVal) {
                    selectEl.value = '';
                } else {
                    selectEl.value = currentVal;
                }
            };

            const syncCombinedDropdowns = (changedField) => {
                let areaVal = areaSelect ? areaSelect.value : '';
                let brandVal = brandSelect ? brandSelect.value : '';

                let selectedCity = '';
                let selectedArea = '';
                if (areaVal.includes(' - ')) {
                    const parts = areaVal.split(' - ');
                    selectedCity = parts[0].trim();
                    selectedArea = parts[1].trim();
                } else if (areaVal) {
                    selectedArea = areaVal.trim();
                }

                const hiddenCity = form.querySelector('input[type="hidden"][name="city"]');
                if (hiddenCity) {
                    hiddenCity.value = selectedCity;
                }

                if (changedField === 'brand') {
                    if (brandVal && (selectedArea || selectedCity)) {
                        const valid = matrix.some((row) => {
                            const matchBrand = row.brand === brandVal;
                            const matchArea = !selectedArea || row.area === selectedArea;
                            const matchCity = !selectedCity || (row.city || row.branch) === selectedCity;
                            return matchBrand && matchArea && matchCity;
                        });
                        if (!valid) {
                            areaVal = '';
                            selectedCity = '';
                            selectedArea = '';
                            if (areaSelect) areaSelect.value = '';
                        }
                    }
                } else if (changedField === 'area') {
                    if (brandVal && (selectedArea || selectedCity)) {
                        const valid = matrix.some((row) => {
                            const matchBrand = row.brand === brandVal;
                            const matchArea = !selectedArea || row.area === selectedArea;
                            const matchCity = !selectedCity || (row.city || row.branch) === selectedCity;
                            return matchBrand && matchArea && matchCity;
                        });
                        if (!valid) {
                            brandVal = '';
                            if (brandSelect) brandSelect.value = '';
                        }
                    }
                }

                const rowsForArea = brandVal
                    ? matrix.filter((row) => row.brand === brandVal)
                    : matrix;
                populateCombinedArea(areaSelect, rowsForArea, areaVal);

                const availableBrands = {};
                matrix.forEach((row) => {
                    const matchArea = !selectedArea || row.area === selectedArea;
                    const matchCity = !selectedCity || (row.city || row.branch) === selectedCity;
                    if (matchArea && matchCity && row.brand) {
                        availableBrands[row.brand] = true;
                    }
                });
                const sortedBrands = Object.keys(availableBrands).sort((a, b) => a.localeCompare(b, undefined, { sensitivity: 'base' }));
                populateBrands(brandSelect, sortedBrands, brandVal);
            };

            areaSelect?.addEventListener('change', () => syncCombinedDropdowns('area'));
            brandSelect?.addEventListener('change', () => syncCombinedDropdowns('brand'));

            syncCombinedDropdowns(null);
            return;
        }

        const populateSelect = (selectEl, validOptions, defaultLabel) => {
            if (!selectEl) return;
            const currentVal = selectEl.value;
            const frag = document.createDocumentFragment();

            const defaultOpt = document.createElement('option');
            defaultOpt.value = '';
            defaultOpt.textContent = defaultLabel || selectEl.dataset.defaultLabel || 'All';
            frag.appendChild(defaultOpt);

            validOptions.forEach((optVal) => {
                const opt = document.createElement('option');
                opt.value = optVal;
                opt.textContent = optVal;
                if (optVal === currentVal) {
                    opt.selected = true;
                }
                frag.appendChild(opt);
            });

            selectEl.innerHTML = '';
            selectEl.appendChild(frag);

            if (currentVal && !validOptions.includes(currentVal)) {
                selectEl.value = '';
            } else {
                selectEl.value = currentVal;
            }
        };

        const syncDropdowns = (changedField) => {
            let branchVal = branchSelect ? branchSelect.value : '';
            let areaVal = areaSelect ? areaSelect.value : '';
            let brandVal = brandSelect ? brandSelect.value : '';

            // Verify compatibility with changed dropdown
            if (changedField === 'branch') {
                if (branchVal && areaVal) {
                    const areaValid = matrix.some((row) => (row.city || row.branch) === branchVal && row.area === areaVal);
                    if (!areaValid) {
                        areaVal = '';
                        if (areaSelect) areaSelect.value = '';
                    }
                }
                if (branchVal && brandVal) {
                    const brandValid = matrix.some((row) => (row.city || row.branch) === branchVal && row.brand === brandVal);
                    if (!brandValid) {
                        brandVal = '';
                        if (brandSelect) brandSelect.value = '';
                    }
                }
            } else if (changedField === 'area') {
                if (areaVal && branchVal) {
                    const branchValid = matrix.some((row) => row.area === areaVal && (row.city || row.branch) === branchVal);
                    if (!branchValid) {
                        branchVal = '';
                        if (branchSelect) branchSelect.value = '';
                    }
                }
                if (areaVal && brandVal) {
                    const brandValid = matrix.some((row) => row.area === areaVal && row.brand === brandVal);
                    if (!brandValid) {
                        brandVal = '';
                        if (brandSelect) brandSelect.value = '';
                    }
                }
            } else if (changedField === 'brand') {
                if (brandVal && branchVal) {
                    const branchValid = matrix.some((row) => row.brand === brandVal && (row.city || row.branch) === branchVal);
                    if (!branchValid) {
                        branchVal = '';
                        if (branchSelect) branchSelect.value = '';
                    }
                }
                if (brandVal && areaVal) {
                    const areaValid = matrix.some((row) => row.brand === brandVal && row.area === areaVal);
                    if (!areaValid) {
                        areaVal = '';
                        if (areaSelect) areaSelect.value = '';
                    }
                }
            }

            branchVal = branchSelect ? branchSelect.value : '';
            areaVal = areaSelect ? areaSelect.value : '';
            brandVal = brandSelect ? brandSelect.value : '';

            const availableBranches = {};
            const availableAreas = {};
            const availableBrands = {};

            matrix.forEach((row) => {
                const rowBranch = row.city || row.branch;
                const matchBranch = !branchVal || rowBranch === branchVal;
                const matchArea = !areaVal || row.area === areaVal;
                const matchBrand = !brandVal || row.brand === brandVal;

                if (matchArea && matchBrand && rowBranch) {
                    availableBranches[rowBranch] = true;
                }
                if (matchBranch && matchBrand && row.area) {
                    availableAreas[row.area] = true;
                }
                if (matchBranch && matchArea && row.brand) {
                    availableBrands[row.brand] = true;
                }
            });

            const sortedBranches = Object.keys(availableBranches).sort((a, b) => a.localeCompare(b, undefined, { sensitivity: 'base' }));
            const sortedAreas = Object.keys(availableAreas).sort((a, b) => a.localeCompare(b, undefined, { sensitivity: 'base' }));
            const sortedBrands = Object.keys(availableBrands).sort((a, b) => a.localeCompare(b, undefined, { sensitivity: 'base' }));

            if (branchSelect) populateSelect(branchSelect, sortedBranches, branchSelect.dataset.defaultLabel || 'All branches');
            if (areaSelect) populateSelect(areaSelect, sortedAreas, areaSelect.dataset.defaultLabel || 'All areas');
            if (brandSelect) populateSelect(brandSelect, sortedBrands, brandSelect.dataset.defaultLabel || 'All brands');
        };

        branchSelect?.addEventListener('change', () => syncDropdowns('branch'));
        areaSelect?.addEventListener('change', () => syncDropdowns('area'));
        brandSelect?.addEventListener('change', () => syncDropdowns('brand'));

        syncDropdowns(null);
    });

    // Quick Filtering via Dashboard Stats (Reports & Requests pages)
    const quickFilterForm = document.querySelector('#report-filter-form') || document.querySelector('#requests-filter-form');
    if (quickFilterForm) {
        document.querySelectorAll('[data-report-quick-filter]').forEach((btn) => {
            btn.addEventListener('click', (event) => {
                if (event.ctrlKey || event.metaKey || event.button === 1) return;

                event.preventDefault();

                const targetStage = btn.dataset.quickStage ?? '';
                const targetStatus = btn.dataset.quickStatus ?? '';
                const isCurrentlyActive = btn.dataset.isActive === '1';

                const isReports = quickFilterForm.id === 'report-filter-form';
                const stageSelect = quickFilterForm.querySelector('#stage') || quickFilterForm.querySelector('#request-filter-stage') || quickFilterForm.querySelector('[name="stage"]');
                const statusSelect = quickFilterForm.querySelector('#status') || quickFilterForm.querySelector('#request-filter-status') || quickFilterForm.querySelector('[name="status"]');

                if (isCurrentlyActive) {
                    if (stageSelect) stageSelect.value = '';
                    if (statusSelect) statusSelect.value = isReports ? 'all' : '';
                } else {
                    if (!isReports && targetStatus === 'not_acknowledged') {
                        if (stageSelect) stageSelect.value = 'not_acknowledged';
                        if (statusSelect) statusSelect.value = '';
                    } else {
                        if (stageSelect) stageSelect.value = targetStage;
                        if (statusSelect) statusSelect.value = (targetStatus === 'all' && !isReports) ? '' : targetStatus;
                    }
                }

                stageSelect?.closest('.rfg-field, .filter-field')?.classList.toggle('is-active', Boolean(stageSelect?.value && stageSelect?.value !== 'all'));
                statusSelect?.closest('.rfg-field, .filter-field')?.classList.toggle('is-active', Boolean(statusSelect?.value && statusSelect?.value !== 'all'));

                quickFilterForm.submit();
            });
        });

        const clearQuickFilterBtn = document.querySelector('[data-clear-quick-filter]');
        clearQuickFilterBtn?.addEventListener('click', (event) => {
            if (event.ctrlKey || event.metaKey || event.button === 1) return;
            event.preventDefault();

            const isReports = quickFilterForm.id === 'report-filter-form';
            const stageSelect = quickFilterForm.querySelector('#stage') || quickFilterForm.querySelector('#request-filter-stage') || quickFilterForm.querySelector('[name="stage"]');
            const statusSelect = quickFilterForm.querySelector('#status') || quickFilterForm.querySelector('#request-filter-status') || quickFilterForm.querySelector('[name="status"]');

            if (stageSelect) stageSelect.value = '';
            if (statusSelect) statusSelect.value = isReports ? 'all' : '';

            quickFilterForm.submit();
        });

        const stageSelect = quickFilterForm.querySelector('#stage') || quickFilterForm.querySelector('#request-filter-stage');
        const statusSelect = quickFilterForm.querySelector('#status') || quickFilterForm.querySelector('#request-filter-status');

        stageSelect?.addEventListener('change', () => {
            stageSelect.closest('.rfg-field, .filter-field')?.classList.toggle('is-active', Boolean(stageSelect.value && stageSelect.value !== 'all'));
        });

        statusSelect?.addEventListener('change', () => {
            statusSelect.closest('.rfg-field, .filter-field')?.classList.toggle('is-active', Boolean(statusSelect.value && statusSelect.value !== 'all'));
        });
    }

    // Dynamic active state toggling for all filter-bar fields (Requests, Dealers)
    document.querySelectorAll('.filter-bar .filter-field').forEach((field) => {
        const control = field.querySelector('input, select');
        if (!control) return;

        const updateActive = () => {
            const val = control.value?.trim() ?? '';
            const isActive = val !== '' && val !== 'all';
            field.classList.toggle('is-active', isActive);

            const form = field.closest('form');
            if (form) {
                const resetBtn = form.querySelector('a.button-light');
                if (resetBtn) {
                    const anyActive = Array.from(form.querySelectorAll('.filter-field.is-active')).length > 0;
                    resetBtn.classList.toggle('has-active', anyActive);
                }
            }
        };

        control.addEventListener('change', updateActive);
        control.addEventListener('input', updateActive);
    });

    // Dynamic active state toggling for all report-filter-grid fields
    document.querySelectorAll('.report-filter-grid .rfg-field').forEach((field) => {
        const control = field.querySelector('input, select');
        if (!control) return;

        const updateActive = () => {
            const val = control.value?.trim() ?? '';
            const isActive = val !== '' && val !== 'all';
            field.classList.toggle('is-active', isActive);
        };

        control.addEventListener('change', updateActive);
        control.addEventListener('input', updateActive);
    });
});
