<x-lareon::admin-editor method="create" :action="route('admin.tickets.refer.store' ,$ticket)" :instance="$ticket" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('referral')]). " ($ticket->ticket_number)")
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.tickets.referrals.inbox')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('referrals')])" color="index"/>
    @endsection
    @section('form')
        <x-lareon::editor.tabs.item>
            <div class="grid md:grid-cols-2 gap-6 y-box font-bold">
                <div class="flex items-center gap-3 ">
                    <span class="">{{ __('subject') }}:</span>
                    <span class="">{{ $ticket->subject }}</span>
                </div>

                <div class="flex items-center gap-3" dir="ltr">
                    <spa>#{{$ticket->ticket_number}}</spa>
                </div>

            </div>

            <x-lareon::editor.tabs.section>
                <x-lareon::editor.input-textarea :required="true" :label="__('reason for refer')" name="reason" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('reason for refer')])"></x-lareon::editor.input-textarea>
            </x-lareon::editor.tabs.section>

            <x-slot:aside>
                <x-lareon::editor.tabs.section>
                    <x-lareon::editor.input-select :required="true" :label="__('refer to')" name="to_admin_id">
                        @foreach($admins as $admin)
                            <option value="{{$admin->id}}">{{$admin->name}}</option>
                        @endforeach
                    </x-lareon::editor.input-select>
                </x-lareon::editor.tabs.section>

            </x-slot:aside>
        </x-lareon::editor.tabs.item>
    @endsection

</x-lareon::admin-editor>
