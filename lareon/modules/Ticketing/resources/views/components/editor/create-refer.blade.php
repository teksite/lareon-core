@props(['ticket' , 'admins'=>null])
@php
    $adminId = auth('admin')->id();
    $admins ??= \Lareon\Steward\App\Models\Admin::query()->where('id', '!=', $adminId)->orderBy('name')->get(['id', 'name']);
@endphp

<x-lareon::editor.tabs.item>
    <div class="grid md:grid-cols-2 gap-6 y-box font-bold">
        <div class="flex items-center gap-3 ">
            <span class="">{{ __('subject') }}:</span>
            <span class="">{{ $ticket->subject }}</span>
        </div>

        <div class="flex items-center gap-3" dir="ltr">
            <spa>#{{$ticket->ticket_number}}</spa>
        </div>

    </div>

    <x-lareon::editor.tabs.section>
        <x-lareon::editor.input-textarea :required="true" :label="__('reason for refer')" name="reason" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('reason for refer')])"></x-lareon::editor.input-textarea>
    </x-lareon::editor.tabs.section>

    <x-slot:aside>
        <x-lareon::editor.tabs.section>
            <x-lareon::editor.input-select :required="true" :label="__('refer to')" name="to_admin_id">
                @foreach($admins as $admin)
                    <option value="{{$admin->id}}">{{$admin->name}}</option>
                @endforeach
            </x-lareon::editor.input-select>
        </x-lareon::editor.tabs.section>

    </x-slot:aside>
</x-lareon::editor.tabs.item>
