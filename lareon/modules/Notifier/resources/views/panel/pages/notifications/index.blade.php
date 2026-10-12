<x-lareon::panel-layout>
    @section('title', __('lareon::global.crud.titles.list', [     'attribute' => __('notifications'), ]))

    <div class="p-3 bordering rounded-xl my-12 flex items-center justify-start gap-3">
        <a href="?read=1" class="bg-white rounded-lg w-24 min-w-fit py-1 px-3 text-xs shadow text-center    {{ request()->has('read') && request()->integer('read') === 1        ? 'text-blue-600 font-bold'        : 'text-gray-600' }}">
            {{ __('read before') }}
        </a>
        <a href="?read=0" class="bg-white rounded-lg w-24 min-w-fit py-1 px-3 text-xs shadow text-center    {{ request()->has('read') && request()->integer('read') === 0        ? 'text-blue-600 font-bold'        : 'text-gray-600' }}">
            {{ __('not read yet') }}
        </a>
    </div>

    <div class="inner-content space-y-3 notifications-list">
        @foreach($notifications as $notification)
            <x-notifier::sections.notification-item :notification="$notification"/>
        @endforeach
        {{ $notifications->appends(request()->query())->links() }}
    </div>

    @once
        @push('footerScripts')
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    const list = document.querySelector('.notifications-list');
                    if (!list) return;

                    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

                    list.addEventListener('click', async (event) => {
                        const target = event.target;
                        if (!(target instanceof Element)) return;

                        const btn = target.closest('button[data-type="markAsRead"]');
                        if (!btn || !list.contains(btn)) return;

                        const box = btn.closest('[data-notification-box]');
                        if (!box || btn.disabled) return;

                        const notificationId = btn.dataset.id;

                        if (!notificationId) {
                            console.error('Notification ID is missing.');
                            return;
                        }
                        btn.disabled = true;
                        try {
                            const response = await fetch(
                                '/panel/ajax/client-submit/notification/read',
                                {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json',
                                        ...(csrfToken && {
                                            'X-CSRF-TOKEN': csrfToken,
                                        }),
                                    },
                                    body: JSON.stringify({notification: notificationId}),
                                }
                            );

                            if (!response.ok) throw new Error(`Request failed: ${response.status}`);
                            box.remove();
                        } catch (error) {
                            console.error('Failed to mark notification as read:', error);
                            btn.disabled = false;


                        }
                    });
                });
            </script>
        @endpush
    @endonce
</x-lareon::panel-layout>
