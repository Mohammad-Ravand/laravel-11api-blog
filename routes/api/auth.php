<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ForgetPasswordController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


Route::post('/register/email', [RegisteredUserController::class, 'storeEmail'])
    ->middleware('guest')
    ->name('register.sendEmail');


Route::post('/register/verify-email-code', [RegisteredUserController::class, 'verifyEmailCode'])
    ->middleware('guest')
    ->name('register.verifyEmailCode');


Route::post('/register/complete-infos', [RegisteredUserController::class, 'storeUserInfo'])
    ->middleware('guest')
    ->name('register.completeInfos');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])
    ->middleware('guest')
    ->name('login');


Route::post('/forget-password', [ForgetPasswordController::class, 'forgetPassword'])
    ->middleware('guest')
    ->name('register.forgetPassword');

Route::post('/change-password', [ForgetPasswordController::class, 'changePassword'])
    ->middleware('guest')
    ->name('register.changePassword');

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});
