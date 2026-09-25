<x-lareon::admin-list>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('forms')]))
    @section('description', __('forms are used to collect user input, such as contact information, or feedback'))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.questionnaire.forms.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.questionnaire.form.create"/>
        <x-lareon::links.nav :href="route('admin.questionnaire.forms.trash.index')" :content="$trashCount" color="trash" can="admin.questionnaire.form.delete"/>
    @endsection
    @section('list')
        <x-lareon::table :rows="$forms" :headers="['id'=>'#' ,'title'=>__('title') ,'active'=>__('status') ,__('inbox') ,'created_at'=>__('created at'),'']">
            @foreach($forms as $key=>$form)
                <tr>
                    <td class="p-3">{{$forms->firstItem() + $key}}</td>

                    <td>{{$form->title}}</td>
                    <td>
                        <span class="{{$form->active==1 ? 'badge-green':__('badge-yellow')}}">
                            {{$form->active ?__('activated'):__('deactivated')}}
                        </span>
                    </td>
                    <td class="select-none">
                     <span title="{{__('all')}}"> {{$form->inbox_count}}</span> / <span title="{{__('unread')}}" class="{{$form->unread_inbox_count > 0 ? 'font-bold' :''}}">{{$form->unread_inbox_count}}</span>
                    </td>
                    <td>
                        <x-lareon::date :date="$form->created_at"/>
                    </td>
                    <td>
                        <x-lareon::action-box class="action">
                            <x-lareon::links.action type="sub" :href="route('admin.questionnaire.inboxes.index').'?form='.$form->id" can="admin.questionnaire.form.edit"/>
                            <x-lareon::links.action type="edit" :href="route('admin.questionnaire.forms.edit' , $form)" can="admin.questionnaire.form.edit"/>
                            <x-lareon::links.action type="delete" method="delete" :href="route('admin.questionnaire.forms.destroy' , $form)" can="admin.questionnaire.form.delete"/>
                        </x-lareon::action-box>
                    </td>
                </tr>
            @endforeach
            <x-slot:foot>
                <tr>
                    <td colspan="9" class="p-2">
                        {!! $forms->appends(request()->query())->links() !!}
                    </td>
                </tr>
            </x-slot:foot>
        </x-lareon::table>

    @endsection

</x-lareon::admin-list>
