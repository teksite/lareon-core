@props(['tabs' => []])

<div x-data="{ activeTab: 0 }" class="tab-contents">
    <div class="flex gap-2  mb-4">
        @foreach($tabs as $index => $tab)
            <button role="button" type="button"
                @click="activeTab = {{ $index }}"
                :class="activeTab === {{ $index }} ? 'border-b-2 border-blue-500 font-semibold' : 'text-gray-500'"
                class="px-4 py-2 transition-colors"
            >
                {{ $tab }}
            </button>
        @endforeach
    </div>

    <div class="tab-contents">
        {{ $slot }}
    </div>
</div>
@push('footerScripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.tab-contents .tab-item').forEach((el, i) => {
                el.setAttribute('x-show', `activeTab === ${i}`)
                el.removeAttribute('style')
            })
        })
    </script>
@endpush
