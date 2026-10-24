<x-lareon::panel-editor type="create" method="post" :instance="$ticket" :action="route('panel.tickets.store')" :hasTab="false" hasFile="true">
    @section('title', __('lareon::global.crud.titles.create',['attribute'=>__('ticket')]) )
    @section('form')
        <div class="flex flex-col items-start gap-6 md:flex-row">
            <div class="w-full md:max-w-[350px]">
                @include('ticketing::panel.pages.tickets.partials.nav' )
            </div>
            <x-lareon::box type="y" class="space-y-3 w-full">
                <div class="grid gap-6 lg:grid-cols-2">
                    <x-lareon::editor.input :required="true" labelPosition="top" :label="__('title')" name="subject" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('issue')])"/>
                    <x-lareon::editor.input-select :required="true" labelPosition="top" :label="__('priority')" name="priority">
                        @foreach($priorities as $priority)
                            <option value="{{$priority->value}}">{{__($priority->label())}}</option>
                        @endforeach
                    </x-lareon::editor.input-select>
                </div>
                <x-lareon::editor.input-textarea :required="true" labelPosition="top" :label="__('message')" name="body" rows="16" min="5" max="10000"></x-lareon::editor.input-textarea>

                @if (config('ticketing.attachments.enabled'))
                    <x-lareon::editor.input-file :required="false" :multiple="true" labelPosition="top" :label="__('attachment')" name="attachment[]" :accept="config('ticketing.attachments.allowed_extensions')"/>
                @endif
            </x-lareon::box>
        </div>

    @endsection
</x-lareon::panel-editor>
