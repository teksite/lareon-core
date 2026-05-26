<x-lareon::admin-layout>
    @section('title', __(':title list',['title'=>__('users')]))
    @section('description', __('in this section you can manage all users of the app'))
    @section('header.start')
        <x-lareon::links.simple  :href="route('admin.users.create')" :content="__('create a new one')" color="blue" can="admin.user.create"/>
    @endsection
    @section('list')
        <x-lareon::box>
{{--
            {{$users->appends($_GET)->links()}}
--}}
        </x-lareon::box>
    @endsection

</x-lareon::admin-layout>
