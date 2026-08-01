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
        const sourceTitle = clonedEl.querySelector('[data-element-handler]')
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

        // ---------- هدر ----------
        const header = document.createElement('div');
        header.className = 'flex items-center justify-between gap-3 mb-3';

        const left = document.createElement('div');
        left.className = 'flex items-center gap-2';

        const handle = document.createElement('span');
        handle.className = 'handler cursor-move text-gray-400';
        handle.dataset.elementHandler = 'handler';
        handle.textContent = '⠿';
        left.appendChild(handle);

        // اسم نوع المان (ثابت، از منبع)
        const sourceTitleEl = document.createElement('span');
        sourceTitleEl.className = 'font-medium text-gray-500';
        sourceTitleEl.textContent = sourceTitle;
        left.appendChild(sourceTitleEl);

        // عنوان real-time که با اینپوت title همگام می‌شود
        const liveTitleEl = document.createElement('span');
        liveTitleEl.className = 'text-gray-400';
        liveTitleEl.dataset.liveTitle = '';
        liveTitleEl.textContent = '';
        left.appendChild(liveTitleEl);

        const removeBtn = document.createElement('button');
        removeBtn.type = 'button';
        removeBtn.className = 'text-red-600';
        removeBtn.dataset.removeTemplateElement = '';
        removeBtn.textContent = '×';

        header.appendChild(left);
        header.appendChild(removeBtn);
        row.appendChild(header);

        // ---------- element_id مخفی ----------
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.dataset.field = 'element_id';
        idInput.value = elementId;
        row.appendChild(idInput);

        // ---------- فیلدهای ثابت name / title ----------
        const fixedGrid = document.createElement('div');
        fixedGrid.className = 'grid sm:grid-cols-2 gap-4 mb-4';

        const nameField = createField({
            label: 'name',
            field: 'name',
        });

        const titleField = createField({
            label: 'title',
            field: 'title',
        });

        // به‌محض تایپ در title، متن کنار هندل هم آپدیت شود
        titleField.input.addEventListener('input', () => {
            liveTitleEl.textContent = titleField.input.value
                ? `— ${titleField.input.value}`
                : '';
        });

        fixedGrid.appendChild(nameField.wrap);
        fixedGrid.appendChild(titleField.wrap);
        row.appendChild(fixedGrid);

        // ---------- فیلدهای args ----------
        if (args.length) {
            const grid = document.createElement('div');
            grid.className = 'grid sm:grid-cols-2 gap-4';

            args.forEach((argName) => {
                const { wrap } = createField({
                    label: argName,
                    field: 'arg',
                    argName,
                });
                grid.appendChild(wrap);
            });

            row.appendChild(grid);
        }

        clonedEl.replaceWith(row);
    }

    // سازنده‌ی عمومی یک فیلد label+input تا کد name/title/arg تکرار نشود
    function createField({ label, field, argName = null }) {
        const wrap = document.createElement('div');

        const labelEl = document.createElement('label');
        labelEl.className = 'block font-medium text-xs text-gray-600 mb-2';
        labelEl.textContent = label;

        const input = document.createElement('input');
        input.type = 'text';
        input.className = 'input block w-full';
        input.dataset.field = field;
        if (argName !== null) input.dataset.argName = argName;

        wrap.appendChild(labelEl);
        wrap.appendChild(input);

        return { wrap, input };
    }

    function reindex() {
        targetList.querySelectorAll('.template-element-item').forEach((item, index) => {
            const elementIdInput = item.querySelector('[data-field="element_id"]');
            if (elementIdInput) elementIdInput.name = `elements[items][${index}][element_id]`;

            const nameInput = item.querySelector('[data-field="name"]');
            if (nameInput) nameInput.name = `elements[items][${index}][name]`;

            const titleInput = item.querySelector('[data-field="title"]');
            if (titleInput) titleInput.name = `elements[items][${index}][title]`;

            item.querySelectorAll('[data-field="arg"]').forEach((input) => {
                input.name = `elements[items][${index}][args][${input.dataset.argName}]`;
            });
        });
    }
});
