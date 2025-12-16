<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {
    Route::get('status', function () {
        return response()->json(['status' => 'API is running']);
    });
});
