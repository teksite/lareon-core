<x-lareon::panel-editor type="update" method="patch" :instance="$user" :action="route('panel.profile.2fa.update')" :hasTab="false">
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
        <x-lareon::box type="y" class="space-y-3">
            <x-auth::editor.2fa :user="$user" :enabling="true"/>
        </x-lareon::box>
    @endsection
</x-lareon::panel-editor>
