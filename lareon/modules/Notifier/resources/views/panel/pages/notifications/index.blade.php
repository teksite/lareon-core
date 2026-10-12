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
                @endphp
                <li>
                   <div class="y-box">
                        <h3>
                            {{$title}}
                        </h3>
                       <p class="text-sm">
                           {{$message}}
                       </p>
                   </div>
                </li>
            @endforeach
        </ul>
    </div>
</x-lareon::panel-layout>
