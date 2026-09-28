<x-lareon::admin-list-creator>
    @push('headerScripts')
        @vite(['lareon/modules/Menu/resources/js/app.js','lareon/modules/Menu/resources/css/app.css'])
    @endpush
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('menu') . " ($menu->title)"]))
    @section('description', __('menus help visitors easily navigate between different pages or sections'))

    @section('header.start')
        <x-lareon::links.nav :href="route('admin.visual.menus.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('menus')])" color="index"/>
    @endsection

    @can('admin.menu.edit')
        @section('form')
            <x-lareon::editor.input :label="__('title')" name="title" id="newTitle" :placeholder="__('enter a :title' ,['title'=>__('title')])"/>
            <x-lareon::editor.input :label="__('url')" name="url" id="newUrl" :placeholder="__('enter a :title' ,['title'=>__('url')])"/>
        @endsection
    @endcan

    @can('admin.menu.edit')
        @section('list')
            <form action="{{ route('admin.visual.menus.sub.update', $menu) }}" method="POST" id="menuForm">
                @csrf
                @method('PATCH')

                <fieldset class="fieldset">
                    <legend class="legend">
                        {{ __('items') }}
                    </legend>

                    <p id="menuEmpty" class="rounded-lg border border-dashed border-slate-300 p-6 text-center text-sm text-slate-500"
                       @if($items->isNotEmpty()) hidden @endif>
                        {{ __('there are no items yet') }}
                    </p>

                    <div id="nestedMenus"></div>

                    <div class="mt-6 flex items-center justify-end">
                        <x-lareon::buttons.simple color="update" type="submit" role="submit">
                            {{ __('update') }}
                        </x-lareon::buttons.simple>
                    </div>
                </fieldset>
            </form>
            <script type="application/json" id="menuInitialItems">@json($items, JSON_HEX_TAG | JSON_HEX_AMP)</script>
        @endsection
    @endcan

</x-lareon::admin-list-creator>
