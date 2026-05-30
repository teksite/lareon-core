@props(['title', 'index' => 0])

<div x-show="activeTab === {{ $index }}" style="display:none" class="tab-item">
    {{ $slot }}
</div>
