@if ($errors->any())
    <ul class="alert alert-danger my-6 w-11/12 mx-auto">
        @foreach ($errors->all() as $error)
            <li id="error-all-{{$loop->index}}"
                class="text-red-900 text-sm font-bold p-1 bg-red-600 border border-dotted error-item gap-3 transition-all duration-150 flex justify-between items-center">
                {{ $error }}
                <button role="button" type="button" id="error-all-{{$loop->index}}-btn" data-target="error-all-{{$loop->index}}" class="hideBtn text-gray-50 flex items-center justify-center hover:bg-red-900 p-0.5 rounded-full w-6 h-6">
                    x
                </button>
            </li>
        @endforeach
    </ul>
@endif
