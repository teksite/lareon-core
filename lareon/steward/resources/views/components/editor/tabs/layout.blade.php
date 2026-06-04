@props([])

<div x-data="{  activeTab: 0, tabs: []  }" x-init=" $nextTick(() => {
        tabs = Array.from($refs.tabContainer.children).filter(child =>
            child.classList?.contains('tab-item')
        ).map(tab => tab.dataset.title || 'tab ' + (tabs.length + 1))
    }) ">
    <div class="flex items-end justify-center flex-wrap mb-4">
        <template x-for="(tab, index) in tabs" :key="index">
            <button type="button" @click="activeTab = index" :class="activeTab === index ? 'border-blue-600 text-blue-600 font-semibold' : 'border-gray-300 text-gray-600'"  class="px-4 py-2 border-b-2 transition-colors duration-200 w-fit min-w-fit" x-text="tab">
            </button>
        </template>
    </div>

    <div class="tab-contents space-y-6" x-ref="tabContainer">
        {{ $slot }}
    </div>
</div>
@pushonce('footerScripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.tab-contents .tab-item').forEach((el, i) => {
                el.setAttribute('x-show', `activeTab === ${i}`)
                el.removeAttribute('style')
            })
        })
    </script>
@endpushonce
