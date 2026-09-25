@php($random = rand(100, 999))
<div class="grid gap-6 md:grid-cols-2">
    <div class="mb-6">
        <x-input.label for="name-{{$random}}" :title="__('name')"/>
        <x-input.text id="name-{{$random}}" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required/>
        <x-input.error :messages="$errors->get('name')" class="mt-2"/>
    </div>
    <div class="mb-6">
        <x-input.label for="company-{{$random}}" :title="__('company')"/>
        <x-input.text id="company-{{$random}}" class="block mt-1 w-full" type="text" name="company" :value="old('company')"
                      required/>
        <x-input.error :messages="$errors->get('company')" class="mt-2"/>
    </div>
</div>

<div class="mb-6">
    <x-input.label for="email-{{$random}}" :title="__('email')"/>
    <x-input.text id="email-{{$random}}" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                  required/>
    <x-input.error :messages="$errors->get('email')" class="mt-2"/>
</div>

<div class="mb-6">
    <x-input.label for="phone-{{$random}}" :title="__('phone')"/>
    <x-input.text id="phone-{{$random}}" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required/>
    <x-input.error :messages="$errors->get('phone')" class="mt-2"/>
</div>

<div class="mb-6">
    <x-input.label for="message-{{$random}}" :title="__('message')"/>
    <x-input.textarea id="message-{{$random}}" class="block mt-1 w-full" rows="6" max="512" name="message" required>{{old('message')}}</x-input.textarea>
    <x-input.error :messages="$errors->get('message')" class="mt-2"/>
</div>
