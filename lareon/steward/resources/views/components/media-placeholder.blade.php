@props(['alt'=>'image' , 'src'=>null , 'width'=>60, 'height'=>110, 'type'=>'image'])

<img src="{{$src ?? ($type==='image' ? asset('assets/images/image-default.avif') : asset('assets/images/avatar-default.avif'))}}" alt="{{$alt}}" width="{{$width ?? 60}}" height="{{$height ?? 110}}" fetchpriority="low" decoding="async" loading="lazy">


