<x-lareon::admin-layout>
    @section('title', __('lareon::global.crud.titles.edit',['attribute'=>__('oauth')]))

    <div class="gap-6 grid md:grid-cols-2">
        @foreach($data as $item)
            <x-lareon::box type="y" class="overflow-hidden">
                <h3 class="capitalize">
                    {{__($item)}}
                </h3>
                <hr class="bordering my-3">
                    <div class="space-y-3">
                        @foreach(config("services.{$item}") as $key=>$value)
                            <div class="">
                                <div>
                                    <strong>{{$key}}</strong>
                                </div>
                                <div dir="ltr" class="text-start text-wrap wrap-break-word">
                                    {{$value}}
                                </div>
                            </div>
                        @endforeach
                    </div>
            </x-lareon::box>
        @endforeach
    </div>

</x-lareon::admin-layout>
