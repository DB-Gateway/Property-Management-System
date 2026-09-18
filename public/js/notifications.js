(() => {
    const bell = document.getElementById('notificationBell');
    if (!bell) return;
    const list = bell.querySelector('[data-notification-list]');
    const badge = bell.querySelector('[data-notification-count]');
    const feedback = bell.querySelector('[data-notification-feedback]');
    const more = bell.querySelector('[data-notification-more]');
    const readAll = bell.querySelector('[data-notification-read-all]');
    let nextPage = null;
    let busy = false;
    let stopped = false;
    let timer;
    let hasHistory = false;

    const icons = {
        new_request: ['M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Z', 'M14 2v6h6M12 12v6m-3-3h6'],
        inspection: ['M9 4H6a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2h-3', 'M9 2h6v4H9zM8 14l3 3 5-6'],
        work_order: ['M14.5 6.5l3 3L21 6a6 6 0 0 1-8 8l-7 7a2.1 2.1 0 0 1-3-3l7-7a6 6 0 0 1 8-8l-3.5 3.5Z'],
        service_report: ['M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6Z', 'M14 2v6h6M8 15l3 3 5-6'],
        request_completed: ['M22 11.1V12a10 10 0 1 1-5.9-9.1', 'M22 4 12 14l-3-3'],
        schedule_changed: ['M8 2v4m8-4v4M3 10h18M5 4h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2Z', 'm9 16 2 2 4-4'],
        upcoming: ['M22 12a10 10 0 1 1-20 0 10 10 0 0 1 20 0Z', 'M12 6v6l4 2'],
        ageing: ['M10.3 3.9 1.8 18.1A2 2 0 0 0 3.5 21h17a2 2 0 0 0 1.7-2.9L13.7 3.9a2 2 0 0 0-3.4 0Z', 'M12 9v4m0 4h.01'],
        priority_changed: ['M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z', 'M4 22v-7'],
    };
    const notificationIcon = (item) => {
        let icon = item.kind;
        if (item.kind === 'stage_completed') {
            // Older notifications predate the explicit stage field.
            icon = item.stage || ({ 'Inspection completed': 'inspection', 'Work completed': 'work_order', 'Service Report completed': 'service_report' })[item.title];
        }
        if (!Object.hasOwn(icons, icon)) icon = 'request_completed';
        const badge = document.createElement('span');
        badge.className = `notification-icon notification-icon-${icon}`;
        badge.setAttribute('aria-hidden', 'true');
        const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
        for (const [key, value] of Object.entries({ width: '22', height: '22', viewBox: '0 0 24 24', fill: 'none', stroke: 'currentColor', 'stroke-width': '1.8', 'stroke-linecap': 'round', 'stroke-linejoin': 'round', focusable: 'false' })) {
            svg.setAttribute(key, value);
        }
        for (const data of icons[icon]) {
            const path = document.createElementNS('http://www.w3.org/2000/svg', 'path');
            path.setAttribute('d', data);
            svg.append(path);
        }
        badge.append(svg);
        return badge;
    };

    const request = async (url, method = 'GET') => {
        const response = await fetch(url, {
            method, credentials: 'same-origin', cache: 'no-store',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-PMS-Account': bell.dataset.userId,
            },
        });
        if ([401, 403, 409, 419].includes(response.status) || response.redirected) {
            stopped = true;
            clearInterval(timer);
            list.replaceChildren();
            badge.hidden = true;
            throw new Error('Your session changed. Reload the page and sign in again.');
        }
        if (!response.ok) throw new Error('Could not refresh notifications. Please try again.');
        return response.json();
    };
    const render = (items, append) => {
        if (!append) list.replaceChildren();
        for (const item of items) {
            if (append && list.querySelector(`[data-id="${item.id}"]`)) continue;
            const link = document.createElement('a');
            link.className = `notification-item${item.read ? '' : ' is-unread'}${item.kind === 'ageing' ? ' is-ageing' : ''}`;
            link.dataset.id = item.id;
            link.href = item.url;
            const title = document.createElement('strong');
            title.textContent = item.title;
            const message = document.createElement('span');
            message.textContent = item.message;
            const time = document.createElement('small');
            time.textContent = `${item.read ? '' : 'Unread · '}${item.time}`;
            const copy = document.createElement('span');
            copy.className = 'notification-copy';
            copy.append(title, message, time);
            link.append(notificationIcon(item), copy);

            if (item.kind === 'priority_changed') {
                link.dataset.kind = 'priority_changed';
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    if (!item.read) {
                        item.read = true;
                        link.classList.remove('is-unread');
                        request(bell.dataset.readAllUrl.replace('/read-all', `/${item.id}/read`), 'PATCH').catch(() => {});
                        const curCount = parseInt(badge.textContent, 10);
                        if (!isNaN(curCount) && curCount > 1) {
                            badge.textContent = curCount - 1;
                        } else {
                            badge.hidden = true;
                        }
                    }
                    bell.open = false;
                    if (typeof window.openDealerPriorityNoticeModal === 'function') {
                        window.openDealerPriorityNoticeModal({
                            referenceNo: item.reference_no,
                            oldPriority: item.old_priority,
                            newPriority: item.new_priority,
                            remarks: item.remarks,
                            url: item.url,
                        });
                    }
                });
            }

            list.append(link);
        }
        if (!list.children.length) {
            const empty = document.createElement('p');
            empty.className = 'notification-empty';
            empty.textContent = 'You’re all caught up. New updates will appear here.';
            list.append(empty);
        }
    };
    const refresh = async (page = 1) => {
        if (busy || stopped) return;
        busy = true;
        more.disabled = true;
        try {
            const url = new URL(bell.dataset.feedUrl, location.href);
            url.searchParams.set('page', page);
            const data = await request(url);
            if (String(data.user_id) !== bell.dataset.userId) throw new Error('Your account changed. Reload this page.');
            badge.textContent = data.unread_count > 99 ? '99+' : data.unread_count;
            badge.hidden = data.unread_count === 0;
            bell.querySelector('summary').setAttribute('aria-label', `Notifications, ${data.unread_count} unread`);
            readAll.disabled = data.unread_count === 0;
            render(data.notifications, page > 1);
            hasHistory = page > 1;
            nextPage = data.next_page;
            more.hidden = !nextPage;
            feedback.textContent = '';
        } catch (error) {
            feedback.textContent = error.message;
        } finally {
            busy = false;
            more.disabled = false;
        }
    };
    readAll.addEventListener('click', async () => {
        readAll.disabled = true;
        try {
            await request(bell.dataset.readAllUrl, 'PATCH');
            await refresh();
        } catch (error) { feedback.textContent = error.message; readAll.disabled = false; }
    });
    more.addEventListener('click', () => { if (nextPage) void refresh(nextPage); });
    bell.addEventListener('toggle', () => { if (bell.open) void refresh(); });
    document.addEventListener('click', (event) => { if (!bell.contains(event.target)) bell.open = false; });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && bell.open) { bell.open = false; bell.querySelector('summary').focus(); }
    });
    window.addEventListener('gateway:push-received', () => void refresh());
    document.addEventListener('visibilitychange', () => { if (!document.hidden) void refresh(); });
    window.addEventListener('online', () => void refresh());
    const priorityNoticeOverlay = document.getElementById('dealerPriorityNoticeOverlay');
    const closeDealerPriorityNotice = () => {
        if (priorityNoticeOverlay) priorityNoticeOverlay.style.display = 'none';
    };

    window.openDealerPriorityNoticeModal = (info) => {
        if (!priorityNoticeOverlay) return;
        const refEl = document.getElementById('dealerPriorityNoticeRef');
        const oldEl = document.getElementById('dealerNoticeOldPriority');
        const newEl = document.getElementById('dealerNoticeNewPriority');
        const remarksEl = document.getElementById('dealerNoticeRemarks');
        const viewLink = document.getElementById('dealerPriorityNoticeViewLink');

        if (refEl) refEl.textContent = info.referenceNo || 'Request';
        if (oldEl) {
            const oldP = (info.oldPriority || 'regular').toLowerCase();
            oldEl.textContent = oldP.charAt(0).toUpperCase() + oldP.slice(1);
            oldEl.className = 'badge priority-' + oldP;
        }
        if (newEl) {
            const newP = (info.newPriority || 'urgent').toLowerCase();
            newEl.textContent = newP.charAt(0).toUpperCase() + newP.slice(1);
            newEl.className = 'badge priority-' + newP;
        }
        if (remarksEl) {
            remarksEl.textContent = info.remarks || 'No remarks provided.';
            remarksEl.style.fontStyle = info.remarks ? 'normal' : 'italic';
            remarksEl.style.color = info.remarks ? '#1e293b' : '#64748b';
        }
        if (viewLink) {
            viewLink.href = info.url || '#';
        }

        priorityNoticeOverlay.style.display = 'flex';
    };

    const dismissBtn = document.getElementById('dealerPriorityNoticeDismiss');
    const closeXBtn = document.getElementById('dealerPriorityNoticeCloseBtn');
    if (dismissBtn) dismissBtn.addEventListener('click', closeDealerPriorityNotice);
    if (closeXBtn) closeXBtn.addEventListener('click', closeDealerPriorityNotice);
    if (priorityNoticeOverlay) {
        priorityNoticeOverlay.addEventListener('click', (e) => {
            if (e.target === priorityNoticeOverlay) closeDealerPriorityNotice();
        });
    }
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && priorityNoticeOverlay && priorityNoticeOverlay.style.display !== 'none') {
            closeDealerPriorityNotice();
        }
    });

    timer = setInterval(() => { if (!document.hidden && !(bell.open && hasHistory)) void refresh(); }, 30000);
    void refresh();
})();
