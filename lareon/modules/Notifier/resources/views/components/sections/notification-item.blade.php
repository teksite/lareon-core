@props(['notification'])

@php
    use Lareon\Modules\Notifier\App\Enums\NotificationEnum;

    $data = $notification->data ?? [];

    $message = data_get($data, 'message.body', '');
    $title = data_get($data, 'message.title', '');
    $readAt = $notification->read_at;
    $type = NotificationEnum::tryFrom(data_get($data, 'meta.type', '')) ?? NotificationEnum::General;
    $badgeClass = $type->badgeClass();
    $iconClass = $type->iconClass();
@endphp

<div class="y-box relative"  id="{{$notification->id}}"  data-notification-box>
    <div class="absolute right-0 h-1/2 w-2 rounded-e-2xl {{ $readAt ? 'bg-gray-300' : $badgeClass }}"></div>

    <div class="flex items-center gap-3" >
        <x-tkicon :icon="$type->icon()" class="size-5  {{$readAt ? 'fill-none stroke-gray-300' : $iconClass  }}"/>
        <h3>{{ $title }}</h3>
        <button class="self-end w-64"  role="button" type="button">
            {{__('mark as read')}}
        </button>
    </div>
    <p class="text-sm">{{ $message }}</p>

</div>
