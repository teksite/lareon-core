<div class="">
    <div class="inline-flex items-stretch bg-white border border-zinc-300 rounded-lg overflow-hidden">
        <input type="text" name="g-recaptcha-response" id="captcha-code" class="focus:outline-none px-1 w-full text-black!" required>
        <label for="captcha-code" class="min-w-fit"></label>
        <button aria-label="{{__('new captcha code')}}" type="button" role="button" class="py-1 px-3 reload-captcha-btn transition-all duration-300 ease-in-out outline-none focus:outline-none" title="{{__('reload')}}">
            {!! captcha_img('math', ['id' => 'captcha-img', 'alt' => 'captcha']) !!}
        </button>
    </div>
    @error('g-recaptcha-response')
        <div class="text-sm text-red-600 space-y-1 mt-1">{{ $message }}</div>
    @enderror
</div>
@push('footerScripts')
    @vite(['lareon/modules/Captcha/resources/js/app.js'])
@endpush
