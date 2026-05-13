<x-lareon::admin-layout>
    @props(['type'=>'create' ,'instance'=>null , 'publishStatus'=>true])
    @section('title') @yield('title') @endsection

    @section('description') @yield('description') @endsection

    @section('header.end')
        <x-lareon::button.solid type="submit" role="submit" class="block w-full" onclick="document.getElementById('createForm').submit()" :color="$type ==='update' || $type=='put' ? 'blue' : ($type=='delete' ? __('red') : __('green'))">
            {{$type=='update' || $type=='put' ? __('update') : ($type=='delete' ? __('delete') : __('create'))}}
        </x-lareon::button.solid>
    @endsection

    @yield('form.before')
    <form method="POST" action="@yield('formRoute')" id="createForm">
        <div class="grid md:grid-cols-3 lg:grid-cols-3 xl:grid-cols-7 gap-6 mb-6">
            <div class="md:col-span-2 lg:col-span-2 xl:col-span-5 flex flex-col gap-6">
                <div>
                    @yield('form.after.start')
                        @csrf
                        @if($type=='update')
                            @method('PATCH')
                        @elseif($type=='put')
                            @method('PUT')
                        @elseif($type=='delete')
                            @method('DELETE')
                        @endif
                       <div class="flex flex-col gap-6">
                           @yield('form')
                       </div>
                    @yield('form.before.end')
                </div>
            </div>
               <div class="xl:col-span-2 w-full">
                   <div class="flex flex-col gap-6 h-full">
                       @yield('aside')
                       @if($instance)
                           <x-lareon::sections.publish-data :instance="$instance"/>
                       @endif
                       <div class="mt-3  sticky top-3">
                           <x-lareon::button.solid type="submit" role="submit" class="block w-full" :color="$type ==='update' || $type=='put' ? 'blue' : ($type=='delete' ? __('red') : __('green'))">
                               {{$type=='update' || $type=='put' ? __('update') : ($type=='delete' ? __('delete') : __('create'))}}
                           </x-lareon::button.solid>
                       </div>
                   </div>
               </div>
        </div>
    </form>
    @yield('form.after')

</x-lareon::admin-layout>
