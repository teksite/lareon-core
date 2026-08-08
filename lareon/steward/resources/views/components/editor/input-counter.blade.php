@props([
    'name',
    'min' => 0,
    'max' => null,
])

<div {{ $attributes->merge(['class'=>'flex gap-3 items-center']) }}
     x-data="{
        count: 0,
        min: {{ (int) $min }},
        max: {{ $max !== null ? (int) $max : 'null' }},
        input: null,

        get percentage() {
            if (!this.max || this.max <= 0) return 0;
            return Math.min((this.count / this.max) * 100, 100);
        },

        get state() {
            if (this.count < this.min) return 'under';
            if (this.max !== null && this.count > this.max)return 'over';
            return 'valid';
        },

        update() {
            this.count = this.input?.value?.length ?? 0;
        },

        init() {
            this.input = document.querySelector(`[name='{{ $name }}']`);
            if (!this.input) return;
            this.update();
            this.input.addEventListener('input', this.update.bind(this));
            this.$cleanup = () => {
            this.input?.removeEventListener( 'input', this.update.bind(this) );
            };
        }
   }"
>
    {{-- Counter --}}
    <div class="flex items-center justify-start text-xs w-16 min-w-16">
        <span class="font-medium" :class="{'text-yellow-600': state === 'under','text-green-600': state === 'valid','text-red-600': state === 'over',}" x-text="count"></span>
        @if($max !== null)
            <span class="text-gray-600">/ {{ $max }}</span>
        @endif
    </div>
    {{-- Progress --}}
    <div class="h-2 w-full overflow-hidden rounded-full bg-gray-300">
        <div class="h-full rounded-full transition-all duration-200" :class="{'bg-yellow-600': state === 'under','bg-green-600': state === 'valid','bg-red-600': state === 'over',}" :style="`width: ${percentage}%`"></div>
    </div>

</div>
