@props(['items'])
@if($items->count())
    @foreach($items->sortBy('position') as $item)
        <div class="list-group-item" id="menu_item-{{$item->id}}">
            <div x-data="{open:false , text1: ''}" class="item y-box p-1  rounded overflow-hidden menu_item" data-id="{{$item->id}}">
                <button type="button" class="w-full flex items-center gap-3 overflow-hidden outline-none"
                        :class="open ? 'rounded-t' : 'rounded'"
                        @click="open=!open">
                    <span class="handle self-stretch px-2 py-1 bg-gray-400 cursor-all-scroll">✢</span>
                    <span class="text-sm">{{$item->title}}</span>
                    <i :class="{'!-rotate-90':open}" class="tkicon ease-in-out transition-all icon-accordion" size="9"
                       data-icon="angle-left"></i>
                </button>
                <div>
                    <div x-show='open' x-cloak x-transition class="px-3">
                        <div class="visible_fields">
                            <div class="grid md:grid-cols-2 gap-3">
                                <x-lareon::editor.input class="w-full block" name="items[{{$item->id}}][title]" :label="__('title')" :value='old("items.$item->id.title") ?? $item->title' id="title-{{$item->id}}"/>
                                <x-lareon::editor.input class="w-full block" name="items[{{$item->id}}][url]" dir="ltr" :label="__('url')" :value='old("items.$item->id.url") ?? $item->url ?? "" ' id="url-{{$item->id}}"/>
                            </div>
                        </div>
                        <div class="mb-3">
                            <x-lareon::editor.input id="subtitle-{{$item->id}}" class="w-full block" :label="__('subtitle')" name="items[{{$item->id}}][subtitle]" :value='old("items.$item->id.subtitle") ?? $item->subtitle ?? "" '/>
                        </div>
                        <div class="mb-3">
                            <x-lareon::editor.input id="classes-{{$item->id}}" class="w-full block" :label="__('classes')" name="items[{{$item->id}}][classes]" dir="ltr" :value='old("items.$item->id.classes") ?? $item->classes ?? "" '/>
                        </div>
                        <div class="grid gap-3 md:grid-cols-2 mb-3">
                            <x-lareon::editor.input id="preicon-{{$item->id}}" class="w-full block" :label="__('pre icon')" name="items[{{$item->id}}][pre_icon]" dir="ltr" :value='old("items.$item->id.pre_icon") ?? $item->pre_icon ?? "" '/>
                            <x-lareon::editor.input id="nexticon-{{$item->id}}" class="w-full block" :label="__('next icon')" name="items[{{$item->id}}][next_icon]" dir="ltr" :value='old("items.$item->id.next_icon") ?? $item->next_icon ?? "" '/>
                        </div>

                        <div class="mb-3">
                            <x-lareon::editor.input id="image-{{$item->id}}" class="w-full block" :label="__('image')" name="items[{{$item->id}}][image]" dir="ltr" :value='old("items.$item->id.image") ?? $item->image ?? "" '/>
                        </div>
                        <button type="button" class="text-red-700 delete-menu-item my-3" id="delete-{{$item->id}}"
                                data-for="{{$item->id}}">{{__('delete')}}
                        </button>
                    </div>
                    <div class="hidden hidden_fields">
                        <input type="hidden" name="items[{{$item->id}}][parent_id]" value="{{$item->parent_id}}" class="parent_id">
                        <input type="hidden" name="items[{{$item->id}}][id]" value="{{$item->id}}" class="item_id">
                        <input type="hidden" class="position-item" name="items[{{$item->id}}][position]" value="{{$item->position}}">
                    </div>
                </div>
            </div>
        </div>
        <div class="list-group nested-sortable ps-6" data-parent_id="{{$item->id}}">
            <x-menu::sections.menu-item :items="$item->children"/>
        </div>

    @endforeach
@endif

