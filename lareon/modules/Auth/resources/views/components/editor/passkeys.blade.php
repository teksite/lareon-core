@props(['passkeys'=>[]])
@if($passkeys->isEmpty())
    <x-lareon::box type="y" class="mb-6 text-center text-sm text-gray-500">
        {{ __('no passkeys registered yet') }}.
    </x-lareon::box>
@else
    <x-lareon::box type="y" class="mb-6">
        <ul class="divide-y divide-line_light">
            @foreach($passkeys as $passkey)
                <li class="flex justify-between items-center py-3">
                    <div class="min-w-0">
                        <div class="font-medium truncate">
                            {{ $passkey->name }}
                        </div>

                        <div class="text-xs text-gray-500 mt-1">
                            last used:
                            <x-lareon::date :date="$passkey->last_used_at"/>
                        </div>
                    </div>
                    <x-lareon::links.action type="delete" method="delete" :href="route('panel.profile.passkeys.destroy', $passkey)" can="panel.profile.passkey"/>
                </li>
            @endforeach
        </ul>
    </x-lareon::box>
@endif
