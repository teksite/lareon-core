<div class="p-0.5 bg-slate-50 mb-6 flex flex-col-reverse sm:flex-row items-center justify-between">
    <div class="w-full sm:min-w-fit sm:w-fit sm:max-fit flex items-center justify-between sm:justify-start gap-3">
        <div class="flex items-center justify-start gap-1 text-slate-600 font-semibold text-sm">
            @yield('nav')
        </div>

    </div>
    <hr class="border-dotted border-gray-300 w-full md:my-0 my-1">
    <div class="flex gap-3 min-w-fit w-full sm:w-fit items-center justify-between p-3">
        @if(isset($moduleData) && is_array($moduleData))
            <div class="flex items-center gap-3">
                @foreach($moduleData as $data )
                    <div>
                        <a href="{{$data['link']}}" class="flex items-center gap-1 bg-slate-100 p-1 rounded-lg shadow">
                            @isset($data['icon'])
                                <x-tkicon type="outline" icon="{{$data['icon']}}"/>
                            @elseif($data['title'])
                                <span>
                                  {{$data['title']}}
                              </span>
                            @endisset
                            <span class="bg-red-600 text-zinc-50 rounded-full flex items-center justify-center text-center text-xs font-bold p-1 aspect-square ">
                                {{$data['count'] ?? 0}}
                            </span>
                        </a>
                    </div>
                @endforeach
            </div>
        @endisset
        <div class="flex items-center gap-3">
            @if(\Illuminate\Support\Facades\Route::has('admin.setlang'))
                <a href="{{route('admin.setlang')}}" class="justify-self-start">
                    Fa\En
                </a>
            @endif
            <a href="/">
                <x-tkicon type="outline" icon="world" size="20" />
            </a>
            @can('admin')
                <a href="{{route('admin.dashboard')}}">
                    <x-tkicon type="outline" icon="gear" size="20" />
                </a>
            @endcan
        </div>
        <button class="hover:cursor-pointer" type="button" role="switch" @click="togglesSidebar()">
            <x-tkicon type="outline" icon="bar-3" size="20" />
        </button>
    </div>
</div>
