<x-lareon::admin-layout>
    @section('title', __('export'))
    @section('description', __('export form submissions to excel'))

   <x-lareon::box type="y">
       <form action="{{route('admin.questionnaire.export.execute')}}" method="POST">
           @csrf
           <div class="grid md::grid-cols-2 lg:grid-cols-3 gap-6 items-end">
               <div class="md:col-span-2 lg:col-span-1">
                   <x-lareon::editor.input-select name="form" id="form_title" class="block w-full" :label="__('form')">
                       @foreach($forms as $form)
                           <option value="{{$form->id}}">
                               {{$form->title}}
                           </option>
                       @endforeach
                   </x-lareon::editor.input-select>
               </div>
               <div class="flex items-center gap-3">
                   <x-lareon::editor.input-date :label="__('from date')" id="fromDate" name="date[start]" type="date"/>
                   <x-lareon::editor.input-date :label="__('until date')" id="toDate" name="end" type="date"/>
               </div>

           </div>
           <div class="flex items-center justify-end self-end mt-6">
               <x-lareon::buttons.nav class="min-w-24" :fullWidth="false" type="submit">
                   {{ __('export') }}
               </x-lareon::buttons.nav>
           </div>
       </form>
   </x-lareon::box>

</x-lareon::admin-layout>
