<x-lareon::panel-layout type="update" method="patch" :instance="$user" :action="route('admin.users.update', $user)">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('profile')]) . "($user->fullname)")
    @section('nav')
        <x-lareon::aside.tab.items :items="[
'profile'=>route('panel.profile.edit') ,
'password'=>route('panel.profile.edit') ,
'passkey'=>route('panel.profile.edit') ,
'2fa'=>route('panel.profile.edit') ,
]"/>
    @endsection
    @section('form')
       
    @endsection

</x-lareon::panel-layout>
