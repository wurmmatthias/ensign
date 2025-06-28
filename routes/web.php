<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\SharedLinkController;



Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
    Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
    Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
    Route::get('/documents/{document}', [DocumentController::class, 'show'])->name('documents.show');
});


Route::post('/documents/{document}/signature', [SignatureController::class, 'store'])->name('signature.store');

Route::get('/signed-preview/{path}', function ($path) {
    return view('documents.signed-preview', ['signedPath' => $path]);
})->name('documents.signed-preview');

Route::get('/share/{token}', [SharedLinkController::class, 'show'])->name('shared.sign');
Route::post('/share/{token}/sign', [SharedLinkController::class, 'sign'])->name('shared.sign.store');


Route::post('/documents/{document}/share', [DocumentController::class, 'share'])->name('documents.share');



require __DIR__.'/auth.php';
