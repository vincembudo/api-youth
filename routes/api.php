<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\YouthController;
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

//Protected
Route::group(['middleware'=>['auth:sanctum']], function () {
    Route::post('/logout',[AuthController::class,'logout']);
    Route::post('/youth/{id}/create',[YouthController::class,'store']);
    Route::get('/youth/{id}/show',[YouthController::class,'show']);
    Route::put('/youth/{id}/update',[YouthController::class,'update']);
    Route::delete('/youth/{id}/destroy',[YouthController::class,'destroy']);
});

//USER
Route::get('/youths',[YouthController::class,'youths']);
Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

