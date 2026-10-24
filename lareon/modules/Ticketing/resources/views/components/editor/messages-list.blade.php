@php use Lareon\Modules\Ticketing\App\Enums\SenderType; @endphp
@props(['ticket' ,'showInternal'=>false])
<div class="w-full p-6 bordering rounded-lg">
    <ul class="space-y-3 overflow-y-auto max-h-svh ">
        @foreach($ticket->messages  ?? [] as $message)
            @php
                $isInternal =$message->is_internal_note;

                if ($isInternal && !$showInternal) continue;
                $isUser = $message->sender_type === SenderType::User;
                $author=$message->authorName();
            @endphp
            <li>
                <div class="flex gap-3 items-center p-1 {{$isUser ?:'flex-row-reverse'}}">
                    <span class="{{$isUser ? 'bg-green-600' : ($isInternal ? 'bg-gray-300' : 'bg-orange-600')}} flex items-center justify-center rounded-full p-1 h-9 w-9 text-white  font-bold text-sm" title="{{$author}}">{{\Illuminate\Support\Str::initials($author)}}</span>
                    <div class="shadow p-3 rounded-lg w-full max-w-5xl {{$isInternal ? 'bordering':'bg-white'}}">
                        {{$message->body}}
                        <hr class="my-2 border-line_light">
                        <div class="text-gray-600 text-xs flex items-center justify-between gap-6">
                            <div>
                                @if($isInternal)
                                    <span class="text-xs text-orange-600">{{__('internal')}}</span>
                                @endif
                                @if ($message->attachments->isNotEmpty())
                                    <div class="flex flex-wrap gap-3">
                                        @foreach ($message->attachments as $attachment)
                                           <span class="text-xs font-bold">{{__('attachments')}}:</span>
                                            <a target="_blank" class="text-2xl font-bold" rel="nofollow" title="{{$attachment->original_name}}" data-size="{{ $attachment->humanSize() }}" href="{{URL::temporarySignedRoute('attachment.files.show',now()->addMinutes(10),['path' => $attachment->path,'disk'=>$attachment->disk ,'filename'=>$attachment->original_name])}}" >
                                                📎
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <x-lareon::date :date="$message->created_at"/>
                        </div>
                    </div>

                </div>
            </li>
        @endforeach
    </ul>
</div>
