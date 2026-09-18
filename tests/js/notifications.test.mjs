import assert from 'node:assert/strict';
import { readFileSync } from 'node:fs';
import { test } from 'node:test';
import vm from 'node:vm';

const source = readFileSync(new URL('../../public/js/notifications.js', import.meta.url), 'utf8');
const settle = () => new Promise((resolve) => setImmediate(resolve));
function element(tag = 'div') {
    return {
        tag, children: [], dataset: {}, attributes: {}, listeners: {}, hidden: false, textContent: '',
        setAttribute(name, value) { this.attributes[name] = value; },
        addEventListener(name, listener) { this.listeners[name] = listener; },
        append(...children) { this.children.push(...children); },
        replaceChildren(...children) { this.children = children; },
        querySelector(selector) { return this.children.find((child) => selector === `[data-id="${child.dataset.id}"]`); },
    };
}
function page(items) {
    const nodes = Object.fromEntries(['list', 'count', 'feedback', 'more', 'read-all'].map((name) => [`[data-notification-${name}]`, element()]));
    nodes.summary = element('summary');
    const bell = element('details');
    bell.dataset = { feedUrl: 'https://pms.test/notifications', readAllUrl: 'https://pms.test/notifications/read-all', userId: '1' };
    bell.querySelector = (selector) => nodes[selector];
    const context = {
        document: {
            hidden: false, getElementById: () => bell,
            createElement: element, createElementNS: (ns, tag) => element(tag),
            querySelector: () => ({ content: 'csrf' }), addEventListener() {},
        },
        window: { addEventListener() {} }, location: { href: 'https://pms.test/dashboard' },
        URL, setInterval() {}, clearInterval() {},
        fetch: async () => ({ ok: true, status: 200, redirected: false, json: async () => ({ user_id: 1, unread_count: items.filter(item => !item.read).length, notifications: items, next_page: null }) }),
    };
    vm.runInNewContext(source, context);
    return nodes;
}

test('each notification has the appropriate accessible decorative icon and retains its text and link', async () => {
    const cases = [
        ['new_request', null, 'New request', 'new_request'],
        ['stage_completed', 'inspection', 'Inspection completed', 'inspection'],
        ['stage_completed', 'work_order', 'Work completed', 'work_order'],
        ['stage_completed', 'service_report', 'Service Report completed', 'service_report'],
        ['request_completed', null, 'Request completed', 'request_completed'],
        ['schedule_changed', null, 'Schedule changed', 'schedule_changed'],
        ['upcoming', null, 'Upcoming Inspection', 'upcoming'],
        ['ageing', null, 'Work past due', 'ageing'],
        ['stage_completed', null, 'Inspection completed', 'inspection'],
        ['stage_completed', null, 'Work completed', 'work_order'],
        ['stage_completed', null, 'Service Report completed', 'service_report'],
    ];
    const items = cases.map(([kind, stage, title], index) => ({ id: String(index), kind, stage, title, message: '<script>Example details</script>', url: `/notifications/${index}/open`, time: 'just now', read: false }));
    const nodes = page(items);
    await settle();
    const links = nodes['[data-notification-list]'].children;
    assert.equal(links.length, cases.length);
    for (const [index, link] of links.entries()) {
        const [icon, copy] = link.children;
        assert.equal(icon.className, `notification-icon notification-icon-${cases[index][3]}`);
        assert.equal(icon.attributes['aria-hidden'], 'true');
        assert.equal(icon.children[0].tag, 'svg');
        assert.equal(copy.children[0].textContent, items[index].title);
        assert.equal(copy.children[1].textContent, items[index].message);
        assert.equal(copy.children[2].textContent, 'Unread · just now');
        assert.equal(link.href, items[index].url);
    }
});

test('unknown notification types use a safe fallback and read items have no unread label', async () => {
    const nodes = page([{ id: '1', kind: 'constructor', title: 'Update', message: 'Details', read: true, time: 'a minute ago', url: '/request' }]);
    await settle();
    const link = nodes['[data-notification-list]'].children[0];
    assert.equal(link.children[0].className, 'notification-icon notification-icon-request_completed');
    assert.equal(link.children[1].children[2].textContent, 'a minute ago');
    assert.equal(nodes['[data-notification-count]'].hidden, true);
});
