import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { test } from 'node:test';
import vm from 'node:vm';

const source = readFileSync(new URL('../../public/js/attachment-preview.js', import.meta.url), 'utf8');

class Element {
    constructor(tag = 'span') {
        this.tagName = tag;
        this.dataset = {};
        this.listeners = {};
        this.children = [];
        this.attributes = {};
        this.classList = { add() {}, remove() {} };
    }
    addEventListener(name, handler) { this.listeners[name] = handler; }
    setAttribute(name, value) { this.attributes[name] = value; }
    append(...nodes) { this.children.push(...nodes); }
    replaceChildren(...nodes) { this.children = nodes; this.textContent = ''; }
    replaceWith(node) { this.replacement = node; }
    closest() { return this; }
    matches() { return this.dataset.attachmentImage !== undefined; }
    focus() { this.focused = true; }
    showModal() { this.open = true; }
    close() { this.open = false; this.listeners.close(); }
}
class ImageElement extends Element {}

function setup({ status = 200, mime = 'image/png', redirected = false, thumbnails = [], inputs = [], fetchImpl } = {}) {
    const dialog = new Element('dialog');
    const body = new Element('div');
    const title = new Element('h2');
    const download = new Element('a');
    const close = new Element('button');
    dialog.querySelector = (selector) => selector === '[data-attachment-body]' ? body : download;
    dialog.querySelectorAll = () => [close];
    const listeners = {};
    const requests = [];
    const revoked = [];
    const document = {
        body: new Element('body'),
        addEventListener(name, handler) { listeners[name] = handler; },
        getElementById(id) { return id === 'attachmentPreviewDialog' ? dialog : title; },
        querySelectorAll(selector) { return selector === 'img[data-attachment-image]' ? thumbnails : inputs; },
        createElement(tag) { return tag === 'img' ? new ImageElement(tag) : new Element(tag); },
    };
    const response = { ok: status === 200, redirected, headers: { get: () => mime }, blob: async () => ({}) };
    vm.runInNewContext(source, {
        document, HTMLImageElement: ImageElement, AbortController,
        setTimeout, clearTimeout,
        URL: { createObjectURL: () => 'blob:preview', revokeObjectURL: (url) => revoked.push(url) },
        fetch: async (url, options) => { requests.push({ url, options }); return fetchImpl ? fetchImpl(options) : response; },
    });
    listeners.DOMContentLoaded();
    async function open(kind = 'image', url = '/attachments/1', name = 'photo.png') {
        const trigger = new Element('a');
        trigger.dataset = { attachmentKind: kind, attachmentUrl: url, attachmentName: name, attachmentDownload: '/attachments/1?download=1' };
        let prevented = false;
        listeners.click({ target: trigger, preventDefault() { prevented = true; } });
        await new Promise(setImmediate);
        assert.equal(prevented, true);
        return trigger;
    }
    return { dialog, body, title, download, close, requests, revoked, listeners, open };
}

test('images open in a modal and closing releases the image and restores focus', async () => {
    const app = setup();
    const trigger = await app.open('image', '/attachments/1', '<photo>.png');
    assert.equal(app.dialog.open, true);
    assert.equal(app.title.textContent, '<photo>.png');
    assert.equal(app.body.children[0].tagName, 'img');
    assert.equal(app.body.children[0].src, 'blob:preview');
    assert.equal(app.download.hidden, false);
    app.close.listeners.click();
    assert.equal(app.dialog.open, false);
    assert.equal(trigger.focused, true);
    assert.deepEqual(app.revoked, ['blob:preview']);
    assert.equal(app.body.children.length, 0);
});

test('PDFs use an embedded viewer and expose a download', async () => {
    const app = setup({ mime: 'application/pdf' });
    await app.open('pdf');
    assert.equal(app.body.children[0].tagName, 'iframe');
    assert.equal(app.download.hidden, false);
});

test('missing images, HTML login responses and redirected sessions show an unavailable icon', async () => {
    for (const options of [{ status: 404 }, { mime: 'text/html' }, { redirected: true }]) {
        const app = setup(options);
        await app.open();
        assert.match(app.body.children[0].innerHTML, /<svg/);
        assert.equal(app.body.children[0].children[0].textContent, 'Image unavailable');
        assert.equal(app.download.hidden, true);
        assert.equal(app.body.attributes['aria-busy'], 'false');
    }
});

test('a corrupt image that downloads successfully still displays the unavailable icon', async () => {
    const app = setup();
    await app.open();
    app.body.children[0].listeners.error();
    assert.equal(app.body.children[0].children[0].textContent, 'Image unavailable');
});

test('office files check availability, while local office uploads require no HTTP request', async () => {
    const app = setup();
    await app.open('file');
    assert.equal(app.requests[0].options.method, 'HEAD');
    assert.match(app.body.textContent, /Download the file/);
    assert.equal(app.download.hidden, false);
    const local = setup();
    await local.open('file', 'blob:local-file');
    assert.equal(local.requests.length, 0);
    assert.equal(local.download.hidden, false);
});

test('an old request cannot replace a newly selected attachment', async () => {
    let resolveFirst;
    let count = 0;
    const response = { ok: true, headers: { get: () => 'image/png' }, blob: async () => ({}) };
    const app = setup({ fetchImpl: () => ++count === 1 ? new Promise((resolve) => { resolveFirst = resolve; }) : response });
    await app.open();
    await app.open('image', '/attachments/2', 'second.png');
    resolveFirst(response);
    await new Promise(setImmediate);
    assert.equal(app.title.textContent, 'second.png');
    assert.equal(app.body.children[0].alt, 'second.png');
    app.dialog.close();
});

test('cached failures and dynamically loaded thumbnail errors get an accessible lost-image icon', () => {
    const cached = new ImageElement('img');
    Object.assign(cached, { complete: true, naturalWidth: 0, alt: 'missing.png', dataset: { attachmentImage: '' } });
    const app = setup({ thumbnails: [cached] });
    assert.equal(cached.replacement.attributes['aria-label'], 'Image unavailable: missing.png');
    const dynamic = new ImageElement('img');
    dynamic.dataset.attachmentImage = '';
    dynamic.className = 'chip-img';
    app.listeners.error({ target: dynamic });
    assert.match(dynamic.replacement.className, /chip-img/);
    assert.match(dynamic.replacement.innerHTML, /<svg/);
});

test('upload fields get clickable previews, clear stale URLs, and respect file limits', () => {
    const input = new Element('input');
    const field = new Element('div');
    field.querySelector = () => null;
    input.closest = () => field;
    input.dataset.maxFiles = '5';
    input.files = [{ name: 'new.png', type: 'image/png', size: 1024 }];
    const app = setup({ inputs: [input] });
    const grid = field.children[0];
    assert.equal(grid.children[0].tagName, 'button');
    assert.equal(grid.children[0].type, 'button');
    assert.equal(grid.children[0].dataset.attachmentKind, 'image');
    assert.equal(grid.children[0].children[0].dataset.attachmentImage, '');
    input.files = [{ name: 'report.pdf', type: 'application/pdf', size: 2048 }];
    input.listeners.change();
    assert.deepEqual(app.revoked, ['blob:preview']);
    assert.equal(grid.children.length, 1);
    assert.equal(grid.children[0].dataset.attachmentKind, 'pdf');
    input.files = Array(6).fill(input.files[0]);
    input.listeners.change();
    assert.equal(grid.children.length, 0);
});
