<?php

use Illuminate\Support\Facades\Route;
use StevenFox\Larashurl\Facades\Larashurl;
use StevenFox\Larashurl\Http\Web\Controllers\ShortUrlVisitController;

$config = Larashurl::config();
if ($config->disableDefaultRouting()) {
    return;
}

// Public Web Routes
Route::group([
    'domain' => $config->routeDomain(),
    'prefix' => $config->routePrefix(),
    'middleware' => 'larashurl',
], function () {
    Route::get('/{urlKey}', ShortUrlVisitController::class)
        ->name('web.short-url-visit');
});
