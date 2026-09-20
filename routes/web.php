<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->file(resource_path('views/index.blade.php'), ['Content-Type' => 'text/html']);
});

Route::get('/app', function () {
    return response()->file(resource_path('views/app.blade.php'), ['Content-Type' => 'text/html']);
});

Route::get('/admin', function () {
    return response()->file(resource_path('views/admin.blade.php'), ['Content-Type' => 'text/html']);
});

Route::get('/join/{token}', function ($token) {
    return response()->file(resource_path('views/app.blade.php'), ['Content-Type' => 'text/html']);
});
