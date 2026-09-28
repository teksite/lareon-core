import Sortable from 'sortablejs';

/* -------------------------------------------------------------------------- */
/*  Config                                                                    */
/* -------------------------------------------------------------------------- */

const CONFIG = {
    rootId: 'nestedMenus',          // container of the menu tree (inside your save <form>)
    createFormId: 'createForm',     // the "add item" form (#newTitle / #newUrl)
    emptyId: 'menuEmpty',           // optional element shown when the tree is empty
    initialDataId: 'menuInitialItems', // optional <script type="application/json"> with existing items
};

const TEXT = {
    untitled: '(بدون عنوان)',
    dragHandle: 'برای جابجایی بکشید',
    indent: 'تبدیل به زیرمجموعه‌ی آیتم بالایی',
    outdent: 'خارج کردن از زیرمجموعه',
    remove: 'حذف',
    invalidUrl: 'آدرس نامعتبر است. فقط http، https، mailto، tel یا آدرس نسبی مجاز است.',
};

/** Fields of each menu item. `name` is the key sent to the server: items[id][name]. */
const FIELDS = [
    { name: 'title', label: 'عنوان', wide: true },
    { name: 'url', label: 'آدرس اینترنتی', dir: 'ltr', wide: true },
    { name: 'subtitle', label: 'زیرعنوان', wide: true },
    { name: 'classes', label: 'کلاس‌ها', dir: 'ltr', wide: true },
    { name: 'pre_icon', label: 'آیکن قبل', dir: 'ltr' },
    { name: 'next_icon', label: 'آیکن بعد', dir: 'ltr' },
    { name: 'image', label: 'تصویر', dir: 'ltr', wide: true },
];

const ALLOWED_SCHEMES = new Set(['http:', 'https:', 'mailto:', 'tel:']);

const STYLES = `
.mb-root{min-height:3rem}
.mb-children{margin-inline-start:1rem;padding-inline-start:.75rem;border-inline-start:2px solid #e2e8f0}
.mb-children:empty{border-inline-start-color:transparent}
.mb-dragging .mb-children{min-height:2.25rem;margin-top:.25rem;margin-bottom:.25rem;border:2px dashed #94a3b8;border-radius:.5rem}
.mb-dragging .mb-root{outline:2px dashed #94a3b8;outline-offset:2px;border-radius:.5rem}
.mb-ghost{opacity:.4}
.mb-chosen>div:first-child{box-shadow:0 0 0 2px #3b82f6}
.mb-handle{cursor:grab;touch-action:none}
.mb-dragging .mb-handle{cursor:grabbing}
`;

/* -------------------------------------------------------------------------- */
/*  State                                                                     */
/* -------------------------------------------------------------------------- */

let root = null;
let isRtl = false;
let idCounter = 0;
let refreshQueued = false;

/** Per-item references to avoid repeated DOM queries. */
const refs = new WeakMap();

/* -------------------------------------------------------------------------- */
/*  Helpers                                                                   */
/* -------------------------------------------------------------------------- */

/**
 * Tiny DOM builder. Text is always set via textContent, so it can never inject HTML.
 * @param {string} tag
 * @param {Object} [props]
 * @param {...(Node|string|null|false)} children
 * @returns {HTMLElement}
 */
function h(tag, props = {}, ...children) {
    const node = document.createElement(tag);
    for (const [key, value] of Object.entries(props)) {
        if (value == null || value === false) continue;
        if (key === 'class') node.className = value;
        else if (key === 'text') node.textContent = value;
        else node.setAttribute(key, value === true ? '' : String(value));
    }
    node.append(...children.flat().filter(Boolean));
    return node;
}

/** Unique temporary id for new items ("rand" prefix marks them as new for the backend). */
function generateId() {
    idCounter += 1;
    return `rand${Date.now()}${idCounter}`;
}

/** Allows relative URLs and http(s)/mailto/tel only (blocks javascript:, data:, ...). */
function isSafeUrl(url) {
    if (!url) return true;
    // Browsers ignore whitespace/control chars inside schemes ("java\tscript:")
    const normalized = url.replace(/[\u0000-\u001F\u007F\s]+/g, '');
    const match = normalized.match(/^([a-z][a-z0-9+.-]*):/i);
    return match ? ALLOWED_SCHEMES.has(`${match[1].toLowerCase()}:`) : true;
}

function validateUrlField(input) {
    input.setCustomValidity(isSafeUrl(input.value.trim()) ? '' : TEXT.invalidUrl);
}

function setValue(input, value) {
    const next = String(value);
    if (input.value !== next) input.value = next;
}

function injectStyles() {
    if (document.getElementById('menu-builder-styles')) return;
    document.head.appendChild(h('style', { id: 'menu-builder-styles', text: STYLES }));
}

/* -------------------------------------------------------------------------- */
/*  Item creation                                                             */
/* -------------------------------------------------------------------------- */

function iconButton(action, glyph, title, extraClass = '') {
    return h('button', {
        type: 'button',
        'data-action': action,
        title,
        'aria-label': title,
        class:
            'inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-md text-slate-500 ' +
            'hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-500 ' +
            'disabled:opacity-30 disabled:pointer-events-none ' + extraClass,
        text: glyph,
    });
}

function createField(itemId, field, value) {
    const inputId = `${field.name}-${itemId}`;
    const input = h('input', {
        type: 'text',
        id: inputId,
        name: `items[${itemId}][${field.name}]`,
        value: value ?? '',
        class: 'input block w-full',
        dir: field.dir,
        maxlength: 255,
        autocomplete: 'off',
        'data-field': field.name,
    });
    if (field.name === 'url') validateUrlField(input);

    return h(
        'div',
        { class: field.wide ? 'md:col-span-2' : '' },
        h('label', { class: 'input_label mb-1', for: inputId, text: field.label }),
        input,
    );
}

/**
 * Builds one menu item (card + its own children container).
 * @param {Object} data - { id?, title, url, subtitle, classes, pre_icon, next_icon, image }
 * @returns {HTMLElement}
 */
function createItem(data = {}) {
    const id = String(data.id ?? generateId());

    const label = h('span', { class: 'truncate text-sm', text: data.title?.trim() || TEXT.untitled });
    const chevron = h('span', { class: 'ms-auto text-xs text-slate-400 transition-transform', text: '▾' });
    const panelId = `panel-${id}`;

    const toggle = h(
        'button',
        {
            type: 'button',
            'data-action': 'toggle',
            'aria-expanded': 'false',
            'aria-controls': panelId,
            class: 'flex min-w-0 flex-1 items-center gap-2 py-2 text-start focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 rounded',
        },
        label,
        chevron,
    );

    const indent = iconButton('indent', isRtl ? '←' : '→', TEXT.indent);
    const outdent = iconButton('outdent', isRtl ? '→' : '←', TEXT.outdent);
    const remove = iconButton('delete', '✕', TEXT.remove, 'hover:!bg-red-50 hover:text-red-600');

    const handle = h('span', {
        class: 'mb-handle select-none px-2 py-2 text-slate-400 hover:text-slate-600',
        title: TEXT.dragHandle,
        text: '⠿',
    });

    const panel = h(
        'div',
        { id: panelId, class: 'border-t border-slate-200 p-3', hidden: true },
        h('div', { class: 'grid gap-3 md:grid-cols-2' }, FIELDS.map((f) => createField(id, f, data[f.name]))),
    );

    const parent = h('input', { type: 'hidden', name: `items[${id}][parent_id]`, value: '' });
    const position = h('input', { type: 'hidden', name: `items[${id}][position]`, value: '0' });

    const card = h(
        'div',
        { class: 'rounded-lg border border-slate-300 bg-white' },
        h('div', { class: 'flex items-center gap-1 pe-1' }, handle, toggle, indent, outdent, remove),
        panel,
        parent,
        position,
    );

    const children = h('div', { class: 'nested-sortable mb-children', 'data-parent_id': id });
    const item = h('div', { class: 'menu_item mb-2', id: `menu_item-${id}`, 'data-id': id }, card, children);

    refs.set(item, { label, toggle, chevron, panel, indent, outdent, parent, position, children });
    createSortable(children);

    return item;
}

/* -------------------------------------------------------------------------- */
/*  Sortable                                                                  */
/* -------------------------------------------------------------------------- */

function createSortable(element) {
    return new Sortable(element, {
        group: 'nested',
        animation: 150,
        fallbackOnBody: true,
        swapThreshold: 0.65,
        emptyInsertThreshold: 16,
        handle: '.mb-handle',
        ghostClass: 'mb-ghost',
        chosenClass: 'mb-chosen',
        onStart: () => root.classList.add('mb-dragging'),
        onEnd: () => {
            root.classList.remove('mb-dragging');
            scheduleRefresh();
        },
    });
}

/* -------------------------------------------------------------------------- */
/*  Tree state (parent_id / position / button states)                         */
/* -------------------------------------------------------------------------- */

function scheduleRefresh() {
    if (refreshQueued) return;
    refreshQueued = true;
    requestAnimationFrame(() => {
        refreshQueued = false;
        refresh();
    });
}

/** Writes parent_id + position (index among siblings) and updates indent/outdent buttons. */
function refresh() {
    const containers = [root, ...root.querySelectorAll('.nested-sortable')];

    for (const container of containers) {
        const isRoot = container === root;
        const parentId = isRoot ? '' : container.dataset.parent_id;
        let index = 0;

        for (const item of container.children) {
            const r = refs.get(item);
            if (!r) continue;

            setValue(r.parent, parentId);
            setValue(r.position, index);
            r.indent.disabled = index === 0; // needs a previous sibling to become its child
            r.outdent.disabled = isRoot;     // already at top level
            index += 1;
        }
    }

    const empty = document.getElementById(CONFIG.emptyId);
    if (empty) empty.hidden = root.children.length > 0;
}

/* -------------------------------------------------------------------------- */
/*  Actions                                                                   */
/* -------------------------------------------------------------------------- */

function setOpen(item, open) {
    const r = refs.get(item);
    if (!r) return;
    r.panel.hidden = !open;
    r.toggle.setAttribute('aria-expanded', String(open));
    r.chevron.classList.toggle('rotate-180', open);
}

/** Moves the item to be the last child of its previous sibling. */
function indentItem(item) {
    const previous = item.previousElementSibling;
    const target = previous && refs.get(previous);
    if (!target) return;
    target.children.appendChild(item);
    scheduleRefresh();
}

/** Moves the item out of its parent, right after the parent. */
function outdentItem(item) {
    const container = item.parentElement;
    if (!container || container === root) return;
    const parentItem = container.closest('.menu_item');
    if (!parentItem) return;
    parentItem.after(item);
    scheduleRefresh();
}

/** Deletes the item; its children move up one level, keeping their order. */
function deleteItem(item) {
    const r = refs.get(item);
    if (r) item.before(...r.children.children);
    item.remove();
    scheduleRefresh();
}

/* -------------------------------------------------------------------------- */
/*  Events                                                                    */
/* -------------------------------------------------------------------------- */

function bindTreeEvents() {
    // One delegated listener for all buttons (works for items added later)
    root.addEventListener('click', (e) => {
        const button = e.target.closest('[data-action]');
        if (!button || button.disabled) return;
        const item = button.closest('.menu_item');
        if (!item) return;

        switch (button.dataset.action) {
            case 'toggle': setOpen(item, refs.get(item)?.panel.hidden); break;
            case 'indent': indentItem(item); break;
            case 'outdent': outdentItem(item); break;
            case 'delete': deleteItem(item); break;
        }
    });

    // Live title sync + URL validation
    root.addEventListener('input', (e) => {
        const input = e.target;
        if (!(input instanceof HTMLInputElement)) return;
        const item = input.closest('.menu_item');
        const r = item && refs.get(item);
        if (!r) return;

        if (input.dataset.field === 'title') r.label.textContent = input.value.trim() || TEXT.untitled;
        if (input.dataset.field === 'url') validateUrlField(input);
    });

    // If a field inside a collapsed item is invalid on save, open it so the browser can show the error
    root.addEventListener(
        'invalid',
        (e) => {
            const item = e.target.closest?.('.menu_item');
            if (item) setOpen(item, true);
        },
        true,
    );
}

function bindCreateForm() {
    const form = document.getElementById(CONFIG.createFormId);
    const titleEl = form?.querySelector('#newTitle');
    const urlEl = form?.querySelector('#newUrl');
    if (!form || !titleEl || !urlEl) return;

    urlEl.addEventListener('input', () => urlEl.setCustomValidity(''));

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        const title = titleEl.value.trim();
        const url = urlEl.value.trim();

        if (!title) {
            titleEl.focus();
            return;
        }
        if (!isSafeUrl(url)) {
            urlEl.setCustomValidity(TEXT.invalidUrl);
            urlEl.reportValidity();
            return;
        }

        root.appendChild(createItem({ title, url }));
        scheduleRefresh();

        titleEl.value = '';
        urlEl.value = '';
        titleEl.focus();
    });
}

/* -------------------------------------------------------------------------- */
/*  Initial data (editing an existing menu)                                   */
/* -------------------------------------------------------------------------- */

/**
 * Reads a flat list [{ id, parent_id, position, title, url, ... }] from
 * <script type="application/json" id="menuInitialItems">.
 */
function readInitialItems() {
    const node = document.getElementById(CONFIG.initialDataId);
    if (!node) return [];
    try {
        const data = JSON.parse(node.textContent);
        return Array.isArray(data) ? data : [];
    } catch {
        return [];
    }
}

function renderInitialItems(items) {
    if (!items.length) return;

    const ids = new Set(items.map((i) => String(i.id)));
    const byParent = new Map();

    for (const item of items) {
        const parentKey = item.parent_id != null && ids.has(String(item.parent_id)) ? String(item.parent_id) : '';
        if (!byParent.has(parentKey)) byParent.set(parentKey, []);
        byParent.get(parentKey).push(item);
    }

    const render = (parentKey, container) => {
        const list = (byParent.get(parentKey) ?? []).sort((a, b) => (a.position ?? 0) - (b.position ?? 0));
        for (const data of list) {
            const element = createItem(data);
            container.appendChild(element);
            render(String(data.id), refs.get(element).children);
        }
    };

    render('', root);
}

/* -------------------------------------------------------------------------- */
/*  Bootstrap                                                                 */
/* -------------------------------------------------------------------------- */

function initMenuBuilder() {
    root = document.getElementById(CONFIG.rootId);
    if (!root) return;

    isRtl = getComputedStyle(root).direction === 'rtl';

    injectStyles();
    root.classList.add('nested-sortable', 'mb-root');
    root.dataset.parent_id = '';

    createSortable(root);
    bindTreeEvents();
    bindCreateForm();
    renderInitialItems(readInitialItems());
    refresh();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initMenuBuilder, { once: true });
} else {
    initMenuBuilder();
}
