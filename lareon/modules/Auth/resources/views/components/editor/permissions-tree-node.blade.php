@props(['node', 'level' => 0 ,'value'=>[] ,'old'=>true])
@php
    $hasChildren = !empty($node['children']);
    $consideredValue = $old ? old('permissions' , $value) : $value
@endphp
<li>
    <div style="padding-inline-start: {{ $level * 9 }}px">

        @if(isset($node['id']))
            <label class="flex items-center gap-2 text-xs text-gray-600 py-1">
                📄<input type="checkbox" name="permissions[]" value="{{ $node['id'] }}" class="permission-self w-4 h-4" @checked(in_array($node['id'] ,$value))>
                <span>{{ $node['title'] }}</span>
            </label>
        @endif

        @if($hasChildren)
          <div class="permission-children permission-node">
              <label class="flex items-center gap-2 text-xs text-blue-600 ml-7 py-1">
                  📁 <input type="checkbox" class="permission-dir w-4 h-4" data-id="{{ $node['title'] }}">
                  <span>{{ $node['title'] }}</span>
              </label>
              <ul class="ms-1 space-y-1">
                  @foreach($node['children'] as $child)
                      <x-auth::editor.permissions-tree-node :node="$child" :level="$level + 1" :value="$consideredValue"/>
                  @endforeach
              </ul>
          </div>
        @endif
    </div>
</li>

