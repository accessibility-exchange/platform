<?php

use App\Http\Controllers\NotificationListController;

Route::controller(NotificationListController::class)
    ->name('notification-list.')
    ->group(function () {
        Route::multilingual('/settings/notifications/list', 'show')
            ->middleware(['auth', 'can:receiveNotifications'])
            ->name('show');

        Route::multilingual('/settings/notifications/list/add', 'add')
            ->method('post')
            ->middleware(['auth', 'can:receiveNotifications'])
            ->name('add');

        Route::multilingual('/settings/notifications/list/remove', 'remove')
            ->method('post')
            ->middleware(['auth', 'can:receiveNotifications'])
            ->name('remove');
    });
