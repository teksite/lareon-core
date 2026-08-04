<x-lareon::admin-editor type="update" method="patch" :instance="$user" :action="route('admin.users.update', $user)" :publishInfo="true" :publishStatus="false">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('user')]) . "($user->fullname)")
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.users.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('users')])" color="index" can="admin.user.read"/>
        <x-lareon::links.nav :href="route('admin.users.create')" :content="__('lareon::global.buttons.new_attribute' ,['attribute'=>__('user')])" color="create" can="admin.user.create"/>
    @endsection

    @section('header.end')
        <x-lareon::links.action type="delete" :href="route('admin.users.destroy', $user)" method="delete" :label="trans('lareon::global.buttons.delete')" can="admin.user.delete"/>
    @endsection

    @section('form')

        <x-lareon::editor.tabs.item :title="__('basic data')">
            <x-lareon::editor.tabs.section>
                <div class="grid gap-6 lg:grid-cols-2">
                    <x-lareon::editor.input :required="true" labelPosition="start" :label="__('first name')" name="name" :value="old('name', $user->name)" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('name') , 'item'=>__('user')])"/>
                    <x-lareon::editor.input :required="true" labelPosition="start" :label="__('last name')" name="lastname" :value="old('lastname', $user->lastname)" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('last name') , 'item'=>__('user')])"/>
                </div>
                <div class="space-y-6">
                    <x-lareon::editor.input :required="true" type="tel" dir="ltr" :value="old('phone', $user->phone)" :label="__('phone')" name="phone" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('phone') , 'item'=>__('user')])"/>
                    <x-lareon::editor.input :required="true" type="email" dir="ltr" :value="old('email', $user->email)" :label="__('email')" name="email" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('email') , 'item'=>__('user') ])"/>
                </div>
                <div>
                    <x-lareon::editor.input-slug :disabled="true" :readonly="true" :value="old('slug', $user->slug)" :label="__('slug')" name="slug" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('slug') , 'item'=>__('user') ])" :showUrl="!!($user->path())"/>
                </div>
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.section>

        <x-lareon::editor.tabs.item :title="__('verifications')">

            <x-lareon::editor.tabs.section>
                <x-lareon::editor.input-radio type="inline" :required="true" :options="[[__('ignore') ,-1 ] ,[__('no') ,0] , [__('yes') ,1]]" :label="__('mark phone as verified')" name="phone_verified_at" inputsClass="flex items-center gap-1" :value="-1"/>
                <x-lareon::editor.input-radio type="inline" :required="true" :options="[[__('ignore') ,-1 ] ,[__('no') ,0] , [__('yes') ,1]]" :label="__('mark email as verified')" name="email_verified_at" inputsClass="flex items-center gap-1" :value="-1"/>
            </x-lareon::editor.tabs.section>

            <x-slot:aside>
                <x-lareon::editor.tabs.section>
                    <table class="w-full y-box">
                        <tbody class="divide-y divide-line_light *:hover:bg-blue-50">
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
                </x-lareon::editor.tabs.section>
            </x-slot:aside>
        </x-lareon::editor.tabs.item>

        <x-lareon::editor.tabs.item :title="__('password')">
            <x-lareon::editor.tabs.section>
                <x-lareon::editor.input-password :label="__('password')" :confirm_label="__('confirm password')" name="password" :placeholder="__('lareon::global.placeholders.auth.password',['attribute'=>__('password')])" wrapperClass="grid gap-6 lg:grid-cols-2"/>
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.item>

        <x-lareon::editor.tabs.item :title="__('passkey')">
            <x-lareon::editor.tabs.section>
                <x-auth::editor.passkeys :passkeys="$user->passkeys"/>
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.item>

        @if(\Illuminate\Support\Facades\Route::has('two-factor.enable'))
            <x-lareon::editor.tabs.item :title="__('two factor authentication')">
                <x-lareon::editor.tabs.section>
                    <x-auth::editor.2fa :user="$user"/>
                </x-lareon::editor.tabs.section>
            </x-lareon::editor.tabs.item>
        @endif


    @endsection
    @section('aside')
        <x-user::user-card :user="$user"/>
    @endsection
</x-lareon::admin-editor>
