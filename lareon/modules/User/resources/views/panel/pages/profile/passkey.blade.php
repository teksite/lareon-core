<x-lareon::panel-editor type="update" method="patch" :instance="$user" :action="route('panel.profile.passkey.update')" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('profile')]) . "($user->fullname)")
    @section('nav' ,view('user::panel.pages.profile.partials.nav'))
    @section('form')

    @endsection

</x-lareon::panel-layout>
