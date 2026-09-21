(() => {
    const lostImageIcon = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" aria-hidden="true"><path d="M3 3l18 18M10 3h9a2 2 0 0 1 2 2v9M21 18v1a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5M3 17l5-5 4 4m3-3 6 6"/><circle cx="15.5" cy="7.5" r="1.5"/></svg>';

    function fallback(message, className = '') {
        const node = document.createElement('span');
        node.className = `attachment-fallback ${className}`;
        node.setAttribute('role', 'status');
        node.innerHTML = lostImageIcon;
        const text = document.createElement('span');
        text.textContent = message;
        node.append(text);
        return node;
    }

    function failedThumbnail(img) {
        if (!img.matches('img[data-attachment-image]')) return;
        const message = `Image unavailable: ${img.alt || 'Attachment'}`;
        const replacement = fallback('Image unavailable', img.className);
        replacement.title = message;
        replacement.setAttribute('aria-label', message);
        img.replaceWith(replacement);
    }

    // Image error events do not bubble; capture them for existing and new uploads.
    document.addEventListener('error', (event) => {
        if (event.target instanceof HTMLImageElement) failedThumbnail(event.target);
    }, true);

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('img[data-attachment-image]').forEach((img) => {
            if (img.complete && !img.naturalWidth) failedThumbnail(img);
        });

        const dialog = document.getElementById('attachmentPreviewDialog');
        if (!dialog) return;
        const body = dialog.querySelector('[data-attachment-body]');
        const title = document.getElementById('attachmentPreviewTitle');
        const download = dialog.querySelector('[data-attachment-download-link]');
        let pending;
        let previewUrl;
        let opener;

        function clearPreview() {
            pending?.abort();
            pending = null;
            body.replaceChildren();
            body.setAttribute('aria-busy', 'false');
            if (previewUrl) URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }

        async function openPreview(trigger) {
            clearPreview();
            opener = trigger;
            title.textContent = trigger.dataset.attachmentName;
            download.hidden = true;
            download.href = trigger.dataset.attachmentDownload || trigger.dataset.attachmentUrl;
            download.download = trigger.dataset.attachmentName;
            body.textContent = 'Loading preview…';
            body.setAttribute('aria-busy', 'true');
            if (!dialog.open) dialog.showModal();
            document.body.classList.add('attachment-preview-open');
            const controller = new AbortController();
            pending = controller;
            const timeout = setTimeout(() => controller.abort(), 20000);
            const kind = trigger.dataset.attachmentKind;
            try {
                const localFile = kind === 'file' && trigger.dataset.attachmentUrl.startsWith('blob:');
                const response = localFile ? null : await fetch(trigger.dataset.attachmentUrl, {
                    method: kind === 'file' ? 'HEAD' : 'GET',
                    credentials: 'same-origin', signal: controller.signal,
                });
                if (!localFile && (!response.ok || response.redirected)) throw new Error('unavailable');
                const mime = response?.headers.get('content-type') || '';
                if ((kind === 'image' && !mime.startsWith('image/')) || (kind === 'pdf' && !mime.includes('application/pdf'))) {
                    throw new Error('invalid-type');
                }
                if (kind === 'file') {
                    if (pending !== controller) return;
                    body.textContent = 'Preview is not available for this file type. Download the file to open it.';
                } else {
                    const blob = await response.blob();
                    if (pending !== controller) return;
                    previewUrl = URL.createObjectURL(blob);
                    const media = document.createElement(kind === 'image' ? 'img' : 'iframe');
                    if (kind === 'image') media.alt = trigger.dataset.attachmentName;
                    else media.title = `PDF preview: ${trigger.dataset.attachmentName}`;
                    media.addEventListener('error', () => {
                        if (pending === controller) body.replaceChildren(fallback(kind === 'image' ? 'Image unavailable' : 'Preview unavailable. Download the file to open it.'));
                    });
                    media.src = previewUrl;
                    body.replaceChildren(media);
                }
                download.hidden = false;
            } catch (error) {
                if (pending !== controller) return;
                body.replaceChildren(fallback(kind === 'image' ? 'Image unavailable' : 'Attachment unavailable. Please try again later.'));
            } finally {
                clearTimeout(timeout);
                if (pending === controller) body.setAttribute('aria-busy', 'false');
            }
        }

        document.addEventListener('click', (event) => {
            const trigger = event.target.closest('[data-attachment-open]');
            if (!trigger) return;
            event.preventDefault();
            openPreview(trigger);
        });
        dialog.querySelectorAll('[data-attachment-close]').forEach((button) => button.addEventListener('click', () => dialog.close()));
        dialog.addEventListener('click', (event) => {
            if (event.target !== dialog) return;
            const box = dialog.getBoundingClientRect();
            if (event.clientX < box.left || event.clientX > box.right || event.clientY < box.top || event.clientY > box.bottom) dialog.close();
        });
        dialog.addEventListener('close', () => {
            clearPreview();
            document.body.classList.remove('attachment-preview-open');
            opener?.focus();
        });

        document.querySelectorAll('[data-file-input]').forEach((input) => {
            const field = input.closest('.form-field') || input.parentElement;
            let grid = field.querySelector('[data-attachment-preview]');
            if (!grid) {
                grid = document.createElement('div');
                grid.className = 'attachment-preview-grid';
                grid.dataset.attachmentPreview = '';
                field.append(grid);
            }
            let urls = [];
            function renderUploads() {
                urls.forEach((url) => URL.revokeObjectURL(url));
                urls = [];
                grid.replaceChildren();
                const files = Array.from(input.files || []);
                if (files.length > Number(input.dataset.maxFiles || 10)) return;
                files.forEach((file) => {
                    const url = URL.createObjectURL(file);
                    urls.push(url);
                    const extension = file.name.split('.').pop().toLowerCase();
                    const kind = file.type.startsWith('image/') ? 'image' : (file.type === 'application/pdf' ? 'pdf' : 'file');
                    const card = document.createElement('button');
                    card.type = 'button';
                    card.className = 'attachment-preview-card';
                    Object.assign(card.dataset, { attachmentOpen: '', attachmentUrl: url, attachmentName: file.name, attachmentKind: kind });
                    card.setAttribute('aria-haspopup', 'dialog');
                    if (kind === 'image') {
                        const img = document.createElement('img');
                        img.dataset.attachmentImage = '';
                        img.alt = file.name;
                        img.src = url;
                        card.append(img);
                    } else {
                        const icon = document.createElement('span');
                        icon.className = 'attachment-preview-icon';
                        icon.textContent = extension.toUpperCase();
                        card.append(icon);
                    }
                    const name = document.createElement('span');
                    name.className = 'attachment-preview-name';
                    name.textContent = file.name;
                    const size = document.createElement('small');
                    size.textContent = `${(file.size / 1024).toFixed(1)} KB · Preview`;
                    card.append(name, size);
                    grid.append(card);
                });
            }
            input.addEventListener('change', renderUploads);
            input.form?.addEventListener('reset', () => setTimeout(renderUploads, 0));
            renderUploads();
        });
    });
})();
