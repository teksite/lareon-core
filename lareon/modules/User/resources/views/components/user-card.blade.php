@props(['user'])
@php
    $items=[
        ['type'=>'tel:' , 'title'=>'phone' , 'value'=>$user->phone , 'isLink'=>true],
        ['type'=>'mailto:' , 'title'=>'phone' , 'value'=>$user->email , 'isLink'=>true],
        ['type'=>'' , 'title'=>'url' , 'value'=>$user->path() , 'isLink'=>true],
    ]
@endphp
<div class="y-box">
    <div class="bordering rounded-xl p-3 space-y-6">
        <figure class="mb-6">
            <img src="{{asset('assets/images/avatar-default.jpg')}}" alt="{{$user->fullname}}" width="100" height="100" class="rounded-full mx-auto" loading="lazy">
            <figcaption class="block text-center mt-3 font-bold">
                {{$user->fullname ?? $user->name}}
            </figcaption>
        </figure>
        <div class="text-center">
           <span class="px-3 py-1 rounded bg-slate-300" title="{{__('roles')}}">
               {{$user->roles->pluck('title')->implode(',')}}
           </span>
        </div>
        <span class="font-bold">
            {{__('details')}}
        </span>
        <hr class="border-line_light my-1">
        <table class="w-full">
            <tbody>
            @foreach($items as $item)
                <tr>
                    <td class="text-sm text-gray-400 p-3 font-bold">
                        {{__($item['title'])}}
                    </td>
                    <td class="p-3 text-end font-bold">
                        @if($item['isLink'] && isset($item['value']))
                            <a href="{{$item['type']}}{{$item['value']}}">
                                {{$item['value']}}
                            </a>
                        @else
                            {{$item['value'] ?? '-'}}
                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
