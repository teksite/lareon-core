@props(['date'=>null ,'ifEmpty' =>'-'])
<span dir="ltr">
    @if(is_null($date))
        {{$ifEmpty ?? ''}}
    @else
       <span title="{{$date}}" dir="ltr" >
            {{dateAdapter($date) ?? '-'}}
       </span>
    @endif
</span>
