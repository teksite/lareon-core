@props(['id' => 'editor-form','action' => null,'method' => 'POST','hasFile' => false,])

<form id="{{ $id }}" class="inner-content" action="{{ $action ?? url()->current() }}" method="{{ strtoupper($method) === 'GET' ? 'GET' : 'POST' }}" @if($hasFile) enctype="multipart/form-data" @endif>
    @csrf
    @unless(in_array(strtoupper($method), ['GET','POST']))
        @method($method)
    @endunless

    {{ $slot }}

</form>
