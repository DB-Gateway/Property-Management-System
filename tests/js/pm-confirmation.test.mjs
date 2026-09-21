import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { test } from 'node:test';
import vm from 'node:vm';

const source = readFileSync(new URL('../../public/js/pm-confirmation.js', import.meta.url), 'utf8');

function setup({ response = { success: true, redirect: 'http://localhost/requests/1#request-assignment' }, ok = true } = {}) {
    const nodes = {};
    const listeners = {};
    const requests = [];
    let reloads = 0;
    for (const id of ['pmConfirmationDialog', 'pmConfirmationForm', 'pmConfirmationPassword', 'pmConfirmationError', 'pmConfirmationSubmit', 'pmConfirmationCancel', 'pmPasswordFields', 'pmConfirmationTitle', 'pmConfirmationSummary']) {
        nodes[id] = {
            dataset: {}, listeners: {}, value: '', checked: true, hidden: false, disabled: false,
            addEventListener(event, handler) { this.listeners[event] = handler; },
            focus() {}, reportValidity() { return true; },
            showModal() { this.open = true; }, close() { this.open = false; this.listeners.close?.(); },
        };
    }
    const form = {
        action: 'http://localhost/requests/1/assignment', dataset: { pmAction: 'Assign Request' },
        reportValidity() { return true; }, matches() { return true; }, querySelector() { return null; },
        elements: { namedItem(name) { return ({ priority: { selectedOptions: [{ textContent: 'Urgent' }] }, assignment_type: { selectedOptions: [{ textContent: 'In house' }] }, remarks: { value: 'Safety risk' } })[name]; } },
    };
    const location = { href: 'http://localhost/requests/1#request-assignment', reload() { reloads++; }, assign(url) { this.href = url; } };
    const context = {
        URL,
        document: { activeElement: { focus() {} }, getElementById(id) { return nodes[id]; }, addEventListener(event, handler) { listeners[event] = handler; } },
        window: { location, history: { replaceState(_state, _unused, url) { location.href = url; } } },
        FormData: class { constructor() { this.values = new Map(); } set(key, value) { this.values.set(key, value); } },
        fetch: async (url, options) => { requests.push({ url, options }); return { ok, json: async () => response }; },
    };
    vm.runInNewContext(source, context);
    const open = () => listeners.submit({ target: form, preventDefault() {} });
    const confirm = () => nodes.pmConfirmationForm.listeners.submit({ preventDefault() {} });
    return { nodes, requests, open, confirm, get reloads() { return reloads; } };
}

test('opening the modal never submits; cancel clears the password without a request', () => {
    const page = setup();
    page.open();
    assert.equal(page.nodes.pmConfirmationDialog.open, true);
    assert.equal(page.requests.length, 0);
    page.nodes.pmConfirmationPassword.value = 'private-password';
    page.nodes.pmConfirmationCancel.listeners.click();
    assert.equal(page.nodes.pmConfirmationPassword.value, '');
    assert.equal(page.requests.length, 0);
});

test('confirmation submits the password without remembering verification and reloads even at the same fragment', async () => {
    const page = setup();
    page.open();
    page.nodes.pmConfirmationPassword.value = 'private-password';
    await page.confirm();
    const body = page.requests[0].options.body.values;
    assert.equal(body.get('current_password'), 'private-password');
    assert.equal(body.has('remember_confirmation'), false);
    assert.equal(body.has('quick_confirm'), false);
    assert.equal(page.nodes.pmConfirmationPassword.value, '');
    assert.equal(page.reloads, 1);
});

test('reopening confirmation clears the previous password and keeps password entry available', async () => {
    const page = setup();
    page.open();
    page.nodes.pmConfirmationPassword.value = 'private-password';
    await page.confirm();
    page.open();
    assert.equal(page.nodes.pmConfirmationPassword.value, '');
    assert.equal(page.nodes.pmPasswordFields.hidden, false);
    assert.equal(page.nodes.pmConfirmationPassword.disabled, false);
    assert.equal(page.requests.length, 1);
});

test('incorrect password clears the input and keeps the action pending', async () => {
    const page = setup({ ok: false, response: { errors: { current_password: ['The password does not match your signed-in account.'] } } });
    page.open();
    page.nodes.pmConfirmationPassword.value = 'wrong-password';
    await page.confirm();
    assert.equal(page.nodes.pmPasswordFields.hidden, false);
    assert.equal(page.nodes.pmConfirmationPassword.value, '');
    assert.equal(page.nodes.pmConfirmationError.hidden, false);
    assert.equal(page.nodes.pmConfirmationDialog.open, true);
    assert.equal(page.reloads, 0);
});
