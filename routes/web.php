<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('index');
});

Route::get('/app', function () {
    return view('app');
});

Route::get('/admin', function () {
    return view('admin');
});

Route::get('/join/{token}', function ($token) {
    return view('app', ['inviteToken' => $token]);
});
