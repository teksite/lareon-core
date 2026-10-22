<x-lareon::admin-list>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('tickets')]))
    @section('description', __('referrals are fixed content on a website that rarely changes and displays the same information to visitors'))

    @section('list')
        <div class="space-y-6">
            @foreach ($pending as $assignment)
                <div class="y-box" id="ticket_{{$assignment->ticket->id}}">
                    <div class="flex items-start justify-between gap-3 mb-2">
                        <div class="text-xs text-gray-600 mb-1">
                            <span> {{ $assignment->ticket->ticket_number }} </span> |
                            <span>{{__('refer from')}}: {{ $assignment->fromAdmin?->name ?? __('system').'/'.__('ai') }}</span> |
                            <span dir="ltr">
                                    {{ $assignment->created_at->diffForHumans() }}
                            </span>
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <x-lareon::links.action type="show" :href="route('admin.tickets.referrals.show', $assignment->ticket)" can="admin.ticket.read"/>
                            <x-ticketing::priority-pulse :priority="$assignment->ticket->priority"/>
                        </div>
                    </div>

                   <div class="mb-3">
                        <span class="block font-bold mb-1 text-lg">
                            {{ $assignment->ticket->subject }}
                        </span>
                       <p class="">
                          <span class="text-gray-600 text-sm ">{{__('reason for refer')}}:</span> <span class="font-bold">{{ $assignment->reason }}</span>
                       </p>
                   </div>

                    <div class="flex items-start justify-between gap-6">
                        <form method="POST" action="{{ route('admin.tickets.referrals.accept', $assignment) }}">
                            @csrf
                            <button type="submit"
                                    class="rounded-lg bg-teal-700 text-white text-sm font-medium px-4 py-2 hover:bg-teal-800">
                               {{__('accept')}}
                            </button>
                        </form>
                        <details class="flex-1">
                            <summary class="cursor-pointer text-sm text-red-600 hover:text-red-900 select-none text-end">
                                {{__('reject')}}
                            </summary>
                            <form method="POST" action="{{ route('admin.tickets.referrals.reject', $assignment) }}" class="mt-3 space-y-3 bg-red-50/50 border border-red-600 rounded-lg p-3">
                                @csrf
                                    <x-lareon::editor.input-textarea  rows="2" :required="true" :label="__('reason for reject')" name="decision_reason" :placeholder="__('lareon::global.placeholders.write.one',['attribute'=>__('reason for refer')])"  required minlength="5" maxlength="2000"></x-lareon::editor.input-textarea>
                                <div>
                                    <x-lareon::editor.input-select required :required="true" :label="__('refer to')" name="next_admin_id">
                                        <option value="" disabled selected>— {{__('choose')}} —</option>
                                    @if ($assignment->fromAdmin)
                                        <option value="{{ $assignment->fromAdmin->id }}">
                                            {{ $assignment->fromAdmin->name }} ({{__('previous admin')}})
                                        </option>
                                    @endif
                                    @foreach($admins as $admin)
                                            <option value="{{$admin->id}}">{{$admin->name}}</option>
                                        @endforeach
                                    </x-lareon::editor.input-select>
                                </div>

                                <x-lareon::buttons.nav :fullWidth="false" color="delete" variant="outline" size="xs">
                                    {{ __('rejection and re-referral') }}
                                </x-lareon::buttons.nav>
                            </form>
                        </details>
                    </div>
                </div>
            @endforeach
        </div>

        {!! $pending->appends(request()->query())->links() !!}

    @endsection
</x-lareon::admin-list>





