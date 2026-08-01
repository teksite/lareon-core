import Sortable from 'sortablejs';

document.addEventListener('DOMContentLoaded', () => {
    const sourceList = document.getElementById('elements-list');
    const targetList = document.getElementById('template-elements-list');

    if (!sourceList || !targetList) return;

    Sortable.create(sourceList, {
        group: { name: 'template-elements', pull: 'clone', put: false },
        sort: false,
        handle: '[data-element-handler]',
        animation: 150,
        filter: 'input, textarea, select, button',
        preventOnFilter: false,
    });

    Sortable.create(targetList, {
        group: { name: 'template-elements', pull: false, put: true },
        sort: true,
        handle: '[data-element-handler]',
        animation: 150,
        filter: 'input, textarea, select, button',
        preventOnFilter: false,

        onAdd(evt) {
            buildRow(evt.item);
            reindex();
        },
        onSort: reindex,
        onUpdate: reindex,
    });

    targetList.addEventListener('click', (e) => {
        const btn = e.target.closest('[data-remove-template-element]');
        if (!btn) return;
        btn.closest('.template-element-item')?.remove();
        reindex();
    });

    function buildRow(clonedEl) {
        const elementId = clonedEl.dataset.elementId;
        console.log(clonedEl)
        const title = clonedEl.querySelector('[data-element-handler]')
            ?.textContent.trim() ?? '';

        let args = [];
        try {
            args = JSON.parse(clonedEl.getAttribute('data-arguments') ?? '[]');
        } catch {
            args = [];
        }

        const row = document.createElement('div');
        row.className = 'template-element-item border border-zinc-300 rounded-lg p-3 bg-white';
        row.dataset.elementId = elementId;

        const header = document.createElement('div');
        header.className = 'flex items-center justify-between gap-3 mb-3';

        const left = document.createElement('div');
        left.className = 'flex items-center gap-2';

        const handle = document.createElement('span');
        handle.className = 'handler cursor-move text-gray-400';
        handle.dataset.elementHandler = 'handler';
        handle.textContent = '⠿';
        left.appendChild(handle);

        const titleEl = document.createElement('span');
        titleEl.className = 'font-medium';
        titleEl.textContent = title;
        left.appendChild(titleEl);

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'text-red-600';
        removeBtn.dataset.removeTemplateElement = '';
        removeBtn.textContent = '×';

        header.appendChild(left);
        header.appendChild(removeBtn);
        row.appendChild(header);

        // hidden input element_id
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.dataset.field = 'element_id';
        idInput.value = elementId;
        row.appendChild(idInput);

        // گرید فیلدهای آرگومان
        if (args.length) {
            const grid = document.createElement('div');
            grid.className = 'grid sm:grid-cols-2 gap-4';

            args.forEach((argName) => {
                const wrap = document.createElement('div');

                const label = document.createElement('label');
                label.className = 'block font-medium text-xs text-gray-600 mb-2';
                label.textContent = argName;

                const input = document.createElement('input');
                input.type = 'text';
                input.className = 'input block w-full';
                input.dataset.field = 'arg';
                input.dataset.argName = argName;

                wrap.appendChild(label);
                wrap.appendChild(input);
                grid.appendChild(wrap);
            });

            row.appendChild(grid);
        }

        clonedEl.replaceWith(row);
    }

    function reindex() {
        targetList.querySelectorAll('.template-element-item').forEach((item, index) => {
            const elementIdInput = item.querySelector('[data-field="element_id"]');
            if (elementIdInput) elementIdInput.name = `elements[${index}][element_id]`;

            item.querySelectorAll('[data-field="arg"]').forEach((input) => {
                input.name = `elements[${index}][args][${input.dataset.argName}]`;
            });
        });
    }
});
