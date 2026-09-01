@props(['hasTab'=>true])
@if($hasTab)
    <div x-data="{ activeTab: 0, tabs: [] }" x-init=" $nextTick(() => { tabs = Array.from($refs.tabContainer.children).filter(child => child.classList?.contains('tab-item') ).map(tab => tab.dataset.title || 'tab ' + (tabs.length + 1)) }) ">
        <div class="flex-1 mx-auto w-full md:w-fit flex items-end justify-center flex-wrap mb-4 bg-slate-300 rounded-2xl p-1 overflow-hidden">
            <template x-for="(tab, index) in tabs" :key="index">
                <button type="button" @click="activeTab = index" :class="activeTab === index ? ' text-second_color_dark font-semibold bg-gray-50 rounded-2xl' : 'text-gray-600'" class="px-4 outline-none select-none py-1 transition-colors duration-200 w-fit min-w-fit overflow-hidden" x-text="tab"></button>
            </template>
        </div>
        <div class="editor tab-contents space-y-6" x-ref="tabContainer"> {{ $slot }} </div>
    </div>

    @pushonce('footerScripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.editor.tab-contents .tab-item').forEach((el, i) => {
                    el.setAttribute('x-show', `activeTab === ${i}`)
                    el.removeAttribute('style')
                })
            })
        </script>
    @endpushonce
@else
    {!! $slot !!}
@endif
