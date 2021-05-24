<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('contacts',[ContactController::class,'index']);
Route::post('contacts',[ContactController::class,'store']);
Route::get('contacts/edit/{id}',[ContactController::class,'edit']);
Route::delete('contacts/delete/{id}',[ContactController::class,'destroy']);