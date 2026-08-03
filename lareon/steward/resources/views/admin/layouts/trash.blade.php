<x-lareon::admin-layout>
    @section('title')
        {{__('global.crud.titles.trash_list' ,['attribute'=>__($pageTitle)])}}
    @endsection
    @section('description')
        @yield('description')
    @endsection
    @section('header.end')
        <x-lareon::search/>
    @endsection
    @yield('list.before')
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
                        <x-lareon::links.action type="delete" method="delete" :href="route($trashIndex , $item)" can="admin.page.delete"/>
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
