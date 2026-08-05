<x-lareon::admin-editor type="update" method="patch" :hasTab="true" :action="route('admin.seo.site.update')" :publishInfo="false" :publishStatus="false">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('seo of site')]))

    @section('form')

        <x-lareon::editor.tabs.item :title="__('website')">
            <x-lareon::editor.tabs.section>
                <x-lareon::editor.input-radio type="inline" :required="true" :options="[[__('no') ,0 ] ,[__('yes') ,1] ]" :label="__('activating')" name="seo[website_state]" inputsClass="flex items-center gap-1" :value="$website?->state?->value ?? 0"/>
                <x-seo::editor.sections.website :value="$website->value ?? []"/>
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.section>


        <x-lareon::editor.tabs.item :title="__('local business')">
            <x-lareon::editor.tabs.section>
                <x-lareon::editor.input-radio type="inline" :required="true" :options="[[__('no') ,0 ] ,[__('yes') ,1] ]" :label="__('activating')" name="seo[localBusiness_state]" inputsClass="flex items-center gap-1" :value="$localBusiness?->state?->value ?? 0"/>
                <x-seo::editor.sections.localBusiness :value="$localBusiness->value ?? []" />
            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.section>


        <x-lareon::editor.tabs.item :title="__('organization')">
            <x-lareon::editor.tabs.section>

            </x-lareon::editor.tabs.section>
        </x-lareon::editor.tabs.section>
    @endsection
</x-lareon::admin-editor>
