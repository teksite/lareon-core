@if(session()->has('reply'))
    @php
        $reply = session('reply');
    @endphp

    <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)"
         class="fixed bottom-5 end-5 z-50">
        <div @class([
                'min-w-96 rounded-lg shadow p-4 w-fit border font-bold overflow-hidden',
                'bg-green-50 text-green-600 border-green-600 shadow-green-600/50' => ($reply['type'] ?? null) === 'success',
                'bg-red-50 text-red-600 border-red-600 shadow-red-600/50' => ($reply['type'] ?? null) === 'failed',
                'bg-yellow-50 text-yellow-600 border-yellow-600 shadow-yellow-600/50' => ($reply['type'] ?? null) === 'warning',
                'bg-blue-50 text-blue-600 border-blue-600 shadow-blue-600/50' => ($reply['type'] ?? null) === 'info',
            ]) >
            @if(!empty($reply['title']))
                <div class="font-bold mb-1">
                    {{ $reply['title'] }}
                </div>
            @endif

            @foreach(($reply['message'] ?? []) as $message)
                <div>{{ $message }}</div>
            @endforeach

            @foreach(($reply['error'] ?? []) as $error)
                <div>{{ $error }}</div>
            @endforeach
                <div class="absolute bottom-0 left-0 h-1 bg-current animate-toast-progress"></div>
        </div>
    </div>
@endif
