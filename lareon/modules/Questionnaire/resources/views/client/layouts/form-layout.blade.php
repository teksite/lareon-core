@props(['page'=>null])
@php
   $classed=isset($ajax) && $ajax ? 'formMode' : '';
@endphp
<form id="form-{{$form->id}}" {{$attributes->merge(['class'=>"$classed"])}} action="{{route('client.submitting.form')}}" method="POST" id="{{uuid_create().rand(10,100)}}" {{$form->has_file ? 'enctype="multipart/form-data"' : ''}}>
    @csrf
    <input type="hidden" value="{{encrypt($form->id)}}" name="data_info[identify]"  readonly>
    <input type="hidden" class="hidden" name="data_info[url]" value="{{url()->current()}}" readonly>
    <input type="hidden" class="hidden" name="data_info[page_title]" value="{{$page ?? __(config('app.name'))}}">
    <input type="text" class="hidden" name="{{config('extralaravel.honeypot.field_name', 'honeypot')}}">
    @if($form->template)
        <div>
            @include("questionnaire.forms.$form->template")
        </div>
    @elseif($form->body)
        <div>
            {!! $form->body !!}
        </div>
    @else
        <div>
            {!! $slot !!}
        </div>
    @endif
    <x-captcha::load />
    @if(isset($button))
        {!! $button !!}
    @else
        <div class="mt-6">
            <button size="md" :title="__('submit')" role="button" type="submit" color="main" class="block w-full">
                {{__('submit')}}
            </button>
        </div>
    @endif
    <div class="response-box">
    @if ($errors->any())
        @if($form->id == decrypt(old('data_info.identify')))
            <hr class="my-3">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li class="text-red-700 font-bold text-sm">{{ $error }}</li>
                @endforeach
            </ul>
            <hr class="my-3">
        @endif
    @endif
    </div>
</form>
