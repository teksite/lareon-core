<x-lareon::panel-layout>
    @section('title', __('lareon::global.crud.titles.list',['attribute'=>__('notifications')]))

    <div class="p-3 bordering rounded-xl my-12 flex items-center justify-start gap-3">
        <a href="?read=1" class="bg-white rounded-lg w-24 min-w-fit py-1 px-3 text-xs shadow text-center {{request()->integer('read')===1 ? 'text-blue-600 font-bold' : 'text-gray-600'}}">{{__('read before')}}</a>
        <a href="?read=0" class="bg-white rounded-lg w-24 min-w-fit py-1 px-3 text-xs shadow text-center {{request()->integer('read')===0 ? 'text-blue-600 font-bold' : 'text-gray-600'}}">{{__('not read yet')}}</a>
    </div>
    <div class="inner-content space-y-3 notifications-list">
        @foreach($notifications as $notification)
            <x-notifier::sections.notification-item :notification="$notification"/>
        @endforeach
        {!! $notifications->appends(request()->query())->links() !!}
    </div>

    @once
        @push('footerScripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    document.querySelector('.notifications-list')?.addEventListener('click', e => {
                        const btnEl = e.currentTarget;
                        if (!btnEl) return;

                        console.log(btnEl);

                        return;
                        const boxEl = btnEl.closest('[data-notification-box]')
                        if (boxEl) boxEl.style.display = 'none';
                        try {
                            const res = fetch('/panel/ajax/client-submit/notification/read', {
                                method: 'POST',
                                headers: {
                                    'content-type': 'application/json',
                                    'accept': 'application/json',
                                },
                                body: JSON.stringify({'notification': btn.getAttribute('data-id')}),
                            });
                            if (res.status === 200) boxEl.remove();
                        } catch (e) {
                            console.error(e);
                            boxEl.style.display = 'block';
                        }
                    });
                })
            </script>
        @endpush
    @endonce

</x-lareon::panel-layout>
