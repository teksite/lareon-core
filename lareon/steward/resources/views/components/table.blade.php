@props([
    'headers' => [],
    'rows' => null,
    'sortable' => true,
    'sortColumn' => null,
    'sortDirection' => 'asc',
    'emptyMessage' => 'No data available',
])

@php
    use Illuminate\Support\Arr;

    $sortColumn = $sortColumn ?? request('order');
    $sortDirection = request('sort', 'asc') ?? $sortDirection;
    $nextDirection = $sortDirection === 'asc' ? 'desc' : 'asc';
    $sortUrl = fn($column) => url()->current() . '?' . Arr::query(array_merge(
        request()->except(['order', 'sort']),
        ['order' => $column, 'sort' => $nextDirection]
    ));
    $sortIcon = function($column) use ($sortColumn, $sortDirection) {
        if ($sortColumn !== $column) return '↕️';
        return $sortDirection === 'asc' ? '↑' : '↓';
    };
@endphp

<div class="overflow-x-auto y-box !p-0">
    <table {{ $attributes->merge(['class' => 'min-w-full text-sm divide-y divide-line_light']) }}>
        <thead class="">
        <tr>
            @foreach($headers as $key => $header)
                <th scope="col" class="px-3 py-3 text-xs font-semibold uppercase text-start text-zinc-600">
                    @if($sortable && is_string($key))
                        <a href="{{ $sortUrl($key) }}" class="inline-flex items-center gap-1 hover:text-zinc-900 {{ $sortColumn === $key ? 'font-bold text-zinc-900' : '' }}">
                            {{ is_array($header) ? ($header['label'] ?? $key) : $header }}
                            <span class="text-xs">{{ $sortIcon($key) }}</span>
                        </a>
                    @else
                        {{ is_array($header) ? ($header['label'] ?? $key) : $header }}
                    @endif
                </th>
            @endforeach
        </tr>
        </thead>
        <tbody class="divide-y divide-line_light bg-slate-50 *:hover:bg-blue-50">
        @if($rows && count($rows) > 0)
            {{ $slot }}
        @else
            <tr>
                <td colspan="{{ count($headers) }}" class="px-3 py-8 text-center text-zinc-600">
                    {{ $emptyMessage }}
                </td>
            </tr>
        @endif
        </tbody>
        @if(isset($foot))
            <tfoot class="bg-zinc-50">
            {{ $foot }}
            </tfoot>
        @endif
    </table>
</div>
