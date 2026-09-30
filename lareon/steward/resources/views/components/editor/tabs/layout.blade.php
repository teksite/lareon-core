@props(['hasTab'=>true])
@if($hasTab)
    <div x-data="{ activeTab: 0, tabs: [] }" x-init=" $nextTick(() => { tabs = Array.from($refs.tabContainer.children).filter(child => child.classList?.contains('tab-item') ).map(tab => tab.dataset.title || 'tab ' + (tabs.length + 1)) }) ">
        <div class=" mx-auto w-full md:w-fit mb-4 y-box p-2 overflow-hidden">
            <div class="flex-1 items-end flex justify-center overflow-x-auto">
                <template x-for="(tab, index) in tabs" :key="index">
                    <button type="button" @click="activeTab = index" :class="activeTab === index ? ' text-second_color_dark font-semibold bg-gray-100 ' : 'text-gray-600'" class="rounded-lg px-4 outline-none select-none py-1 transition-colors duration-200 w-fit min-w-fit overflow-hidden" x-text="tab"></button>
                </template>
            </div>
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
