<?php

use Illuminate\Support\Facades\Route;
use Laraditz\Twilio\Http\Controllers\WebhookController;

Route::prefix('webhooks')->name('webhooks.')->group(function () {
    Route::match(['get', 'post'], '/receive', [WebhookController::class, 'receive'])->name('receive');
    Route::match(['get', 'post'], '/status', [WebhookController::class, 'status'])->name('status');
});
