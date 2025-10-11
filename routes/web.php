<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\PageController;




Route::post('/admin/components/save', [ComponentController::class, 'save']);
Route::get('/admin/components/list', [ComponentController::class, 'list']);

// Builder UI
Route::get('/builder', function () {
    return view('builder',['page' => null]);
});




Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/', [PageController::class, 'index'])->name('pages.index');


Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');

Route::get('/pages/{id}/edit', [PageController::class, 'edit'])->name('pages.edit');

Route::post('/pages/{id}/save', [PageController::class, 'save'])->name('pages.save');

Route::get('/preview/{id}', [PageController::class, 'preview'])->name('pages.preview');
Route::post('/pages/{id}/publish', [PageController::class, 'publish'])->name('pages.publish');
