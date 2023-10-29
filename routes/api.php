<?php

use App\Http\Controllers\Auth\RegisterController as RegisterController;
use App\Http\Controllers\PatternController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::apiResource('channels', \App\Http\Controllers\ChannelController::class);
Route::apiResource('entity_infos', \App\Http\Controllers\EntityInfoController::class);
Route::apiResource('reviews', \App\Http\Controllers\ReviewController::class);
Route::apiResource('orders',\App\Http\Controllers\OrderController::class);

