<?php

use Illuminate\Support\Facades\Route;
use Kejubayer\Steadfast\Http\Controllers\SteadfastWebhookController;

Route::middleware(config('steadfast.webhook.middleware', ['api']))
    ->post(config('steadfast.webhook.path', 'steadfast/webhook'), SteadfastWebhookController::class)
    ->name('steadfast.webhook');
