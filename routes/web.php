<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


//Rutas de administrador

Route::get('/', [ EmployeesController::class, 'index']);
Route::get('administrator/generalShow', [ EmployeesController::class, 'generalShow' ])->name('admin.employees');
Route::post('administrator/registerEmployees', [EmployeesController::class, 'store'])->name('admin.employees.create');
Route::put('administrator/updateEmployees/{id}', [EmployeesController::class, 'update'])->name('admin.employees.edit');


// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
