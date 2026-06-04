<x-lareon::admin-editor type="update" method="patch" :instance="$user" :action="route('admin.users.update', $user)">
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

        <x-lareon::editor.tabs.item :title="__('verifications')">
            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-6">
                    <x-lareon::editor.input-radio type="inline" :required="true" :options="[[__('ignore') ,-1 ] ,[__('no') ,0] , [__('yes') ,1]]" :label="__('mark phone as verified')" name="phone_verified_at" inputsClass="flex items-center gap-1" :value="-1"/>
                    <x-lareon::editor.input-radio type="inline" :required="true" :options="[[__('ignore') ,-1 ] ,[__('no') ,0] , [__('yes') ,1]]" :label="__('mark email as verified')" name="email_verified_at" inputsClass="flex items-center gap-1" :value="-1"/>
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

        <x-lareon::editor.tabs.item :title="__('password')">
            <div class="grid gap-6 md:grid-cols-2">
                <div class="">
                    <x-lareon::editor.password :label="__('password')" :confirm_label="__('confirm password')" name="password" :placeholder="__('lareon::global.placeholders.auth.password',['attribute'=>__('password')])" wrapperClass="grid gap-6 lg:grid-cols-2"/>
                </div>
            </div>
        </x-lareon::editor.tabs.item>

        <x-lareon::editor.tabs.item :title="__('passkey')">
            <div class="grid gap-6 md:grid-cols-2">
                <div class="">
                    <x-lareon::editor.password :label="__('password')" :confirm_label="__('confirm password')" name="password" :placeholder="__('lareon::global.placeholders.auth.password',['attribute'=>__('password')])" wrapperClass="grid gap-6 lg:grid-cols-2"/>
                </div>
            </div>
        </x-lareon::editor.tabs.item>

    @if(\Illuminate\Support\Facades\Route::has('two-factor.enable'))
        <x-lareon::editor.tabs.item :title="__('two factor authentication')">
            <x-auth::editor.2fa :user="$user"/>
        </x-lareon::editor.tabs.item>
        @endif

    @endsection
    @section('form.after')
        <x-lareon::box class="mb-6">
            <form method="POST" action="{{ route('two-factor.enable')}}">
                @csrf
                <div class="mb-3 flex flex-col md:flex-row items-center justify-between gap-6">
                    <p class="mb-0 w-full">
                        {{__('Two-Factor Authentication is currently disabled. To enable it, please click the \'enable\' button')}}
                    </p>
                    <x-lareon::buttons.nav class="w-64">
                        {{ __('enable') }}
                    </x-lareon::buttons.nav>
                </div>

            </form>
        </x-lareon::box>
    @endsection
</x-lareon::admin-editor>
