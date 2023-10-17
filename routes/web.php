<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\ClinicController;

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
Route::get('administrator/generalShow', [ EmployeesController::class, 'generalShow' ]);
Route::put('administrator/updateEmployees/{id}', [EmployeesController::class, 'update']);

// Rutas de consultorio 
Route::get('/clinic/view', [ ClinicController::class, 'index' ]);
Route::get('/clinic/generalShow', [ ClinicController::class, 'generalShow' ]);
Route::post('/clinic/register', [ ClinicController::class, 'register' ]);
Route::put('/clinic/update/{id}', [ ClinicController::class, 'update' ]);
Route::get('/clinic/search', [ ClinicController::class, 'search' ]);
// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Auth::routes();

// Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
