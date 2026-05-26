<x-lareon::admin-editor-layout>
    @section('title', __('new :title',['title'=>__('user')]))
    @section('description', __('in this window you can create a new :title',['title'=>__('user')]))
    @section('formRoute', route('admin.users.store'))
    @section('header.start')
        <x-lareon::link.btn-outline :href="route('admin.users.index')" :title="__('all :title',['title'=>__('users')])" color="index"/>
    @endsection
    @section('form')
        <x-lareon::box>
            <x-lareon::sections.text :value="old('name')" type="text" :title="__('name')" name="name" :placeholder="__('write a :title for :item',['title'=>__('name') , 'item'=>__('user')])" :required="true"/>
            <x-lareon::sections.text :value="old('phone')" type="phone" :title="__('phone')" name="phone" :placeholder="__('write a unique :title' ,['title'=>__('phone')])" :required="true"/>
            <x-lareon::sections.text :value="old('email')" type="email" :title="__('email')" name="email" :placeholder="__('write a unique :title' ,['title'=>__('email')])" :required="true"/>
            <x-lareon::sections.password :title="__('password')" name="password" :placeholder="__('write a :title' ,['title'=>__('password')])" :required="true"/>
            <x-lareon::sections.password :title="__('password confirmation')" name="password_confirmation" :placeholder="__('rewrite the :title' ,['title'=>__('password')])" :required="true"/>
        </x-lareon::box>
    @endsection
    @section('form.before.end')
        <x-lareon::box>
            <div class="flex items-center justify-start gap-3">
                <x-lareon::input.checkbox id="sendNotification" name="sendNotification" value="1"/>
                <x-lareon::input.label :title="__('send a welcoming email to the user')" for="sendNotification"/>
            </div>
        </x-lareon::box>
    @endsection
    @section('aside')

    @endsection
</x-lareon::admin-editor-layout>
