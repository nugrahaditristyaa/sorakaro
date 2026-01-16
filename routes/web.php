<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LearnController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('register');
});

Route::get('/dashboard', [LearnController::class, 'dashboard'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:admin'])->get('/admin-test', fn () => 'ADMIN OK');

Route::middleware(['auth'])->group(function () {
    Route::get('/learn', [LearnController::class, 'index'])->name('learn.index');
    Route::get('/learn/level/{level}', [LearnController::class, 'showLevel'])->name('learn.level');

    Route::post('/lesson/{lesson}/start', [LearnController::class, 'start'])->name('learn.start');

    Route::get('/lesson/{lesson}/q/{question}', [LearnController::class, 'showQuestion'])->name('learn.question');

    Route::post('/lesson/{lesson}/q/{question}', [LearnController::class, 'submitAnswer'])->name('learn.submit');

    Route::get('/lesson/{lesson}/result/{attempt}', [LearnController::class, 'result'])->name('learn.result');
    Route::get('/resume/{attempt}', [LearnController::class, 'resume'])->name('learn.resume');
});

require __DIR__.'/auth.php';
