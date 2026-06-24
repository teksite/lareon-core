@if ($errors->any())
    <div class="w-11/12 mx-auto my-6 rounded-xl border border-red-300 bg-red-50 p-4 shadow-sm">
        <div class="flex items-center gap-2 mb-3">
            <svg class="w-5 h-5 text-red-700" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                      d="M18 10A8 8 0 114 4.5 8 8 0 0118 10zM9 7a1 1 0 012 0v3a1 1 0 11-2 0V7zm1 7a1.25 1.25 0 100-2.5A1.25 1.25 0 0010 14z"
                      clip-rule="evenodd"/>
            </svg>

            <h3 class="font-semibold text-red-800">
                خطاهای اعتبارسنجی
            </h3>
        </div>

        <ul class="space-y-2">
            @foreach ($errors->all() as $error)
                <li class="text-sm text-red-700">
                    • {{ $error }}
                </li>
            @endforeach
        </ul>
    </div>
@endif
