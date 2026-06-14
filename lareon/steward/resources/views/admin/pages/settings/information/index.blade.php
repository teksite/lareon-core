<x-lareon::admin-layout>
    <x-lareon::editor.tabs.layout>
        <x-lareon::editor.tabs.item title="PHP">
            <x-lareon::tables.simple :body="$phpInfo" />
        </x-lareon::editor.tabs.item>
        <x-lareon::editor.tabs.item title="LARAVEL">
            <x-lareon::tables.simple :body="$laravelInfo" />
        </x-lareon::editor.tabs.item>
    </x-lareon::editor.tabs.layout>

</x-lareon::admin-layout>
