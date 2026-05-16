<div class="p-0.5 mb-6 flex flex-col sm:flex-row items-center justify-between">
    <div class="w-full sm:min-w-fit sm:w-fit sm:max-fit flex items-center justify-between sm:justify-start gap-3">
        <ul class="flex items-center justify-start gap-1 text-slate-600 font-semibold text-sm">
            <a href="{{route('admin.dashboard')}}" class="">
                    {{__('dashboard')}}
            </a>
        </ul>
        @if(isset($moduleData) && is_array($moduleData))
            <div class="flex items-center gap-3">
                @foreach($moduleData as $data )
                    <div>
                        <a href="{{$data['link']}}" class="flex items-center gap-1 bg-slate-100 p-1 rounded-lg shadow">
                            @isset($data['icon'])
                                <i class="tkicon" data-icon="{{$data['icon']}}"></i>
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
    </div>
    <hr class="border-dotted border-gray-300 w-full md:my-0 my-1">
    <div class="w-full sm:min-w-fit sm:w-fit sm:max-fit inline-flex justify-between md:justify-end items-center gap-6 btn-stroke bg-slate-50">
        @if(\Illuminate\Support\Facades\Route::has('admin.setlang'))
            <a href="{{route('admin.setlang')}}" class="justify-self-start">
                Fa\En
            </a>
        @endif
        <div class="flex items-center gap-1">
            <a href="/">
                <i class="tkicon" data-icon="world"></i>
            </a>
            @if(\Illuminate\Support\Facades\Route::has('panel.dashboard'))
                <a href="{{route('panel.dashboard')}}">
                    <i class="tkicon" data-icon="user"></i>
                </a>
            @endif
            <button class="hover:cursor-pointer" type="button" role="switch" @click="togglesSidebar()">
                <i class="tkicon" data-icon="bar-3"></i>
            </button>
        </div>
    </div>
</div>
