<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ComponentController;







//this routes show all listed page in Database
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/', [PageController::class, 'index'])->name('pages.index');

//this help to create new page
Route::get('/pages/create', [PageController::class, 'create'])->name('pages.create');
//this help to edit page   
Route::get('/pages/{id}/edit', [PageController::class, 'edit'])->name('pages.edit');
//this help to save page
Route::post('/pages/{id}/save', [PageController::class, 'save'])->name('pages.save');

//this help to preview page
Route::get('/preview/{id}', [PageController::class, 'preview'])->name('pages.preview');
//this help to publish page
Route::post('/pages/{id}/publish', [PageController::class, 'publish'])->name('pages.publish');



Route::post('/admin/components/save', [ComponentController::class, 'save']);
Route::post('/admin/components/saveAsComponent', [ComponentController::class, 'savePageAsComponent']);
Route::get('/admin/components/list', [ComponentController::class, 'list']);
Route::get('/admin/components/list/{id}', [ComponentController::class, 'listId']);


Route::view('/test', 'test2');




Route::post('/form/save', [ComponentController::class, "saveForm"]);
// routes/web.php (web middleware)
Route::get('/csrf-token', function () {
 return response()->json(['token' => csrf_token()]);
});
