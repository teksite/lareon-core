@props(['permissions'])

<ul class="permission-tree space-y-1 text-sm grid md:grid-cols-2 gap-6">
    @foreach($permissions as $node)
        <x-auth::editor.permissions-tree-node :node="$node" />
    @endforeach
</ul>
@push('footerScripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {

        //todo sync view vy check and mark permissions
    </script>
@endpush
