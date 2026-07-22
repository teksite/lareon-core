<x-lareon::admin-layout>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('roles')]))
    @section('description', __('roles control user access via ACL'))

    <iframe src="/filemanager/browser" style="width:100%;min-height:900px;border:0;"></iframe>
</x-lareon::admin-layout>
