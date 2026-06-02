@props(['rolesGroup'=>[],'value'=>[] ,'old'=>true])
@php
    $consideredValue = $old ? old('roles' , $value) : $value;
@endphp
<ul class="grid gap-6 sm:grid-cols-2">
    @foreach($rolesGroup as $group=>$roles)
        <li class="bordering p-3 rounded-lg">
            <ul class="space-y-3">
                @foreach($roles as $role)
                    <li class="flex items-center justify-start gap-2">
                        <x-lareon::inputs.checkbox :value="$role['id']" id="role_{{$group}}_{{$role['id']}}" name="roles[]" :checked="in_array($role['id'] , $consideredValue)" />
                        <x-lareon::inputs.label :title="$role['title']" for="role_{{$group}}_{{$role['id']}}"/>
                    </li>
                @endforeach
            </ul>
        </li>
    @endforeach
</ul>

