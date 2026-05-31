<x-lareon::admin-editor type="update" :instance="$user" :acttion="route('admin.users.edit' ,$user)">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('user')]) . "($user->fullname)")
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.users.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('users')])" color="index"/>
        <x-lareon::links.nav :href="route('admin.users.create')" :content="__('lareon::global.buttons.new_attribute' ,['attribute'=>__('user')])" color="create"/>
    @endsection
    @section('form')
        <x-lareon::editor.tabs.layout>
            <x-lareon::editor.tabs.item :title="__('basic data')">
                <div class="grid gap-6 lg:grid-cols-2">
                    <x-lareon::editor.input :required="true" labelPosition="start" :label="__('first name')" name="name" :value="$user->name" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('name') , 'item'=>__('user')])"/>
                    <x-lareon::editor.input :required="true" labelPosition="start" :label="__('last name')" name="lastname" :value="$user->lastname" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('last name') , 'item'=>__('user')])"/>
                </div>
                <div class="space-y-6">
                    <x-lareon::editor.input :required="true" type="tel" dir="ltr" :value="$user->phone" :label="__('phone')" name="phone" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('phone') , 'item'=>__('user')])"/>
                    <x-lareon::editor.input :required="true" type="email" dir="ltr" :value="$user->email" :label="__('email')" name="email" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('email') , 'item'=>__('user') ])"/>
                </div>
                <div>
                    <x-lareon::editor.input-slug :disabled="true" :readonly="true" :value="$user->slug" :label="__('slug')" name="slug" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('slug') , 'item'=>__('user') ])" :showUrl="!!($user->path())"/>
                </div>
            </x-lareon::editor.tabs.item>

            <x-lareon::editor.tabs.item :title="__('verification')">
                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <x-lareon::editor.input-radio type="inline" :required="true" :options="[[__('ignore') , ] ,[__('no') , null] , [__('yes') ,1]]" :label="__('mark email as verified')" name="email_verified_at" inputsClass="flex items-center gap-1"/>
                        <x-lareon::editor.input-radio type="inline" :required="true" :options="[[__('ignore') , ] ,[__('no') , null] , [__('yes') ,1]]" :label="__('mark phone as verified')" name="phone_verified_at" inputsClass="flex items-center gap-1"/>
                    </div>
                    <div class="">
                        <table class="w-full">
                            <tbody class="divide-y divide-line_light bg-slate-50 *:hover:bg-blue-50">
                            <tr>
                                <td class="px-3 py-2 font-bold">
                                    {{__('phone verified at')}}
                                </td>
                                <td class="px-3 py-2 ">
                                    <x-lareon::date :date="$user->phone_verified_at ?? null"/>
                                </td>
                            </tr>
                            <tr>
                                <td class="px-3 py-2 font-bold">
                                    {{__('email verified at')}}
                                </td>
                                <td class="px-3 py-2 ">
                                    <x-lareon::date :date="$user->email_verified_at ?? null"/>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </x-lareon::editor.tabs.item>

            <x-lareon::editor.tabs.item :title="__('authentication')">
                <div class="grid gap-6 md:grid-cols-2">
                    <div class="">
                        <x-lareon::editor.password :label="__('password')" :confirm_label="__('confirm password')" name="password" :placeholder="__('lareon::global.placeholders.write.auth.password',['attribute'=>__('password')])" :required="true" wrapperClass="grid gap-6 lg:grid-cols-2"/>
                    </div>
                </div>
            </x-lareon::editor.tabs.item>

        </x-lareon::editor.tabs.layout>
    @endsection
</x-lareon::admin-editor>
