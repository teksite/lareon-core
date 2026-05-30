<x-lareon::admin-editor>
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('user')]))
    @section('formRoute', route('admin.users.store'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.users.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('users')])" color="index"/>
    @endsection
    @section('form')
        <x-lareon::box type="y">
            <fieldset class="fieldset space-y-6">
                <legend class="legend">{{__('basic data')}}</legend>
                <div class="grid gap-6 lg:grid-cols-2">
                    <x-lareon::editor.input labelPosition="start" :value="old('name')" :label="__('first name')" name="name" :placeholder="__('lareon::global.placeholder.write.two',['attribute'=>__('name') , 'item'=>__('user')])" :required="true"/>
                    <x-lareon::editor.input labelPosition="start" :value="old('lastname')" :label="__('last name')" name="lastname" :placeholder="__('lareon::global.placeholder.write.two',['attribute'=>__('last name') , 'item'=>__('user')])" :required="true"/>
                </div>
                <div class="space-y-6">
                    <x-lareon::editor.input type="tel" dir="ltr" :value="old('phone')" :label="__('phone')" name="phone" :placeholder="__('lareon::global.placeholder.write.unique_two',['attribute'=>__('phone') , 'item'=>__('user')])" :required="true"/>
                    <x-lareon::editor.input type="email" dir="ltr" :value="old('email')" :label="__('email')" name="email" :placeholder="__('lareon::global.placeholder.write.unique_two',['attribute'=>__('email') , 'item'=>__('user') ])" :required="true"/>
                </div>

                <div class="">
                    <x-lareon::editor.password :label="__('password')" :confirm_label="__('confirm password')" name="password" :placeholder="__('lareon::global.placeholder.write.password',['attribute'=>__('password')])" :required="true" wrapperClass="grid gap-6 lg:grid-cols-2"/>
                </div>
            </fieldset>

            {{--
                <x-lareon::sections.text :value="old('email')" type="email" :title="__('email')" name="email" :placeholder="__('write a unique :title' ,['title'=>__('email')])" :required="true"/>
    --}}
            {{--
                        <x-lareon::sections.password :title="__('password')" name="password" :placeholder="__('write a :title' ,['title'=>__('password')])" :required="true"/>
            --}}
            {{--
                        <x-lareon::sections.password :title="__('password confirmation')" name="password_confirmation" :placeholder="__('rewrite the :title' ,['title'=>__('password')])" :required="true"/>
            --}}
        </x-lareon::box>
    @endsection
    @section('form.before.end')
        <x-lareon::box type="y">
            <div class="flex items-center justify-start gap-3">
                {{--
                                <x-lareon::input.checkbox id="sendNotification" name="sendNotification" value="1"/>
                --}}
                {{--
                                <x-lareon::input.label :title="__('send a welcoming email to the user')" for="sendNotification"/>
                --}}
            </div>
        </x-lareon::box>
    @endsection
    @section('aside')

    @endsection
</x-lareon::admin-editor>
