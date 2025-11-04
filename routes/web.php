<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;


Route::get('/', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth'])->group(function () {
    Route::get('/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/tasks/store', [TaskController::class, 'store'])->name('tasks.store');
    Route::match(['get', 'post'], '/tasks/{id}/review', [TaskController::class, 'review'])->name('tasks.review');
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::post('/tasks/{id}/update', [TaskController::class, 'update'])->name('tasks.update');
    Route::match(['get', 'post'], '/tasks/{id}/progress', [TaskController::class, 'progress'])->name('tasks.progress');
    Route::match(['get', 'post'], '/tasks/{id}/override', [TaskController::class, 'override'])->name('tasks.override');
    Route::post('/tasks/{id}/complete', [TaskController::class, 'complete'])->name('tasks.complete');
    Route::post('/tasks/{id}/delete', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::get('/tasks/monitor', [TaskController::class, 'monitor'])->name('tasks.monitor');
    Route::get('/tasks/{id}/history', [TaskController::class, 'history'])->name('tasks.history');
});
