<?php

use Illuminate\Support\Facades\Route;

Route::get('/debug', function () {
    return response()->json([
        'APP_URL' => env('APP_URL'),
        'PORT' => env('PORT'),
        'DB_HOST' => env('DB_HOST'),
        'DB_PORT' => env('DB_PORT'),
        'DB_DATABASE' => env('DB_DATABASE'),
        'DB_USERNAME' => env('DB_USERNAME'),
    ]);
});
