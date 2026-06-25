<x-lareon::panel-editor type="update" method="patch" :instance="$user" :action="route('panel.profile.update')" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('profile')]) . "($user->fullname)")
    @section('nav' ,view('user::panel.pages.profile.partials.nav'))
    @section('form')
        <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-5">
            <x-lareon::box type="y" class="space-y-3 xl:col-span-3">
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
            </x-lareon::box>
            <aside class="xl:col-span-2 y-box">
                <x-lareon::editor.publish-data :instance="$user"/>
            </aside>
        </section>
    @endsection
</x-lareon::panel-editor>
