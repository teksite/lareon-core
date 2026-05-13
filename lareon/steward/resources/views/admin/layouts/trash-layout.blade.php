@props(['title','index' ,'trash'])
<x-lareon::admin-list-layout xmlns:x-lareon="http://www.w3.org/1999/html" xmlns:x-lareon="http://www.w3.org/1999/html">
    @section('title' ,__(':title trash',['title'=>$title]))
    @section('description', __(':title trash contains instances that have not been completely deleted from the database. you can either restore them or permanently remove them' ,['title'=>$title]))
    @section('header.start')
        <x-lareon::link.btn-outline :href="$index" :title="__('all :title',['title'=>$title])" color="index"/>
        <x-lareon::search/>
    @endsection
    @section('header.end')
        <form action="{{$trash}}" method="POST" class="deltfrmItms">
            @csrf
            @method('DELETE')
            <x-lareon::button.outline type="submit" role="button" title="{{__('wipe all item in the trash')}}" :value="__('wipe all')" color="danger" />
        </form>
        <form action="{{$trash}}" method="POST">
            @csrf
            @method('patch')
            <x-lareon::button.outline type="submit" role="button" title="{{__('restore all item in the trash')}}" :value="__('restore all')" color="update"/>
        </form>
    @endsection
    @yield('list.before')
    @yield('list')
    @yield('list.after')
</x-lareon::admin-list-layout>
