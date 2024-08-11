<?php

use App\Http\Controllers\GuestsController;
use Illuminate\Support\Facades\Route;

Route::post('guests', [GuestsController::class, 'createGuest']);
Route::get('guests/{id}', [GuestsController::class, 'getGuest']);
Route::get('guests', [GuestsController::class, 'getGuest']);
Route::put('guests/{id}', [GuestsController::class, 'updateGuest']);
Route::delete('guests/{id}', [GuestsController::class, 'deleteGuest']);
