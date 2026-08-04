<?php

use Illuminate\Support\Facades\Route;
use Lareon\Modules\Seo\App\Http\Controllers\Web\Admin\Seo\RobotFileController;
use Lareon\Modules\Seo\App\Http\Controllers\Web\Admin\Seo\SeoSiteController;
use Lareon\Modules\Seo\App\Http\Controllers\Web\Admin\Seo\SitemapController;

Route::prefix('seo')->name('seo.')->group(function () {
    Route::name('site.')->prefix('site')->group(function () {
        Route::get('/', [SeoSiteController::class, 'edit'])->name('edit');
        Route::patch('/', [SeoSiteController::class, 'update'])->name('update');
    });
    Route::name('robot.')->prefix('robot.txt')->group(function () {
        Route::get('/', [RobotFileController::class, 'edit'])->name('edit');
        Route::patch('/', [RobotFileController::class, 'update'])->name('update');
    });
    Route::name('sitemaps.')->prefix('sitemap')->group(function () {
        Route::get('/', [SitemapController::class, 'edit'])->name('edit');
        Route::patch('/', [SitemapController::class, 'update'])->name('update');
    });
    Route::name('redirects.')->prefix('redirects')->group(function () {
        Route::get('/', [SitemapController::class, 'edit'])->name('edit');
        Route::patch('/', [SitemapController::class, 'update'])->name('update');
    });
});
