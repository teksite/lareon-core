<x-lareon::panel-editor type="update" method="patch" :instance="$user" :action="route('panel.profile.passkey.update')" :hasTab="false">
@section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('profile')]) . "($user->fullname)")
    @section('nav')
        <x-lareon::aside.tab.items :items="[
'profile'=>route('panel.profile.edit') ,
'password'=>route('panel.profile.password') ,
'passkey'=>route('panel.profile.passkey') ,
'2fa'=>route('panel.profile.2fa') ,
]"/>
    @endsection
    @section('form')

    @endsection

</x-lareon::panel-layout>
