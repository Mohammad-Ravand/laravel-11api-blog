<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->put('/category/{category:slug}',[CategoryController::class,'update']);
Route::middleware(['auth:sanctum'])->get('/category',[CategoryController::class,'index']);
Route::middleware(['auth:sanctum'])->post('/category',[CategoryController::class,'store']);
Route::get('/category/{category}',[CategoryController::class,'show']);
