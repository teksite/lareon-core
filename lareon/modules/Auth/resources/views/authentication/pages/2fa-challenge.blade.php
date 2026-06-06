<x-auth::layout :title="trans('lareon::global.auth.sign_in')">
    @php
        $tabs=[
          ['id'=>1 , 'title'=>'TOTP' , 'content'=>'totp'],
          ['id'=>2 , 'title'=>'RECOVERY CODE' , 'content'=>'recovery'],
          ['id'=>3 , 'title'=>'OTP' , 'content'=>'otp'],
        ];

    @endphp
    <section class="w-11/12 md:2-3/4 mx-auto my-3">

        <div class="text-center">
            <x-icon type="outline" icon="user" size="32" class="mx-auto mb-3"></x-icon>
            <h1 class="text-center !mb-0 text-xl">{{__('lareon::global.auth.2fa')}}</h1>
        </div>
        <div x-data="{activeTab: 1}" class="mt-12">
            <div class="flex border-b border-gray-200 mb-6">

                @foreach($tabs as $tab)
                    <button type="button" @click="activeTab = {{$tab['id']}}"
                            :class="{'border-blue-600 text-blue-600': activeTab == '{{$tab['id']}}', 'border-transparent text-gray-600 ': activeTab != '{{$tab['id']}}'}"
                            class="flex-1 py-2 px-3 text-center text-sm font-medium border-b-2 transition duration-150 hover:text-blue-900">
                        <x-icon type="outline" icon="key" size="20" class="inline mr-2"></x-icon>
                        {{$tab['title']}}
                    </button>
                @endforeach
            </div>
            <div>
                @foreach($tabs as $tab)
                    @include("auth::authentication.pages.partials.{$tab['content']}")
                @endforeach
          </div>
      </div>
    </section>

</x-auth::layout>
