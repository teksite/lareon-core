@foreach((new \Lareon\Modules\Auth\App\Logics\OAuthLogic())->enables()->result as $key=>$value)
<a href="{{route('auth.oauth.redirect',['provider'=>$key])}}" {{$attributes->merge()}} title="{{__('by :title',['title'=>__($key)])}}">
    <img src="{{asset('assets/images/'.$key.'-icon.svg') }}" alt="{{__('by :title',['title'=>__($key)])}}" width="24" height="24">
</a>
@endforeach
