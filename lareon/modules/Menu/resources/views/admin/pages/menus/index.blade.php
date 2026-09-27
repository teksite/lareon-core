<x-lareon::admin-list-creator>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('menus')]))
    @section('description', __('menus help visitors easily navigate between different pages or sections'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.visual.menus.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.menu.create"/>
{{--        <x-lareon::links.nav :href="route('admin.visual.menus.trash.index')" :content="$trashCount" color="trash" can="admin.menu.delete"/>--}}
    @endsection
    @section('form')
        <x-lareon::editor.input :required="true" labelPosition="start" :label="__('title')" name="title" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('menu')])"/>
    @endsection
    @section('list')
        <x-lareon::table :rows="$menus" :headers="['id'=>'#','title'=>__('title'),'label'=>__('label') ,'created_at'=>__('created at'),'']">
            @foreach($menus as $key=>$menu)
                <tr>
                    <td class="p-3">{{$menus->firstItem() + $key}}</td>
                    <td>{{$menu->title}}</td>
                    <td>{{$menu->label}}</td>
                    <td>
                        <x-lareon::date :date="$menu->created_at"/>
                    </td>
                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="sub" :href="route('admin.visual.menus.sub.index' , $menu)" can="admin.menu.read"/>
                            <x-lareon::links.action type="edit" :href="route('admin.visual.menus.edit' , $menu)" can="admin.menu.edit"/>
                            <x-lareon::links.action type="delete" method="delete" :href="route('admin.visual.menus.destroy' , $menu)" can="admin.menu.delete"/>
                        </x-lareon::action-box>
                    </td>
                </tr>
            @endforeach
            <x-slot:foot>
                <tr>
                    <td colspan="9" class="p-2">
                        {!! $menus->appends(request()->query())->links() !!}
                    </td>
                </tr>
            </x-slot:foot>
        </x-lareon::table>
    @endsection

</x-lareon::admin-list-creator>
