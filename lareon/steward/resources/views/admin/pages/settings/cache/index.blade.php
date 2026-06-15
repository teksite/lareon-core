<x-lareon::admin-layout>
    @section('title', __('information'))
    @section('description', __('this page is a brief overview of the application and the system it is running on'))
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
                          <li>
                              <form method="POST" action="{{ route('admin.settings.cache.execute') }}">
                                  @csrf
                                  <input type="hidden" name="type" value="{{ $type->value }}">
                                  <input type="hidden" name="action" value="{{ $action->value }}">
                                  <button type="submit" class="cursor-pointer bordering hover:bg-slate-100 text-shadow-slate-600 font-bold text-xs px-2 py-1 rounded-xl">
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
