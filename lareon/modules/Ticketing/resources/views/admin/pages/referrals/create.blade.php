<x-lareon::admin-editor method="create" :action="route('admin.tickets.refer.store' ,$ticket)" :instance="$ticket" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('referral')]). " ($ticket->ticket_number)")
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.tickets.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('tickets')])" color="index"/>
        <x-lareon::links.nav :href="route('admin.tickets.index')" :content="__('go back')" color="index"/>
    @endsection
    @section('form')
       <x-ticketing::editor.create-refer :ticket="$ticket" :admins="$admins"/>
    @endsection

</x-lareon::admin-editor>
