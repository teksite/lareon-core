@php use Lareon\Modules\Notifier\App\Enums\NotificationEnum; @endphp
<x-lareon::panel-layout>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('notifications')]))

    <div class="inner-content">
        <ul class="space-y-3">
            @foreach($notifications as $notification)
                @php
                    $data= $notification->data;
                    $message= $data['message']['body'] ?? '';
                    $title= $data['message']['title'] ?? '';
                    $readAt= $notification->read_at;
                    $type= NotificationEnum::tryFrom($data['meta']['type'] ?? null) ??  NotificationEnum::General;
                @endphp
                <li>
                    <div class="y-box relative">
                        <div class="absolute h-1/2 w-2 right-0 rounded-e-2xl {{$readAt ? 'bg-gray-600' : 'bg-blue-600' }}"></div>
                        <div class="flex gap-3 items-center ">
                            <x-tkicon icon="{{$type->icon()}}" class="size-5 {{$type->classIcon()}}"/>
                            <h3>
                                {{$title}}
                            </h3>
                        </div>
                        <p class="text-sm">
                            {{$message}}
                        </p>
                    </div>
                </li>
            @endforeach
        </ul>
    </div>
</x-lareon::panel-layout>
