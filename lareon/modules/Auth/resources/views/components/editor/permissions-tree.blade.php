@props(['permissions'])

<ul class="permission-tree space-y-1 text-sm grid md:grid-cols-2 gap-6">
    @foreach($permissions as $node)
        <x-auth::editor.permissions-tree-node :node="$node" />
    @endforeach
</ul>
@push('footerScripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

            const tree = document.querySelector('.permission-tree');
            if (!tree) return;

            const all = tree.querySelectorAll('input.perm-checkbox');

            // =========================
            // DOWNWARD (ONLY virtual controls children)
            // =========================
            function syncDown(cb) {

                const type = cb.dataset.type;
                if (type !== 'virtual') return;

                const li = cb.closest('li');
                const children = li.querySelectorAll('ul input.perm-checkbox');

                children.forEach(child => {
                    child.checked = cb.checked;
                    child.indeterminate = false;
                });
            }

            // =========================
            // UPWARD (REAL + VIRTUAL parents)
            // =========================
            function syncUp(cb) {

                let li = cb.closest('li');

                while (li) {

                    const parentLi = li.parentElement.closest('li');
                    if (!parentLi) break;

                    const parentVirtual = parentLi.querySelector(':scope > div input[data-type="virtual"]');

                    const children = parentLi.querySelectorAll('ul input.perm-checkbox');

                    const checked = [...children].filter(c => c.checked).length;
                    const total = children.length;

                    const full = checked === total && total > 0;
                    const empty = checked === 0;
                    const partial = !full && !empty;

                    if (parentVirtual) {
                        parentVirtual.checked = full;
                        parentVirtual.indeterminate = partial;
                    }

                    li = parentLi;
                }
            }

            // =========================
            // EVENTS
            // =========================
            all.forEach(cb => {

                cb.addEventListener('change', function () {

                    syncDown(cb);
                    syncUp(cb);

                });

            });

        });
    </script>
@endpush
