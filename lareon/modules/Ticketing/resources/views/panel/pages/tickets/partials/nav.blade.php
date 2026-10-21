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
        <div class="mt-12">
            <button  type="button" data-confirm-target="close-ticket-form" class="js-confirm-close text-red-600 font-bold" >
                {{ __('closing the ticket') }}
            </button>
            <form id="close-ticket-form" method="POST" action="{{ route('panel.tickets.close', $ticketId) }}" class="hidden">
                @csrf
            </form>
        </div>
   @endif
    @push('footerScripts')
        <script>
            document.querySelectorAll('.js-confirm-close').forEach(function (button) {
                button.addEventListener('click', function () {
                    if (confirm('{{__('are you sure you want to close this ticket? You will not be able to send any further replies after closing it.')}}')) {
                        document.getElementById(button.dataset.confirmTarget)?.submit();
                    }
                });
            });
        </script>
    @endpush
</aside>
