<x-lareon::admin-editor :action="route('admin.users.store')" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('user')]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.users.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('users')])" color="index"/>
    @endsection
    @section('form')
        <x-lareon::box type="y">
            <fieldset class="fieldset space-y-6">
                <legend class="legend">{{__('basic data')}}</legend>
                <div class="grid gap-6 lg:grid-cols-2">
                    <x-lareon::editor.input :required="true" labelPosition="start" :label="__('first name')" name="name" :value="old('name')" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('name') , 'item'=>__('user')])"/>
                    <x-lareon::editor.input :required="true" labelPosition="start" :label="__('last name')" name="lastname" :value="old('lastname')" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('last name') , 'item'=>__('user')])"/>
                </div>
                <div class="space-y-6">
                    <x-lareon::editor.input :required="true" type="tel" dir="ltr" :value="old('phone')" :label="__('phone')" name="phone" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('phone') , 'item'=>__('user')])"/>
                    <x-lareon::editor.input :required="true" type="email" dir="ltr" :value="old('email')" :label="__('email')" name="email" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('email') , 'item'=>__('user') ])"/>
                </div>

                <div class="">
                    <x-lareon::editor.password :label="__('password')" :confirm_label="__('confirm password')" name="password" :placeholder="__('lareon::global.placeholders.auth.password',['attribute'=>__('password')])" :required="true" wrapperClass="grid gap-6 lg:grid-cols-2"/>
                </div>
            </fieldset>
        </x-lareon::box>
        <x-lareon::box type="y">
            <fieldset class="fieldset space-y-6">
                <legend class="legend">{{__('verification')}}</legend>
                <x-lareon::editor.input-radio type="inline" :required="true" value="0" :options="[[__('no') , 0] , [__('yes') ,1]]" :label="__('mark email as verified')" name="email_verified_at" inputsClass="flex items-center gap-1"/>
                <x-lareon::editor.input-radio type="inline" :required="true" value="0" :options="[[__('no') , 0] , [__('yes') ,1]]" :label="__('mark phone as verified')" name="phone_verified_at" inputsClass="flex items-center gap-1"/>
            </fieldset>
        </x-lareon::box>

        <x-lareon::box type="y">
            <fieldset class="fieldset space-y-6">
                <legend class="legend">{{__('send notification')}}</legend>
                <div class="grid gap-6 lg:grid-cols-2">
                    <x-lareon::editor.input-check type="inline" :required="true" :options="[[__('yes') ,1]]" :label="__('send notification via email')" name="send_email_notification" inputsClass="flex items-center gap-1"/>
                    <x-lareon::editor.input-check type="inline" :required="true" :options="[[__('yes') ,1]]" :label="__('send notification via phone')" name="send_phone_notification" inputsClass="flex items-center gap-1"/>
                </div>
            </fieldset>
        </x-lareon::box>
    @endsection
    @section('aside')

    @endsection
</x-lareon::admin-editor>
