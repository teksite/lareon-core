@props(['title'=>null , 'required' =>false])
<label {{$attributes->merge(['class'=>'input_label'])}}>
    {!! $title ?? $slot ?? '' !!}
    @if($required)
        <span class="text-red-600 text-xs font-bold">*</span>
    @endif
</label>
