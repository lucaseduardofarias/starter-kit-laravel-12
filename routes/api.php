<?php

use App\Http\Controllers\Asaas;
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

Route::post('asass/client', Asaas\CreateCustomersController::class);
