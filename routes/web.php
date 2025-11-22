<?php

use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PersonnelController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware(['auth'])->group(function () {


    Route::get('/', [ItemController::class, 'index'])->name('dashboard');


    Route::resource('personnel', PersonnelController::class)->except(['show']);


    Route::resource('items', ItemController::class);

    Route::get('assignments/history', [AssignmentController::class, 'history'])->name('assignments.history');


    Route::post('items/{item}/assign', [AssignmentController::class, 'assign'])->name('items.assign');

    Route::post('items/{item}/return', [AssignmentController::class, 'retake'])->name('items.return');
});