@php($isActive =!!$user->two_factor_secret)
<x-lareon::panel-editor :buttonText=" $isActive? __('disable') : __('enable')" type="{{$isActive ? 'delete' :'update'}}" method="{{$isActive ? 'DELETE' :'POST'}}" :instance="$user" :action="$isActive ? route('two-factor.disable') :route('two-factor.enable')" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('profile')]) . "($user->fullname)")
    @section('nav' ,view('user::panel.pages.profile.partials.nav'))

    @section('form')
        <x-lareon::box type="y" class="space-y-3">
            <x-auth::editor.2fa :user="$user" :disabling="false"/>
        </x-lareon::box>
    @endsection
</x-lareon::panel-editor>
