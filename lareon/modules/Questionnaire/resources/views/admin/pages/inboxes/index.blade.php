<x-lareon::admin-list>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('inboxes')]))
    @section('description', __('forms are used to collect user input, such as contact information, or feedback'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.questionnaire.forms.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('forms')])" color="index"/>
        <x-lareon::links.nav :href="route('admin.questionnaire.inboxes.trash.index')" :content="$trashCount" color="trash" can="admin.questionnaire.form.delete"/>
    @endsection
    @section('list')
        <x-lareon::table :rows="$inboxes" :headers="['id'=>'#' ,'form'=>__('form'),'url','created_at'=>__('created at'),'read_at'=>__('read at'),'reader_id'=>__('read by'),'']">
            @foreach($inboxes as $key=>$inbox)
                <tr class="{{$inbox->read_at===null ? 'font-bold' : ''}}">
                    <td class="p-3">{{$inboxes->firstItem() + $key}}</td>
                    <td>{{$inbox->form->title}}</td>
                    <td><a href="{{$inbox->url}}" title="{{$inbox->url}}" target="_blank" rel="nofollow">{{$inbox->page_title ?? $inbox->url}}</a></td>
                    <td>
                        <x-lareon::date :date="$inbox->created_at"/>
                    </td>
                    <td>
                        <x-lareon::date :date="$inbox->read_at"/>
                    </td>
                    <td>
                        {{$inbox->readBy?->name}}
                    </td>
                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="show" :href="route('admin.questionnaire.inboxes.edit' , $inbox)" can="admin.questionnaire.inbox.edit" target="_self"/>
                            <x-lareon::links.action type="delete" method="delete" :href="route('admin.questionnaire.inboxes.destroy' , $inbox)" can="admin.questionnaire.inbox.delete"/>
                        </x-lareon::action-box>
                    </td>
                </tr>
            @endforeach
            <x-slot:foot>
                <tr>
                    <td colspan="9" class="p-2">
                        {!! $inboxes->appends(request()->query())->links() !!}
                    </td>
                </tr>
            </x-slot:foot>
        </x-lareon::table>

    @endsection

</x-lareon::admin-list>
