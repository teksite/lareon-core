@props([
    'type' => 'show',
    'href' => null,
    'method' => null, // GET, POST, PUT, PATCH, DELETE
    'size' => 'md',
    'label' => null,
    'confirm' => false,
    'target' => null,
    'can' => [null],
])

@php
    $configs = [
        'show' => [
            'icon' => 'eye',
            'color' => 'text-violet-600 hover:bg-violet-50',
            'title' => trans('show'),
            'target' => '_blank',
        ],
        'edit' => [
            'icon' => 'pen',
            'color' => 'text-amber-600 hover:bg-amber-50',
            'title' => trans('edit'),
            'target' => '_self',
        ],
        'delete' => [
            'icon' => 'trash',
            'color' => 'text-red-600 hover:bg-red-50',
            'title' => trans('delete'),
            'target' => '_self',
        ],
        'restore' => [
            'icon' => 'recycle',
            'color' => 'text-green-600 hover:bg-green-50',
            'title' => trans('restore'),
            'target' => '_self',
        ],
        'create' => [
            'icon' => 'database',
            'color' => 'text-emerald-600 hover:bg-emerald-50',
            'title' => trans('create'),
            'target' => '_self',
        ],
        'sub' => [
            'icon' => 'box-arrow-in',
            'color' => 'text-purple-600 hover:bg-purple-50',
            'title' => trans('sub Items'),
            'target' => '_self',
        ],
        'download' => [
            'icon' => 'cloud-download',
            'color' => 'text-indigo-600 hover:bg-indigo-50',
            'title' => trans('download'),
            'target' => '_blank',
        ],
        'setting' => [
            'icon' => 'gears',
            'color' => 'text-orange-600 hover:bg-orange-50',
            'title' => trans('settings'),
            'target' => '_self',
        ],
    ];

    $config = $configs[$type] ?? [
        'icon' => 'link',
        'color' => 'text-gray-600 hover:bg-gray-50',
        'title' => trans('link'),
        'target' => '_blank',
    ];


    $sizes = [
        'sm' => 'p-1 text-xs',
        'md' => 'p-1.5 text-sm',
        'lg' => 'p-2 text-base',
    ];

    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $title = $label ?? $config['title'];
    $target = $target ?? ($href ? $config['target'] : null);
    $hasForm = $method && in_array(strtoupper($method), ['POST', 'PUT', 'PATCH', 'DELETE']);

    $baseClasses = "inline-flex items-center gap-1 rounded-md transition-colors duration-200 {$sizeClass} {$config['color']} focus:outline-none focus:ring-2 focus:ring-offset-1";
    $attributes = $attributes->merge(['class' => $baseClasses, 'title' => $title]);
@endphp

@canany((array) $can)
    @if($hasForm)
        <form action="{{ $href ?? '#' }}" method="POST" class="inline">
            @csrf
            @method($method)

            <button type="submit" {{ $attributes }} @if($confirm) data-action_confirm @endif data-type="{{$method}}">
                <x-icon type="outline" icon="{{ $config['icon'] }}" size="18" class="fill-none stroke-current" />
                @if($label)
                    <span class="text-sm">{{ $label }}</span>
                @endif
            </button>
        </form>
    @elseif($href)
        <a href="{{ $href }}" {{ $attributes }} @if($target) target="{{ $target }}" @endif  @if($confirm) data-action_confirm @endif data-type="{{$method}}">
            <x-icon type="outline" icon="{{ $config['icon'] }}" size="18" class="fill-none stroke-current" />
            @if($label)
                <span class="text-sm">{{ $label }}</span>
            @endif
        </a>
    @else
        <button type="button" {{ $attributes }} @if($confirm) data-action_confirm @endif data-type="{{$method}}">
            <x-icon type="outline" icon="{{ $config['icon'] }}" size="18" class="fill-none stroke-current" />
            @if($label)
                <span class="text-sm">{{ $label }}</span>
            @endif
        </button>
    @endif
@endcanany
