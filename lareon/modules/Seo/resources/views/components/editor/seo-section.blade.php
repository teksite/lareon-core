@props(['value'])
<div>
    <div x-data="{ tabs: [
        { id: 1, title: 'meta tags', active: true },
        { id: 2, title: 'schema', active: false },
        { id: 3, title: 'sitemap', active: false },
      ], activeTab: 1 }" class="flex flex-col items-start lg:flex-row gap-3">
        <nav class="w-full lg:min-w-[200px] lg:w-[200px] relative flex rounded-lg lg:flex-col overflow-hidden z-0 rounded-t-lg" aria-label="Tabs">
            <template x-for="(tab, ix) in tabs" :key="tab.id">
                <button role="button" type="button" :class="tab.active ? 'bg-slate-50' : 'bg-slate-200'"
                   class="group relative min-w-0 flex-1 overflow-hidden p-3  bordering text-center hover:bg-slate-50 focus:z-10" :aria-current="tab.active ? 'page' : 'undefined'"
                   @click.prevent="tabs.forEach(tab => tab.active = false); tabs[ix].active = true">
                    <span x-text="tab.title" class="tab.active ? 'text-black' : 'text-gray-600'"></span>
                </button>
            </template>
        </nav>
        <div class="w-full">
            <section x-show="tabs.find(tab => tab.id === 1).active">
                <x-seo::editor.sections.meta-tag :data="$value['meta'] ?? []"/>
            </section>
            <section x-show="tabs.find(tab => tab.id === 2).active">
                <x-seo::editor.sections.schema-tag :data="$value['schema'] ?? []"/>
            </section>
            <section x-show="tabs.find(tab => tab.id === 3).active">
                <x-seo::editor.sections.sitemap :data="$value['sitemap'] ?? []"/>
            </section>
        </div>
    </div>
</div>
