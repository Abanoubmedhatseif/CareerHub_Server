<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/checkAuth', function () {
    if (auth()->check()) {
        return response()->json(['message' => 'Authenticated']);
    }
    return response()->json(['message' => 'Not authenticated']);
});
