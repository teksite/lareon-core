<x-lareon::admin-layout>
    @section('title', __('caches'))
    @section('description', __("manage, clear, and optimize your application's caches from a single place"))
    <ul class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 items-stretch mt-12">
        @foreach($cacheTypes as $type)
            <li>
                <x-lareon::box type="y">
                    <h3 class="text-center">
                        {{$type->value}}
                    </h3>
                    <hr class="my-3 border-line_light">
                   <ul class="flex items-center gap-6 justify-center">
                       @foreach($type->actions() as $action)
                           @php($isStore=$action === \Lareon\Steward\App\Enums\CacheAction::STORE)
                          <li>
                              <form method="POST" action="{{ route('admin.settings.cache.execute') }}">
                                  @csrf
                                  <input type="hidden" name="type" value="{{ $type->value }}">
                                  <input type="hidden" name="action" value="{{ $action->value }}">
                                  <button type="submit" class="{{$isStore ? 'hover:bg-blue-100 hover:text-blue-600' : 'hover:bg-red-100 hover:text-red-600'}} text-center cursor-pointer bordering  text-shadow-slate-600 font-bold text-xs px-2 py-1 rounded-xl">
                                      <x-icon type="outline" class="fill-none stroke-current mx-auto mb-1" icon="{{$isStore ? 'box-arrow-in' : 'trash'}}"/>
                                      {{ $action->label() }}
                                  </button>

                              </form>
                          </li>
                       @endforeach
                   </ul>
                </x-lareon::box>
            </li>
        @endforeach

    </ul>

</x-lareon::admin-layout>
