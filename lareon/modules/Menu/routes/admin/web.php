<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Menu\App\Http\Controllers\Web\Admin\Menus\MenusController;
use Lareon\Modules\Menu\App\Http\Controllers\Web\Admin\Menus\SubMenusController;


Route::prefix('appearance')->name('appearance.')->group(function () {

    Route::prefix('menus')->name('menus.sub.')->scopeBindings()->group(function (){
        Route::get('{menu}/items', [SubMenusController::class, 'index'])->scopeBindings()->name('index');
        Route::post('{menu}/items', [SubMenusController::class, 'store'])->scopeBindings()->name('store');
        Route::patch('{menu}/items', [SubMenusController::class, 'update'])->scopeBindings()->name('update');
    });

    Route::resource('menus', MenusController::class);
});
