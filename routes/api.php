<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/exemplo', function (Request $request) {
    return json_encode([
        'exemplo' => 'Exemplo de rota',
    ]);
});
