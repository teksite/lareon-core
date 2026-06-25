<x-lareon::panel-layout type="update" method="patch" :instance="$user" :action="route('panel.profile.update')">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('profile')]) . "($user->fullname)")
    @section('nav')
        <x-lareon::aside.tab.items :items="[
'profile'=>route('panel.profile.edit') ,
'password'=>route('panel.profile.password') ,
'passkey'=>route('panel.profile.passkey') ,
'2fa'=>route('panel.profile.2fa') ,
]"/>
    @endsection

    <x-lareon::box type="y" class="space-y-3">
        <x-lareon::editor.tabs.item :title="__('password')">
            <x-lareon::editor.password :label="__('password')" :confirm_label="__('confirm password')" name="password" :placeholder="__('lareon::global.placeholders.auth.password',['attribute'=>__('password')])" wrapperClass="grid gap-6 lg:grid-cols-2"/>
        </x-lareon::editor.tabs.item>
    </x-lareon::box>
</x-lareon::panel-layout>
