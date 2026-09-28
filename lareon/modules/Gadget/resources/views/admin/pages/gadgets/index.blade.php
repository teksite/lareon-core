<x-lareon::admin-list>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('gadgets')]))
    @section('description', __('gadgets are customizable contents or functionality to different parts of the application'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.visual.gadgets.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.gadget.create"/>
        <x-lareon::links.nav :href="route('admin.visual.gadgets.trash.index')" :content="$trashCount" color="trash" can="admin.gadget.delete"/>
    @endsection
    @section('list')
        <ul>
            @foreach($gadgets as $gadget)
                <li class="y-box">
                    <h3 class="mb-3 text-center">{{$gadget->title}}</h3>
                    <h4 class="mb-3 text-center text-gray-600">{{$gadget->label}}</h4>
                    <x-lareon::action-box class="action">
                        <x-lareon::links.action type="edit" :href="route('admin.visual.gadgets.edit' , $gadget)" can="admin.gadget.edit"/>
                        <x-lareon::links.action type="delete" method="delete" :href="route('admin.visual.gadgets.destroy' , $gadget)" can="admin.gadget.delete"/>
                    </x-lareon::action-box>
                </li>
            @endforeach
        </ul>

        <div class="p-2">
            {!! $gadgets->appends(request()->query())->links() !!}
        </div>

    @endsection

</x-lareon::admin-list>
