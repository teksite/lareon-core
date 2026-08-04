@props(['data'=>[]])
<div class="space-y-6">
    <x-lareon::editor.input-radio type="inline" :required="true" :options="[[__('no') ,0 ] ,[__('yes') ,1] ]" :label="__('activating')" name="seo[website]state" inputsClass="flex items-center gap-1" :value="$data->state->value"/>
    <x-lareon::editor.input :required="true" :label="__('title')" name="seo[website][data][title]" :value="$data->value['title'] ?? null"/>
    <x-lareon::editor.input-textarea :required="true" :label="__('description')" name="seo[website][data][description]">{{$data->value['description'] ?? null}}</x-lareon::editor.input-textarea>
    <x-seo::lang :required="true" name="seo[website][data][language]" :value="$data->value['language']"/>
    <x-seo::currency :required="true" name="seo[website][data][currency]" :value="$data->value['currency']"/>


    {{--Lang--}}
    {{--   <tr>
           <th class="w-fit p-3 text-start">
               <x-lareon::input.label for="language" :title="__('language')"/>
           </th>
           <td class="w-full p-3">
               <x-lareon::input.select name="website[data][language]" id="language" :required="true">
                   @foreach(config('lang') as $codeLang=>$lang)
                       <option value="{{$codeLang}}"
                           {{(old('website.language' == $codeLang) ||  (isset($data->value['language']) && $data->value['language'] == $codeLang ) ) ? 'selected' : ''}}>
                           {{__($lang)}}
                       </option>
                   @endforeach
               </x-lareon::input.select>
               <x-lareon::input.error :messages="get_error($errors , 'website[data][language]')"/>
           </td>
       </tr>
       --}}{{--Currency--}}{{--
       <tr>
           <th class="w-fit p-3 text-start">
               <x-lareon::input.label for="currency" :title="__('currency')"/>
           </th>
           <td class="w-full p-3">
               <x-lareon::input.select id="currency" name="website[value][currency]" :required="true">
                   @foreach(config('currency') as $codeCurrency=>$currency)
                       <option value="{{$codeCurrency}}"
                           {{(old('website.currency' == $codeCurrency) || (isset($data->value['currency']) && $data->value['currency'] == $codeCurrency )) ? 'selected' : ''}}>
                           {{__($currency)}}
                       </option>
                   @endforeach
               </x-lareon::input.select>
               <x-lareon::input.error :messages="get_error($errors , 'website[value][currency]')"/>
       </td>--}}
</div>
