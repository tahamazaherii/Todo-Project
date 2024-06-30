<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\TodoController;
use App\Http\Controllers\api\UserController;




// for send email and login user
Route::post('/sendLoginLink' , [UserController::class , 'sendLoginLink']);

// after send email redirect to logon and show token
Route::get('/login' , [UserController::class , 'login'])->name('auth.login');

Route::get('/', function () {
    return response()->json(['message' => 'please login'] ,500);
})->name('login');



Route::group(['middleware' => ['auth:sanctum']], function () {

    Route::post('/logout', [UserController::class, 'logout']); // برای خروج حساب کاربر هست

    Route::apiResource('todos', TodoController::class); // apiResource todo

    Route::get('/showFinishStatus' , [TodoController::class , 'showFinishStatus']);//برای نمایش همه ی فعالیت های که انجام شده هست

    Route::put('/ChangeStatus/{todo}', [TodoController::class, 'ChangeStatus']); //برای اپدیت کاری که انجام داده هست
});
