@php
    $data= $notification->data;
    $title=$data['message']['title']?? '';
    $body=$data['message']['body']?? '';
    $type=$data['message']['type'] ?? null;
    $creator=$data['meta']['creator']?? '';
    $channels=$data['meta']['channels']?? [];

@endphp
<x-lareon::admin-editor :action="route('admin.notifications.update' , $notification)" method="update" :instance="$notification" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('notification') . " ($title) ($notification->notifiable_id)"]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.notifications.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('notifications')])" color="index"/>
        <x-lareon::links.nav :href="route('admin.notifications.create')" :content="__('lareon::global.buttons.new_one')" color="create" can="admin.notification.create"/>
    @endsection
    @section('header.end')
        <x-lareon::links.action type="delete" :href="route('admin.notifications.destroy', $notification)" method="delete" :label="trans('lareon::global.buttons.delete')" can="admin.notification.delete"/>
    @endsection

    @section('form')
        <x-lareon::editor.tabs.item :title="__('content')">
            <div class="space-y-6 y-box">
                <x-lareon::editor.input :required="true" labelPosition="start" :label="__('title')" name="title" :value="$title" :placeholder="__('lareon::global.placeholders.write.two',['attribute'=>__('title') , 'item'=>__('notification')])"/>
                <x-lareon::editor.input-textarea :required="false" :label="__('message')" rows="9" name="message" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('message')])">{!! $body !!}</x-lareon::editor.input-textarea>
                <x-lareon::editor.input-select :required="true" :label="__('type')" name="type" :value="$type">
                    @foreach(\Lareon\Modules\Notifier\App\Enums\NotificationEnum::cases() as $case)
                        <option value="{{$case->value}}">{{$case->value}}</option>
                    @endforeach
                </x-lareon::editor.input-select>
            </div>

            <x-slot:aside>
                <x-lareon::editor.tabs.section>
                    <div class="flex items-center justify-between gap-3">
                        <h3>{{__('user')}}</h3>
                        <p class="font-bold text-sm">
                            {{$notification->notifiable_id}}
                        </p>
                    </div>
                </x-lareon::editor.tabs.section>

                <x-lareon::editor.tabs.section>
                    <div class="flex items-center justify-between gap-3">
                        <h3>{{__('creator')}}</h3>
                        <div class="group relative">
                            <x-tkicon icon="exclamation-circle" class="fill-none stroke-1 stroke-cyan-600 size-5"/>
                            <div class="y-box hidden group-hover:block absolute z-10 w-64 min-w-fit left-0">
                                <p>>0: admins</p>
                                <p>=0: system</p>
                            </div>
                        </div>
                    </div>

                    <p class="font-bold text-sm">
                        {{$creator}}
                    </p>
                </x-lareon::editor.tabs.section>

                <x-lareon::editor.tabs.section>
                    <h3>{{__('via')}}</h3>
                    <ul class="list-inside list-disc">
                        @foreach($channels as $channel)
                            <li>{{$channel}}</li>
                        @endforeach
                    </ul>
                </x-lareon::editor.tabs.section>
            </x-slot:aside>

        </x-lareon::editor.tabs.item>
    @endsection

</x-lareon::admin-editor>
