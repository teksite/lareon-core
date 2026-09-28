<x-lareon::admin-list-creator>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('ips')]))
    @section('description', __('ips help visitors easily navigate between different pages or sections'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.settings.ips.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.setting.edit"/>
    @endsection
    @section('form')
        <x-lareon::editor.input :required="true" labelPosition="start" :label="__('ip')" name="ip" :value="old('ip')" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('ip')])"/>
        <x-lareon::editor.input-select :required="true" labelPosition="start" :label="__('type')" name="type" :value="old('type')">
            @foreach(\Lareon\Modules\Fence\App\Enums\GuardType::cases() as $case)
                <option value="{{$case->value}}">
                    {{$case->label()}}
                </option>
            @endforeach
        </x-lareon::editor.input-select>
    @endsection
    @section('list')
        <x-lareon::table :rows="$ips" :headers="['id'=>'#','title'=>__('title'),'type'=>__('type') ,'created_at'=>__('created at'),'']">
            @foreach($ips as $key=>$ip)
                <tr>
                    <td class="p-3">{{$ips->firstItem() + $key}}</td>
                    <td>{{$ip->ip}}</td>
                    <td>{{$ip->type->toHtml()}}</td>
                    <td>
                        <x-lareon::date :date="$ip->created_at"/>
                    </td>
                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="delete" method="delete" :href="route('admin.settings.ips.destroy' , $ip)" can="admin.setting.edit"/>
                        </x-lareon::action-box>
                    </td>
                </tr>
            @endforeach
            <x-slot:foot>
                <tr>
                    <td colspan="9" class="p-2">
                        {!! $ips->appends(request()->query())->links() !!}
                    </td>
                </tr>
            </x-slot:foot>
        </x-lareon::table>
    @endsection

</x-lareon::admin-list-creator>
