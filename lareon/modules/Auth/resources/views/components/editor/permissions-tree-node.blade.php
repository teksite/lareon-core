@props(['node', 'level' => 0])
@php
    $hasChildren = !empty($node['children']);
@endphp
<li>
    <div style="padding-inline-start: {{ $level * 9 }}px">

        @if(isset($node['id']))
            <label class="flex items-center gap-2 text-xs text-gray-600 py-1">
                📄<input type="checkbox" name="permissions[]" value="{{ $node['id'] }}" class="perm-self w-4 h-4">
                <span>{{ $node['title'] }}</span>
            </label>
        @endif

        @if($hasChildren)
            <label class="flex items-center gap-2 text-xs text-blue-600 ml-7 py-1">
                📁 <input type="checkbox" class="perm-cascade w-4 h-4" data-path="{{ $node['title'] }}">
                <span>{{ $node['title'] }}</span>

            </label>
            <ul class="ms-1 space-y-1">
                @foreach($node['children'] as $child)
                    <x-auth::editor.permissions-tree-node :node="$child" :level="$level + 1"/>
                @endforeach
            </ul>
        @endif
    </div>
</li>

