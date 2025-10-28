<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ComponentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PropertiesController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\DashboardController;

use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

//authincatio routes handle here 
Route::view('login', 'admin/login')->name('login.form');
Route::view('register', 'admin/register')->name('register.form');

//routes to handle auth form
Route::post('login', [AuthController::class, 'login'])->name('login.save');
Route::post('register', [AuthController::class, 'register'])->name('register.save');




//here we start middleware 
// routes/web.php
Route::middleware('ensure.authentic')->group(function () {

 //route for home page 
 Route::get('/', [DashboardController::class, 'dashboard'])->name('dashboard');
 Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

 //this help to create new page
 Route::post('/pages/create', [PageController::class, 'create'])->name('pages.create');

 //this for delete page
 Route::delete('delete/{id}/page', action: [PageController::class, 'pageDelete'])->name('pages.delete');


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

 //route for related to  property page
 Route::get('properties/add', [PropertiesController::class, 'addProperty'])->name('add.property');
 Route::post('properties/save', [PropertiesController::class, 'saveProperty'])->name('save.property');
 Route::delete('properties/{id}/delete', action: [PropertiesController::class, 'deleteProperty'])->name('delete.property');
 Route::get('properites/{id}/edit', [PropertiesController::class, 'editProperty'])->name('edit.property');
 Route::put('properties/{id}/update', [PropertiesController::class, 'updateProperty'])->name('update.property');

 //route for related to agents page
 Route::view('agent/add', 'add.agents')->name('add.agent');
 Route::post('agent/save', [AgentController::class, 'addAgent'])->name('save.agent');
 Route::delete('agent/{id}/delete', [AgentController::class, 'deleteAgent'])->name('delete.agent');
 Route::get('agent/{id}/edit', [AgentController::class, 'editAgent'])->name('edit.agent');
 Route::put('agent/{id}/update', [AgentController::class, 'updateAgent'])->name('update.agent');


 //routes for delete subcriber email
 Route::delete('subcriber/{id}/delete', [DashboardController::class, 'deleteSubcriber'])->name('delete.subcriber');
 //routes for review details 
 Route::get('review/{id}/{type}/detail', [DashboardController::class, 'reviewDetail'])->name('detail.review');
 
 //route for form
 Route::get('form/{id}/detail',[DashboardController::class,'formDetail'])->name('detail.form');
 Route::delete('form/{id}/delete',[DashboardController::class,'formDelete'])->name('delete.form');
});
