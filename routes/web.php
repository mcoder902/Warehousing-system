<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PersonnelController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('home');

//auth
Route::get("login",[AuthController::class,'index'] )->name('login');
Route::post("login",[AuthController::class,'login'] )->name('login.submit');
Route::get("logout",[AuthController::class,'logout'] )->name('logout');

Route::get("forgot-password",[AuthController::class,'forgotPassword'] )->name('forgot-password');
Route::post("forgot-password",[AuthController::class,'forgotPassword'] )->name('forgot-password.submit');




Route::middleware(['auth'])->group(function () {


    Route::get('/dashboard', [ItemController::class, 'index'])->name('dashboard');


    Route::resource('personnel', PersonnelController::class)->except(['show']);


    Route::resource('items', ItemController::class);

    Route::get('assignments/history', [AssignmentController::class, 'history'])->name('assignments.history');


    Route::post('items/{item}/assign', [AssignmentController::class, 'assign'])->name('items.assign');

    Route::post('items/{item}/return', [AssignmentController::class, 'retake'])->name('items.return');
});