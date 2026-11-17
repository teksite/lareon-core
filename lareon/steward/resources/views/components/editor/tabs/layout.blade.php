
@props(['hasTab' => true])

@if($hasTab)
    <div
        x-data="{ activeTab: 0, tabs: [], links: [] }"
        x-init="
            $nextTick(() => {
                const children = Array.from($refs.tabContainer.children);

                tabs = children
                    .filter(child => child.classList.contains('tab-item'))
                    .map((tab, index) => tab.dataset.title || 'Tab ' + (index + 1));

                links = children
                    .filter(child => child.classList.contains('tab-link'))
                    .map(link => ({
                        title: link.dataset.title,
                        url: link.dataset.url,
                        target: link.dataset.target || '_self'
                    }));
            })
        "
    >
        <div class="mx-auto w-full md:w-fit mb-4 y-box p-2 overflow-hidden">
            <div class="flex items-center justify-center gap-2 overflow-x-auto">

                {{-- Tabs --}}
                <template x-for="(tab, index) in tabs" :key="index">
                    <button
                        type="button"
                        @click="activeTab = index"
                        :class="activeTab === index
                            ? 'text-second_color_dark font-semibold bg-gray-100'
                            : 'text-gray-600'"
                        class="rounded-lg px-4 py-1 whitespace-nowrap transition-colors duration-200"
                        x-text="tab"
                    ></button>
                </template>

                {{-- Links --}}
                <template x-for="(link, index) in links" :key="index">
                    <a
                        :href="link.url"
                        :target="link.target"
                        rel="noopener noreferrer"
                        class="rounded-lg px-4 py-1 text-gray-600 hover:bg-gray-100 whitespace-nowrap transition-colors duration-200"
                        x-text="link.title"
                    ></a>
                </template>

            </div>
        </div>

        <div class="editor tab-contents space-y-6" x-ref="tabContainer">
            {{ $slot }}
        </div>
    </div>

    @pushonce('footerScripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.editor.tab-contents').forEach(container => {
                    container.querySelectorAll(':scope > .tab-item').forEach((el, i) => {
                        el.setAttribute('x-show', `activeTab === ${i}`);
                        el.removeAttribute('style');
                    });
                });
            });
        </script>
    @endpushonce

@else
    {!! $slot !!}
@endif
