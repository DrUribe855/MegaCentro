<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\CollectorController;
use App\Http\Controllers\ManagerController;
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
    return view('../auth/login');
});

Route::post('/login2', [AuthController::class, 'login']);
Route::get('/index', [HomeController::class, 'index']);

// --------------------------- Rutas de modulo empleados --------------------------- \\

Route::get('administrator/generalShow', [ EmployeesController::class, 'generalShow' ])->middleware('can:admin.employees.generalShow');
Route::get('/indexEmployees', [ EmployeesController::class, 'index'])->middleware('can:admin.employees.generalShow');
Route::post('administrator/registerEmployees', [EmployeesController::class, 'store'])->middleware('can:admin.employees.create')->name('admin.employees.create');
Route::put('administrator/updateEmployees/{id}', [EmployeesController::class, 'update'])->middleware('can:admin.employees.edit')->name('admin.employees.edit');

// --------------------------- Rutas de auxiliar ---------------------------------- \\

Route::get('/assistant/index', [ AssistantController::class, 'index'])->middleware('can:admin.managers.generalShow');

// --------------------------- Rutas de modulo encargados -------------------------------- \\ 

Route::get('/clinic/view', [ ClinicController::class, 'index' ])->middleware('can:admin.managers.generalShow');
Route::get('/clinic/generalShow', [ ClinicController::class, 'generalShow' ])->middleware('can:admin.managers.generalShow');
Route::post('/clinic/register', [ ClinicController::class, 'register' ])->middleware('can:admin.managers.create');
Route::put('/clinic/update/{id}', [ ClinicController::class, 'update' ])->middleware('can:admin.managers.edit');

// ----------------------------- Rutas de recolector --------------------------------- \\

Route::get('/collector/index', [ CollectorController::class, 'index'])->middleware('can:collector.collections.generalShow');

// ------------------------------- Rutas de encargado --------------------------------------- \\

Route::get('/manager/index', [ ManagerController::class, 'index'])->middleware('can:manager.clinics.generalShow');

// Rutas de login

Auth::routes();
 
