<?php

use App\Http\Controllers\Client;
use App\Http\Controllers\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'asass'], function () {
    Route::get('client/search/{cpf_cnpj}', Client\IndexController::class);
    Route::post('client', Client\CreateController::class);
    Route::put('client/{id}', Client\UpdateController::class);
    Route::get('client/{id}/payments', Client\Payment\IndexController::class);
    Route::get('client/{client_id}/payment/{payment_id}', Payment\ShowController::class);
    Route::post('client/{id}/payments', Payment\CreateController::class);
    Route::get('client/{id}/cards', Client\Card\IndexController::class);
});
