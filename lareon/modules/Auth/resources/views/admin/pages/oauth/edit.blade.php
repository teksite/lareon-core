<x-lareon::admin-editor method="update" :action="route('admin.settings.oauth.update')" :hasTab="false">
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('oauth')]))
    @section('form')
        <x-lareon::editor.tabs.item :title="__('via')" class="mt-12">
            <div class="gap-6 grid md:grid-cols-2">
                @foreach(config('auth.oauth.types') ?? config('auth.oauth.types') ?? [] as $type=>$detail)
                    <x-lareon::editor.tabs.section>
                        <h3 class="capitalize">
                            {{__($type)}}
                        </h3>
                        <hr class="bordering my-3">
                        <x-lareon::editor.input-radio type="inline" :required="true" :options="[[__('no') ,0 ] ,[__('yes') ,1] ]" :label="__('activating')" name="oauth[{{$type}}][enable]" inputsClass="flex items-center gap-1" :value="old('oauth.'.$type.'.enable') ?? $data[$type]['enable'] ??  0"/>
                        <div class="flex flec-col md:flex-row gap-6">
                            <x-lareon::editor.input :value="old('oauth.'.$type.'.secret_key') ?? $data[$type]['secret_key'] ?? ''" :label="__('secret key')" name="oauth[{{$type}}][secret_key]" :required="false"/>
                            <x-lareon::editor.input :value="old('oauth.'.$type.'.client_id') ?? $data[$type]['client_id'] ?? ''" :label="__('client id')" name="oauth[{{$type}}][client_id]" :required="false"/>
                        </div>
                    </x-lareon::editor.tabs.section>
                @endforeach
            </div>

        </x-lareon::editor.tabs.item>
    @endsection
</x-lareon::admin-editor>
