@props(['ticketId'=>null])
@php
    $items=[
    __('all my tickets')=>route('panel.tickets.index') ,
    __('new ticket')=>route('panel.tickets.create') ,
    ]
@endphp
<aside class="y-box">
    <nav>
        <ul class="space-y-6">
            @foreach($items as $label=>$url)
                <li>
                    <a href="{{$url}}">{{$label}}</a>
                </li>
            @endforeach
        </ul>
    </nav>

   @if($ticketId)
        <div>
            <x-lareon::buttons.nav data-confirm-target="close-ticket-form" class="min-w-36 mt-6 js-confirm-close" :fullWidth="false" type="button" color="delete" variant="outline" size="xs">
                {{ __('close') }}
            </x-lareon::buttons.nav>
            <form id="close-ticket-form" method="POST" action="{{ route('panel.tickets.close', $ticketId) }}" class="hidden">
                @csrf
            </form>
        </div>
   @endif
</aside>
