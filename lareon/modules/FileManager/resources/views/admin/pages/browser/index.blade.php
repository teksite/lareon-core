<x-lareon::admin-layout>
    @section('title', __('media browser'))
    @section('description', __('manage media stored in the database, with files securely organized across your configured storage disks'))

    <section class="mt-12">
        <iframe src="{{route('filemanager.browser')}}" style="width:100%;min-height:900px;border:0;"></iframe>
    </section>
</x-lareon::admin-layout>
