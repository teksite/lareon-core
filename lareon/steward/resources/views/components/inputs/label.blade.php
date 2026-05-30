@props(['title'=>null , 'markAsRequire' =>false])
<label {{$attributes->merge(['class'=>'input_label'])}}>
    {!! $title ?? $slot ?? '' !!}
    @if($markAsRequire)
        <span class="text-red-600 text-xs font-bold">*</span>
    @endif
</label>
