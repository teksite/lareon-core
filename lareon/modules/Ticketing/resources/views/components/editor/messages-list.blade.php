@php use Lareon\Modules\Ticketing\App\Enums\SenderType; @endphp
@props(['ticket'])
<div class="w-full p-6 bordering rounded-lg">
    <ul class="space-y-3 overflow-y-auto max-h-svh ">
        @foreach($ticket->messages  ?? [] as $message)
            @php
                $isUser = $message->sender_type === SenderType::User;
                $author=$message->authorName();

            @endphp
            <li>
                <div class="flex gap-3 items-center p-1 {{$isUser ?:'flex-row-reverse'}}">
                    <span class="{{$isUser ? 'bg-green-600' : 'bg-blue-600'}} flex items-center justify-center rounded-full p-1 h-9 w-9 text-white  font-bold text-sm" title="{{$author}}">{{\Illuminate\Support\Str::initials($author)}}</span>
                    <div class="shadow p-6 rounded-lg w-full max-w-5xl bg-white">
                        {{$message->body}}
                    </div>
                </div>
            </li>
        @endforeach
    </ul>
</div>
