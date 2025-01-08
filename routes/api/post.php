<?php
use App\Http\Controllers\PostController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->put('/post/{post:slug}',[PostController::class,'update']);
Route::middleware(['auth:sanctum'])->get('/post',[PostController::class,'index']);
Route::middleware(['auth:sanctum'])->post('/post',[PostController::class,'store']);
Route::get('/post/{post:slug}',[PostController::class,'show']);
