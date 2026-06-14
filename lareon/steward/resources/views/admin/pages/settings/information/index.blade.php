<x-lareon::admin-layout>
    @section('title', __('information'))
    @section('description', __('this page is a brief overview of the application and the system it is running on'))
    <x-lareon::editor.tabs.layout>

        <x-lareon::editor.tabs.item title="PHP">
            <x-lareon::tables.simple :data="$phpInfo" />
        </x-lareon::editor.tabs.item>

        <x-lareon::editor.tabs.item title="LARAVEL">
            <x-lareon::tables.simple :data="$laravelInfo" />
        </x-lareon::editor.tabs.item>

        <x-lareon::editor.tabs.item title="DATABASE">
            <x-lareon::tables.simple :data="$databaseInfo" />
        </x-lareon::editor.tabs.item>

        <x-lareon::editor.tabs.item title="SYSTEM INFORMATION">
            <x-lareon::tables.simple :data="$sysInfo" />
        </x-lareon::editor.tabs.item>

    </x-lareon::editor.tabs.layout>

</x-lareon::admin-layout>
