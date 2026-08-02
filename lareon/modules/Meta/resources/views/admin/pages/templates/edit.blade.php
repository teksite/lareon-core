<x-lareon::admin-layout>
    @pushonce('headerScripts')
        @vite(['lareon/modules/Meta/resources/js/app.js','lareon/modules/Meta/resources/css/app.css'])
    @endpushonce
    @section('title', __('lareon::global.crud.titles.edit_current',['attribute'=>__('template') , 'item'=>($template->title)]))
    @section('header.start')
        <x-lareon::links.nav :href="route('admin.settings.meta.templates.index')" :content="__('lareon::global.buttons.all_attribute' ,['attribute'=>__('templates')])" color="index" can="admin.meta.template.read"/>
    @endsection
    @section('header.end')
        <x-lareon::links.action type="delete" :href="route('admin.settings.meta.templates.destroy', $template)" method="delete" :label="trans('lareon::global.buttons.delete')" can="admin.meta.template.delete"/>
    @endsection

    <form method="POST" action="{{route('admin.settings.meta.templates.update', $template)}}" class="space-y-6">
        @method('PATCH')
        @csrf
        <x-lareon::editor.tabs.section>
            <div class="grid gap-6 lg:grid-cols-2">
                <x-lareon::editor.input :required="true" :label="__('title')" name="title" :value="$template->title" :placeholder="__('lareon::global.placeholders.write.unique.two',['attribute'=>__('title') , 'item'=>__('template')])"/>
            </div>
        </x-lareon::editor.tabs.section>

        <section class="flex flex-col lg:flex-row gap-6">
            <div class="w-full lg:max-w-[350px]">
                <fieldset class="p-3 rounded-lg bordering">
                    <legend class="px-3">
                        {{__('elements')}}
                    </legend>

                    <div id="elements-list" class="space-y-6" data-elmenet-list="elements-list">
                        @foreach($elements as $element)
                            @php
                                $args = json_encode(($element->settings)['args']['items'] ?? []);
                            @endphp
                            <x-lareon::box data-element-item data-element-id="{{ $element->id }}" data-arguments="{{$args}}">
                                <div class="flex items-center justify-between gap-3">
                                    <div class="flex items-center justify-start gap-3 handler" data-element-handler="handler">
                                        <x-tkicon icon="arrow-move" class="size-4 stroke-2"/>
                                        {{ $element->title }}
                                    </div>
                                    <button class="text-blue-600 text-xs font-semibold outline-none hover:bg-blue-50 rounded p-0.5"
                                            type="button" role="button" data-add-element>
                                        {{__('add')}} ->
                                    </button>
                                </div>
                            </x-lareon::box>
                        @endforeach
                    </div>
                </fieldset>
            </div>
            <fieldset class="w-full p-3 rounded-lg bordering">
                <legend class="px-3">
                    {{__('items')}}
                </legend>
                <div id="template-elements-list" data-initial-elements="{{ json_encode(
                        $template->elements?->map(fn ($el) => [
                            'element_id' => $el->id,
                            'name'       => $el->pivot->name,
                            'title'      => $el->pivot->title,
                            'args'       => $el->pivot->settings
                                ? (is_array($el->pivot->settings)
                                    ? $el->pivot->settings
                                    : json_decode($el->pivot->settings, true))
                                : [],
                        ])->values()
                    ) }}">
                </div>

            </fieldset>
        </section>
        <x-lareon::buttons.nav class="w-24" :fullWidth="false" type="submit" role="submit" color="create">
            {{ __('update')}}
        </x-lareon::buttons.nav>

    </form>
</x-lareon::admin-layout>
