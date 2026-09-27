<x-lareon::admin-editor :action="route('admin.visual.menus.update' , $menu)" method="update" :instance="$menu" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('menu') . " ($menu->title)"]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.visual.menus.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('menus')])" color="index"/>
        <x-lareon::links.nav :href="route('admin.visual.menus.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.menu.create"/>
    @endsection
    @section('header.end')
        <x-lareon::links.action type="delete" :href="route('admin.visual.menus.destroy', $menu)" method="delete"  :label="trans('lareon::global.buttons.delete')" can="admin.menu.delete"/>

    @endsection

    @section('form')
        <x-lareon::editor.tabs.item :title="__('content')">
            <x-lareon::editor.tabs.section>
               <div class="flex gap-3 items-center">
                   <span>{{__('label')}}</span>
                   <span class="font-bold">{{$menu->label}}</span>
               </div>
                <x-lareon::editor.input :required="true" labelPosition="start" :label="__('title')" name="title" :value="$menu->title" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('menu')])"/>
                <x-lareon::editor.input dir="ltr" :required="false" labelPosition="start" :label="__('classes')" name="classes" :value="$menu->classes" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('classes') , 'item'=>__('menu')])"/>
            </x-lareon::editor.tabs.section>>
        </x-lareon::editor.tabs.item>
    @endsection

</x-lareon::admin-editor>
