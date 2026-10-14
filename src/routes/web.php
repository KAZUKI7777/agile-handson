<?php

use App\Http\Controllers\DailyReportController;
use App\Http\Controllers\KptController;
use App\Http\Controllers\ScrumController;
use App\Http\Controllers\ScrumTaskController;
use App\Http\Controllers\SprintController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;

// トップページはスクラムダッシュボード
Route::get('/', function () {
    return redirect()->route('scrum.dashboard');
});

// スクラム関連メインルート
Route::prefix('scrum')->name('scrum.')->group(function () {
    Route::get('/', [ScrumController::class, 'dashboard'])->name('dashboard');
    Route::get('/board', [ScrumController::class, 'board'])->name('board');
    Route::get('/poker', [ScrumController::class, 'poker'])->name('poker');
    Route::get('/kpt', [ScrumController::class, 'kpt'])->name('kpt');
});

// スプリント管理
Route::resource('sprints', SprintController::class)->only(['index', 'store', 'update', 'destroy']);

// タスク管理
Route::resource('tasks', ScrumTaskController::class)->only(['store', 'update', 'destroy']);
Route::patch('tasks/{task}/status', [ScrumTaskController::class, 'updateStatus'])->name('tasks.updateStatus');
Route::patch('tasks/{task}/points', [ScrumTaskController::class, 'updatePoints'])->name('tasks.updatePoints');

// KPTふりかえり管理
Route::post('kpt', [KptController::class, 'store'])->name('kpt.store');
Route::post('kpt/{item}/vote', [KptController::class, 'vote'])->name('kpt.vote');
Route::patch('kpt/{item}/toggle', [KptController::class, 'toggleAction'])->name('kpt.toggle');
Route::delete('kpt/{item}', [KptController::class, 'destroy'])->name('kpt.destroy');

// 日報管理（既存）
Route::resource('reports', DailyReportController::class);


// Todo管理（新規）
Route::get('/todos', [TodoController::class, 'index'])->name('todos.index');
Route::post('/todos', [TodoController::class, 'store'])->name('todos.store');
Route::post('/todos/{id}/complete', [TodoController::class, 'complete'])->name('todos.complete');
Route::delete('/todos/{id}', [TodoController::class, 'destroy'])->name('todos.destroy');