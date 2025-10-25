<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\AuthController;






//authincatio routes handle here 
Route::view('login', 'admin/login')->name('login.form');
Route::view('register', 'admin/register')->name('register.form');

//routes to handle auth form
Route::post('login', [AuthController::class, 'login'])->name('login.save');
Route::post('register', [AuthController::class, 'register'])->name('register.save');




//here we start middleware 
// routes/web.php
Route::middleware('ensure.authentic')->group(function () {

 Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

 //this routes show all listed page in Database
 Route::get('/pages', [PageController::class, 'index'])->name('pages.index');

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


 //logout route

 Route::post('/logout', [AuthController::class, 'logout'])->name('logout.user');



 Route::post('/form/save', [ComponentController::class, "saveForm"]);
 // routes/web.php (web middleware)
 Route::get('/csrf-token', function () {
  return response()->json(['token' => csrf_token()]);
 });
});







Route::get('/', [PageController::class, 'index'])->name('pages.index');
