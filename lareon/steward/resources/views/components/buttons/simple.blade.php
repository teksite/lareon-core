@props(['type' => 'button','size' => 'md','variant' => 'solid','color' => 'primary','loading' => false,'disabled' => false,'fullWidth' => false,'rounded' => 'md', 'content'=>null , 'can'=>[null]])
@php
    // Size classes
  $sizes = [
        'xs' => 'px-2.5 py-1.5 text-xs',
        'sm' => 'px-3 py-2 text-sm',
        'md' => 'px-4 py-2.5 text-sm',
        'lg' => 'px-5 py-3 text-base',
        'xl' => 'px-6 py-3.5 text-base',
    ];

    // Color variants
    $colors = [
        'blue' => [
            'solid' => 'shadow-lg shadow-blue-600/50 bg-blue-600 hover:bg-blue-700 active:bg-blue-900 text-white hover:shadow-sm focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
            'outline' => 'border-2 border-blue-600 text-blue-600 hover:bg-blue-50 active:bg-blue-100 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2',
        ],
        'gray' => [
            'solid' => 'shadow-lg shadow-gray-600/50 bg-gray-600 hover:bg-gray-700 active:bg-gray-900 text-white hover:shadow-sm focus:ring-2 focus:ring-gray-500 focus:ring-offset-2',
            'outline' => 'border-2 border-gray-600 text-gray-600 hover:bg-gray-50 active:bg-gray-100 focus:ring-2 focus:ring-gray-500 focus:ring-offset-2',
        ],
        'green' => [
            'solid' => 'shadow-lg shadow-green-600/50 bg-green-600 hover:bg-green-700 active:bg-green-900 text-white hover:shadow-sm focus:ring-2 focus:ring-green-500 focus:ring-offset-2',
            'outline' => 'border-2 border-green-600 text-green-600 hover:bg-green-50 active:bg-green-100 focus:ring-2 focus:ring-green-500 focus:ring-offset-2',
        ],
        'red' => [
            'solid' => 'shadow-lg shadow-red-600/50 bg-red-600 hover:bg-red-700 active:bg-red-900 text-white hover:shadow-sm focus:ring-2 focus:ring-red-500 focus:ring-offset-2',
            'outline' => 'border-2 border-red-600 text-red-600 hover:bg-red-50 active:bg-red-100 focus:ring-2 focus:ring-red-500 focus:ring-offset-2',
        ],
        'yellow' => [
            'solid' => 'shadow-lg shadow-yellow-500/50 bg-yellow-500 hover:bg-yellow-600 active:bg-yellow-700 text-white hover:shadow-sm focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2',
            'outline' => 'border-2 border-yellow-600 text-yellow-600 hover:bg-yellow-50 active:bg-yellow-100 focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2',
        ],
        'cyan' => [
            'solid' => 'shadow-lg shadow-cyan-600/50 bg-cyan-600 hover:bg-cyan-700 active:bg-cyan-900 text-white hover:shadow-sm focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2',
            'outline' => 'border-2 border-cyan-600 text-cyan-600 hover:bg-cyan-50 active:bg-cyan-100 focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2',
        ],
        'black' => [
            'solid' => 'shadow-lg shadow-gray-900/50 bg-gray-900 hover:bg-gray-900 active:bg-gray-950 text-white hover:shadow-sm focus:ring-2 focus:ring-gray-700 focus:ring-offset-2',
            'outline' => 'border-2 border-gray-900 text-gray-900 hover:bg-gray-50 active:bg-gray-100 focus:ring-2 focus:ring-gray-700 focus:ring-offset-2',
        ],
    ];

    $roundedClasses = [
        'none' => 'rounded-none',
        'sm' => 'rounded-sm',
        'md' => 'rounded-md',
        'lg' => 'rounded-lg',
        'xl' => 'rounded-xl',
        'full' => 'rounded-full',
    ];

   $baseClasses = 'inline-flex items-center justify-center font-medium transition-all duration-200 ease-in-out focus:outline-none disabled:opacity-50 disabled:cursor-not-allowed disabled:pointer-events-none';
    $sizeClass = $sizes[$size] ?? $sizes['sm'];
    $colorClass = $colors[$color][$variant] ?? $colors['blue']['solid'];
    $roundedClass = $roundedClasses[$rounded] ?? $roundedClasses['lg'];
    $widthClass = $fullWidth ? 'w-full' : '';

    $classes = trim("{$baseClasses} {$sizeClass} {$colorClass} {$roundedClass} {$widthClass}");

    $content =$content ?? ($slot->isNotEmpty() ? $slot : '');
@endphp

@canany((array)$can)
    <button type="{{ $type }}" class="{{ $classes }}" {{ $disabled ? 'disabled' : '' }} {{ $attributes }}>
        {!! $content !!}
    </button>
@endcanany
