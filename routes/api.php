<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\MessageController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('messages')->controller(MessageController::class)
    ->name('api.message.')
    ->group(function () {
        Route::get('', 'index')->name('index');
        Route::get('{message}', 'show')->name('show');
        Route::post('', 'store')->name('store');
        Route::delete('{message}', 'destroy')->name('destroy');
    });
