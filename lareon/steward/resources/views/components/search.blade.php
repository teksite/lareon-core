@props([
    'name' => 's',
    'placeholder' => trans('search') . '...',
    'value' => null,
    'buttonIcon' => 'magnifier',
    'showReset' => true,
    'resetText' => 'all',
    'variant' => 'default', // default, minimal, rounded
    'size' => 'md', // sm, md, lg
])

@php
    $value = $value ?? request($name);
    $hasValue = filled($value);

    $variants = [
        'default' => 'border border-zinc-300 rounded-lg focus-within:ring-2 focus-within:ring-blue-500 focus-within:border-blue-500',
        'minimal' => 'border-b border-zinc-300 focus-within:border-blue-500',
        'rounded' => 'border border-zinc-300 rounded-full focus-within:ring-2 focus-within:ring-blue-500',
    ];

    $sizes = [
        'sm' => 'ps-2 py-0.5 text-sm',
        'md' => 'ps-3 py-1 text-base',
        'lg' => 'ps-4 py-2 text-lg',
    ];

    $iconSize = match($size) {
        'sm' => 16,
        'lg' => 24,
        default => 20,
    };

    $variantClass = $variants[$variant] ?? $variants['default'];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $wrapperClasses = "flex items-center gap-1 w-full bg-transparent transition-all duration-200 {$variantClass} {$sizeClass}";
    $inputClasses = "block w-full outline-none bg-transparent text-zinc-700 placeholder:text-zinc-400";
@endphp

<form action="{{ url()->current() }}" method="GET" class="w-full">
    <div class="{{ $wrapperClasses }}">
        <input type="text" name="{{ $name }}" value="{{ $value }}" placeholder="{{ __($placeholder) }}..." class="{{ $inputClasses }}" autocomplete="off">

        <div class="flex items-center gap-1 shrink-0">
            <button type="submit" title="{{ __($placeholder) }}" class="flex items-center justify-center px-2 py-1 text-zinc-400 hover:text-blue-600 transition-colors duration-200 {{ $hasValue ? 'text-blue-600' : '' }}">
                <x-icon type="outline" icon="{{ $buttonIcon }}" size="{{ $iconSize }}"/>
            </button>

            @if($showReset && $hasValue)
                <a href="{{ request()->fullUrlWithoutQuery($name) }}" title="{{ __($resetText) }}" class="flex items-center justify-center w-6 h-6 text-zinc-400 hover:text-red-600 hover:bg-red-50 rounded-full transition-all duration-200">
                    ✕
                </a>
            @endif
        </div>
    </div>
</form>
