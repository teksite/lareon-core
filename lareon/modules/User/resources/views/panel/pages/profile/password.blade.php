<x-lareon::panel-editor type="update" method="patch" :instance="$user" :action="route('panel.profile.password.update')" :hasTab="false">
@section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('profile')]) . "($user->fullname)")
    @section('nav' ,view('user::panel.pages.profile.partials.nav'))
    @section('form')
        <x-lareon::box type="y" class="space-y-3">
            <x-lareon::editor.password :label="__('current password')" name="current_password" :showConfirm="false" :strength="false" :placeholder="__('**********')" wrapperClass="grid gap-6 lg:grid-cols-2"/>
            <x-lareon::editor.password :label="__('password')" :confirm_label="__('confirm password')" name="password" :placeholder="__('lareon::global.placeholders.auth.password',['attribute'=>__('password')])" wrapperClass="grid gap-6 lg:grid-cols-2"/>
        </x-lareon::box>
    @endsection
</x-lareon::panel-editor>
