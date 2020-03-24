<?php

use Illuminate\Events\Dispatcher;

$site_controller = '\MonstreX\VoyagerSite\Http\Controllers\VoyagerSiteController';

Route::middleware(['web'])->group(function () use ($site_controller) {
    Route::post('/api/send-form', $site_controller . '@sendForm')->name('send.form');
});

app(Dispatcher::class)->listen('voyager.admin.routing', function ($router) use($site_controller) {
    $this->addRoutes($router, $site_controller);
});
