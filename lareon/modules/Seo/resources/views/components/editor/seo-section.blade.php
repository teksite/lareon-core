<x-lareon::box>
    <div x-data="{ tabs: [
        { id: 1, title: 'meta tags', active: true },
        { id: 2, title: 'schema', active: false },
        { id: 3, title: 'sitemap', active: false },
      ], activeTab: 1 }">
    <section x-show="tabs.find(tab => tab.id === 1).active">
        @if($meta)
            <x-seo::editor.sections.meta-tag :data="$value['meta'] ?? []"/>
        @else
            <x-seo::editor.sections.not-data/>
        @endif
    </section>
    <section x-show="tabs.find(tab => tab.id === 2).active">
        @if($schema)
            <x-seo::sections.instance.schema :data="$value['schema'] ?? []"/>
        @else
            <x-seo::sections.instance.not-active/>
        @endif
    </section>
    <section x-show="tabs.find(tab => tab.id === 3).active">
        @if($sitemap)

            <x-seo::sections.instance.sitemap :data="$value['sitemap'] ?? []"/>
        @else
            <x-seo::sections.instance.not-active/>
        @endif
    </section>
    </div>
</x-lareon::box>
