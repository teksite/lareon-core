<x-lareon::admin-layout>
    @section('title', __('forms analytics'))
    @section('description', __('analytics help provide a comprehensive overview at a glance'))

    <form action="{{ route('admin.questionnaire.analytics.show') }}" method="GET" id="inboxLineChartForm">
        <x-lareon::box class="flex items-center gap-3 mb-12">
            <div>
                <x-lareon::editor.input-date :label="__('from date')" id="fromDate" name="fromDate" type="date" :value="request()->fromDate ?? now()->startOfMonth()->format('Y-m-d')"/>
                <x-lareon::editor.input-date :label="__('until date')" id="toDate" name="toDate" type="date" :value="request()->toDate ?? now()->endOfMonth()->format('Y-m-d')"/>
                <x-lareon::editor.input-select id="range" name="range" :label="__('range')">
                    <option value="month">{{ __('month') }}</option>
                    <option value="year">{{ __('year') }}</option>
                    <option value="week">{{ __('week') }}</option>
                    <option value="day">{{ __('day') }}</option>
                </x-lareon::editor.input-select>
            </div>
            <x-lareon::buttons.nav class="min-w-36" :fullWidth="false" type="submit" color="blue" >
                {{ 'submit' }}
            </x-lareon::buttons.nav>
        </x-lareon::box>
    </form>

    <div id="inboxLineChart" class="w-full h-[400px]"></div>
    @push('headerScripts')
        @vite(['lareon/modules/Questionnaire/resources/js/app.js'])
    @endpush

</x-lareon::admin-layout>
