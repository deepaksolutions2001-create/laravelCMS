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

 //route for hoe page 
 Route::get('/', [AuthController::class, 'dashboard'])->name('dashboard');
 Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('dashboard');

 //this help to create new page
 Route::post('/pages/create', [PageController::class, 'create'])->name('pages.create');
 
 //this for delete page
 Route::delete('delete/{id}/page',[PageController::class,'pageDelete'])->name('pages.delete');


 //this help to edit page   
 Route::get('/pages/{id}/edit', [PageController::class, 'edit'])->name('pages.edit');


 //this help to save page
 Route::post('/pages/{id}/save', [PageController::class, 'save'])->name('pages.save');

 //this help to preview page
 Route::get('/preview/{id}', [PageController::class, 'preview'])->name('pages.preview');



 //this help to publish page
 Route::post('/pages/{id}/publish', [PageController::class, 'publish'])->name('pages.publish');
 Route::get('pages/publish/{slug}', [PageController::class, 'slug'])->name('publish.pages');


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
