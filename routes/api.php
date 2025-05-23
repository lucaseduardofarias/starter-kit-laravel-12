<?php

use App\Http\Controllers\Asaas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::group(['prefix' => 'asass'], function () {
    Route::post('client', Asaas\Customer\CreateController::class);
    Route::put('client/{id}', Asaas\Customer\UpdateController::class);
});
