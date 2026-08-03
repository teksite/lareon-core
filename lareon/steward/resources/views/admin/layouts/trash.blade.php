<x-lareon::admin-layout>
    @section('title')
        {{__('lareon::global.crud.titles.trash_list' ,['attribute'=>__($pageTitle)])}}
    @endsection
    @section('description')
        {{__('lareon::global.crud.titles.trash_list' ,['attribute'=>__($pageTitle)])}}
    @endsection

    @section('header.start')
        <x-lareon::links.nav :href="route($backTo)" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__($pageTitle)])" color="index"/>
    @endsection
    @section('header.end')
        <x-lareon::search/>
    @endsection
    <div class="p-3 rounded-xl mb-6 flex items-center justify-end bordering gap-3">
        <x-lareon::links.action varaint="outline" type="restore" method="patch" :href="route($restoreRoute)" :label="__('lareon::global.buttons.restore_all')"/>
        <x-lareon::links.action varaint="outline" type="prune" method="delete" :href="route($flushRoute)" :label="__('lareon::global.buttons.delete_all')"/>
    </div>
    <x-lareon::table :rows="$items" :headers="['id'=>'#', 'title'=>__('title') ,'created_at'=>__('created at') ,'deleted_at'=>__('deleted at') ,'']">
        @foreach($items ?? [] as $key=>$item)
            <tr>
                <td class="p-3">{{$items->firstItem() + $key}}</td>
                <td>{{$item->title}}</td>
                <td>
                    <x-lareon::date :date="$item->created_at"/>
                </td>
                <td>
                    <x-lareon::date :date="$item->deleted_at"/>
                </td>
                <td>
                    <x-lareon::action-box class="action">
                        <x-lareon::links.action type="restore" method="patch" :href="route($reinstateRoute , $item)"/>
                        <x-lareon::links.action type="prune" method="delete" :href="route($pruneRoute , $item)"/>
                    </x-lareon::action-box>
                </td>
            </tr>
        @endforeach
        <x-slot:foot>
            <tr>
                <td colspan="9" class="p-2">
                    {!! $items?->appends(request()->query())->links() !!}
                </td>
            </tr>
        </x-slot:foot>
    </x-lareon::table>
    @yield('list.after')
</x-lareon::admin-layout>
