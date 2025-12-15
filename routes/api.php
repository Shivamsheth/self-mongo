<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\MongoTokenAuth;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('/register',[AuthController::class,'register']);
Route::post('/login',[AuthController::class,'login']);

// i want to add multiple routes in one middleware with different group
Route::middleware(['mongo.auth'])->group(function(){
    Route::get('/users',[UserController::class,'index']); // only admin can access
    Route::get('/user/profile/{id}',[UserController::class,'profile']);
    Route::put('/user/update-profile/{id}',[UserController::class,'updateProfile']);
    Route::delete('/user/delete/{id}',[UserController::class,'deleteProfile']);
    Route::delete('/allUsers-delete',[UserController::class,'deleteAllUsers']); // only admin can access
});