@props(['name','show' => false,'maxWidth' => '2xl','id' => null,])
@php
    $id = $id ?? 'modal-' . \Illuminate\Support\Str::uuid();
    $maxWidthClasses = [
        'sm' => 'sm:max-w-sm',
        'md' => 'sm:max-w-md',
        'lg' => 'sm:max-w-lg',
        'xl' => 'sm:max-w-xl',
        '2xl' => 'sm:max-w-2xl',
        '3xl' => 'sm:max-w-3xl',
        '5xl' => 'sm:max-w-5xl',
    ];

    $maxWidthClass = $maxWidthClasses[$maxWidth] ?? $maxWidthClasses['2xl'];
@endphp

<div
    id="{{ $id }}"
    x-data="modal('{{ $name }}', {{ json_encode($show) }})"
    x-init="init()"
    x-cloak
    x-show="isOpen"
    x-on:open-modal.window="open($event.detail)"
    x-on:close-modal.window="close()"
    x-on:keydown.escape.window="close()"
    class="fixed inset-0 z-50 flex items-center justify-center px-3 sm:px-0"
>
    {{-- Overlay --}}
    <div
        x-show="isOpen"
        x-transition.opacity
        class="fixed inset-0 bg-black/70 backdrop-blur-sm"
        x-on:click="close()"
    ></div>

    {{-- Modal --}}
    <div x-show="isOpen" x-transition class="relative w-full {{ $maxWidthClass }} bg-slate-50 rounded-xl p-3 border bordering  overflow-hidden">
        <button type="button" class="absolute top-3 right-3 text-zinc-400 hover:text-red-500 transition" x-on:click="close()">
            ✕
        </button>
        <div class="p-6">
            {{ $slot }}
        </div>
    </div>
</div>

@pushonce('footerScripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('modal', (name, initialShow) => ({
                name,
                isOpen: initialShow,

                init() {
                    this.toggleBodyScroll()
                    this.$watch('isOpen', () => this.toggleBodyScroll())
                },

                open(detail) {
                    if (detail === this.name) this.isOpen = true
                },

                close() {
                    this.isOpen = false
                },

                toggleBodyScroll() {
                    document.body.classList.toggle('overflow-hidden', this.isOpen)
                }
            }))
        })
    </script>

@endpushonce
