@props(['permissions'=>[],'value'=>null])

<ul class="permission-tree space-y-1 text-sm grid md:grid-cols-2 gap-6">
    @foreach($permissions as $node)
        <x-auth::editor.permissions-tree-node :node="$node ?? []" :value="$value"/>
    @endforeach
</ul>
@push('footerScripts')
    <script>
        function updateNode(node) {

            const dirCheckbox = node.querySelector(':scope > label .permission-dir');

            if (!dirCheckbox) {
                return;
            }

            const permissions = node.querySelectorAll(
                ':scope > ul input.permission-self'
            );

            const total = permissions.length;

            if (!total) {
                dirCheckbox.checked = false;
                dirCheckbox.indeterminate = false;
                return;
            }

            const checkedCount = [...permissions]
                .filter(input => input.checked)
                .length;

            dirCheckbox.checked = checkedCount === total;
            dirCheckbox.indeterminate =
                checkedCount > 0 && checkedCount < total;
        }

        function updateParents(node) {

            while (node) {

                updateNode(node);

                node = node.parentElement?.closest('.permission-node');
            }
        }

        function initializeTree() {

            const nodes = document.querySelectorAll('.permission-node');

            for (let i = nodes.length - 1; i >= 0; i--) {
                updateNode(nodes[i]);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {

            initializeTree();

            document.addEventListener('change', e => {

                // Folder checkbox
                if (e.target.classList.contains('permission-dir')) {

                    const node = e.target.closest('.permission-node');

                    if (!node) {
                        return;
                    }

                    const checked = e.target.checked;

                    node
                        .querySelectorAll(
                            'input.permission-self, input.permission-dir'
                        )
                        .forEach(input => {
                            input.checked = checked;
                            input.indeterminate = false;
                        });

                    updateParents(
                        node.parentElement?.closest('.permission-node')
                    );

                    return;
                }

                // Permission checkbox
                if (!e.target.classList.contains('permission-self')) {
                    return;
                }

                updateParents(
                    e.target.closest('.permission-node')
                );
            });

        });
    </script>
@endpush
