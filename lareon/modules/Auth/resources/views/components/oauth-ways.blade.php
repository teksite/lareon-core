@php
 use Lareon\Modules\Auth\App\Providers\AuthServiceProvider;
$configs = [];

foreach(AuthServiceProvider::OauthType ?? [] as $item){
    $data = config("services.{$item}", []);
    if (count($data)) $configs[$item]=$data;
}

$items = array_filter($configs, function ($item){
   return isset($item['client_id']) && isset($item['client_secret']) && isset($item['redirect']) &&
          trim($item['client_id']) !== '' && trim($item['client_secret']) !== '' && trim($item['redirect']) !== '';
});

@endphp

@foreach($items as $key=>$value)
    <a href="{{route('auth.oauth.redirect',['provider'=>$key])}}" {{$attributes->merge()}} title="{{__('by :title',['title'=>__($key)])}}">
        <img src="{{asset('assets/images/'.$key.'-icon.svg') }}" alt="{{__('by :title',['title'=>__($key)])}}" width="24" height="24">
    </a>
@endforeach
