<?php

namespace Lareon\Modules\Notifier\App\Logics;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Notifications\DatabaseNotification;
use Lareon\Modules\User\App\Models\User;
use Lareon\Steward\App\Service\ContentSaverService;
use Teksite\Handler\Contracts\ServiceResultContract;
use Teksite\Handler\Facade\FetchData;
use Teksite\Handler\Services\ServiceWrapper;


class NotificationLogic
{

    /**
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    public function all(mixed $fetchData = [], Authenticatable|User $user = null)
    {
        return ServiceWrapper::make(false)->do(function () {
            return FetchData::get(DatabaseNotification::class, ['title', 'read_at']);
        })->run();
    }


    /**
     * @throws \Throwable
     * @throws BindingResolutionException
     */
    public function allByUser(int $read = -1, Authenticatable|User $user = null)
    {
        $user ??= auth()->user();
        return ServiceWrapper::make(false)->do(function () use ($user, $read) {
            return FetchData::get($user->notifications()->where(function ($query) use ($read) {
                if ($read === 0) {
                    $query->whereNull('read_at');
                } elseif ($read === 1) {
                    $query->whereNotNull('read_at');
                }
            }), ['title']);
        })->run();
    }

    public function markAsRead(DatabaseNotification $notification)
    {
        return ServiceWrapper::make(true)->do(function () use ($notification) {
            $notification->markAsRead();
            return true;
        })->run();
    }


    /**
     * @throws \Throwable
     */
    public function update(DatabaseNotification $notification, array $inputs = []): ServiceResultContract
    {
        return ServiceWrapper::make(true)->do(function () use ($notification, $inputs) {
            $data = $notification->data;
            $editorId = auth('admin')->id();

            $data['message']['title'] = $inputs['title'];
            $data['message']['body'] = $inputs['message'];
            $data['meta']['type'] = $inputs['type'] ?? null;
            $data['meta']['edits'][][$editorId] = [
                'edit_at' => now()->toDateTimeString(),
                'old'     => [
                    'title' => $data['message']['title'] ?? '',
                    'body'  => $data['message']['body'] ?? '',

                ],
                'new'     => [
                    'title' => $inputs['title'] ?? '',
                    'body'  => $inputs['message'] ?? '',
                ],
            ];


            $notification->update([
                'data' => $data,
            ]);

            return $notification;
        })->run();
    }


    /**
     * @throws \Throwable
     */
    public function delete(DatabaseNotification $notification): ServiceResultContract
    {
        return ServiceWrapper::make(false)->do(function () use ($notification) {
            return ContentSaverService::delete($notification);

        })->run();
    }

    protected function getModelClass(): string
    {
        return DatabaseNotification::class;
    }
}

