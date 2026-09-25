<x-lareon::admin-editor :action="route('admin.questionnaire.inboxes.update' , $inbox)" method="update" :instance="$inbox">
    @section('title', __('lareon::global.crud.titles.show',['attribute'=>__('item') . " ({$inbox->form->title})"]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.questionnaire.inboxes.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('inboxes')])" color="index" can="admin.questionnaire.inbox.read"/>
        <x-lareon::links.nav :href="route('admin.questionnaire.forms.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('forms')])" color="index" can="admin.questionnaire.form.read"/>
    @endsection
    @section('header.end')
        <x-lareon::links.action type="delete" :href="route('admin.questionnaire.inboxes.destroy', $inbox)" method="delete" :label="trans('lareon::global.buttons.delete')" can="admin.questionnaire.form.delete"/>
    @endsection

    @section('form')
        <x-lareon::box class="overflow-x-scroll">
            <table class="w-full ">
                @foreach($inbox?->data ?? [] as $key=>$data)
                    <tr class="border border-zinc-300">
                        <th class="p-3 text-start">{{__($key)}}</th>
                        <td class="p-3">{{is_array($data) ? implode(', ', $data) : $data}}</td>
                    </tr>
                @endforeach
                <tr class="border border-zinc-300">
                    <th class="p-3 text-start">IP</th>
                    <td class="p-3">{{$inbox->ip_address}}</td>
                </tr>
                <tr class="border border-zinc-300">
                    <th class="p-3 text-start">{{__('url')}}</th>
                    <td class="p-3"><a href="{{$inbox->url}}" target="_blank">{{$inbox->url}}</a></td>
                </tr>
                <tr class="border border-zinc-300">
                    <th class="p-3 text-start">{{__('read by')}}</th>
                    <td class="p-3">{{$inbox->readBy->name}}</td>
                </tr>
            </table>
        </x-lareon::box>

    @endsection
    @section('aside')
        <x-lareon::editor.tabs.section>
            <h3>
                {{__('notes')}}
            </h3>
            @foreach($inbox?->note ?? [] as $item)
                <p class="mb-3 text-sm">
                    <span class="font-bold">{{$item['author']}}</span>
                    {{$item['note']}}
                </p>
            @endforeach
            <hr class="bordering my-6">
            <x-lareon::editor.input-textarea :required="false" :label="__('note')" name="note" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('note')])"></x-lareon::editor.input-textarea>
        </x-lareon::editor.tabs.section>
    @endsection

</x-lareon::admin-editor>
