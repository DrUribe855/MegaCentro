<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeesController;
use App\Http\Controllers\ClinicController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AssistantController;
use App\Http\Controllers\CollectorController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ResidueController;
use App\Http\Controllers\ResiduePriceController;
use App\Models\Residue_price;

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

Route::get('/', function(){
    if(auth()->user()){
        return view('index');
    }else{
        return view('../auth/login');
    }
});

Route::post('/login2', [AuthController::class, 'login']);
Route::get('/index', [HomeController::class, 'index']);

// --------------------------- Rutas de modulo empleados --------------------------- \\

Route::get('administrator/generalShow', [ EmployeesController::class, 'generalShow' ])->middleware('can:admin.employees.generalShow');
Route::get('/indexEmployees', [ EmployeesController::class, 'index'])->middleware('can:admin.employees.generalShow');
Route::post('administrator/registerEmployees', [EmployeesController::class, 'store'])->middleware('can:admin.employees.create')->name('admin.employees.create');
Route::put('administrator/updateEmployees/{id}', [EmployeesController::class, 'update'])->middleware('can:admin.employees.edit')->name('admin.employees.edit');

// Auth::routes();

Route::get('/assistant/index', [ AssistantController::class, 'index'])->middleware('can:admin.managers.generalShow');
Route::get('/assistant/continuation', [ AssistantController::class, 'showContinuation'])->middleware('can:admin.view.rhContinuation');
Route::get('/assistant/unified', [ AssistantController::class, 'showUnified'])->middleware('can:admin.managers.generalShow');
Route::get('/assistant/unifiedContinuation', [ AssistantController::class, 'showUnifiedContinuation'])->middleware('can:admin.managers.generalShow');

// --------------------------- Rutas de modulo encargados -------------------------------- \\ 

Route::get('/clinic/view', [ ClinicController::class, 'index' ])->middleware('can:admin.managers.generalShow');
Route::get('/clinic/generalShow', [ ClinicController::class, 'generalShow' ])->middleware('can:admin.managers.generalShow');
Route::get('/clinic/generalShowClinic', [ ClinicController::class, 'generalShowClinic' ])->middleware('can:admin.managers.generalShow');
Route::get('/clinic/showClinicResponsible', [ ClinicController::class, 'showClinicResponsible' ])->middleware('can:admin.managers.generalShow');
Route::get('/clinic/showTower/{tower}', [ ClinicController::class, 'showTower' ])->middleware('can:admin.managers.generalShow');
Route::get('/residue/showInvoince/{id}/{status}', [ClinicController::class, 'showInvoince']);
Route::get('/clinic/consultation/{id}/{status}', [ClinicController::class, 'consultation'])->middleware('can:admin.managers.generalShow');
Route::get('/clinic/consultationUser', [ClinicController::class, 'consultationUser'])->middleware('can:admin.managers.generalShow');
Route::put('/clinic/update/{id}', [ ClinicController::class, 'update' ])->middleware('can:admin.managers.edit');
Route::post('/clinic/register', [ ClinicController::class, 'register' ])->middleware('can:admin.managers.create');
Route::post('/clinic/infoClinic/{id}', [ ClinicController::class, 'infoClinic' ])->middleware('can:admin.managers.generalShow');
Route::post('/clinic/deleteUser/{role}', [ClinicController::class, 'deleteUser'])->middleware('can:admin.managers.edit');
Route::post('/clinic/addClinic', [ClinicController::class, 'addClinic'])->middleware('can:admin.managers.create');
Route::post('/clinic/addUser', [ClinicController::class, 'addUser'])->middleware('can:admin.managers.create');
Route::post('/residue/payBill/{id}', [ClinicController::class, 'payBill']);

// ----------------------------- Rutas de recolector --------------------------------- \\

Route::get('/collector/index', [ CollectorController::class, 'index'])->middleware('can:collector.collections.generalShow');
Route::get('/collector/non-hazardous-wast', [CollectorController::class, 'noHazardousView']);
Route::get('/collector/non-hazardous-waste', [CollectorController::class, 'residueChemical']);
Route::get('/collector/clinics', [ CollectorController::class, 'getClinics']);
Route::post('/collector/saveCollection', [ CollectorController::class, 'store']);
Route::get('/collector/getRole', [ CollectorController::class, 'getUserRole']);
// ------------------------------- Rutas de encargado --------------------------------------- \\

Route::get('/manager/index', [ ManagerController::class, 'index'])->middleware('can:manager.clinics.generalShow');
Route::get('/manager/collector_residue', [ ManagerController::class, 'collectorResidue']);// ->middleware('can:manager.clinics.generalShow');

// Rutas de login

Auth::routes();

// ------------------------------- Rutas de residuos --------------------------------------- 

Route::get('/residue/generalShow/{date}', [ResidueController::class, 'generalShow']);
Route::get('/residue/showContinuation/{date}', [ResidueController::class, 'showContinuation']);
Route::get('/residue/showUnified/{date}', [ResidueController::class, 'showUnified']);
Route::get('/residue/showUnifiedContinuation/{date}', [ResidueController::class, 'showUnifiedContinuation']);
Route::get('/residue/showCollectorResidue/{type}', [ResidueController::class, 'showCollectorResidue']);
Route::post('/residue/registerCollector/{id}/{date}', [ResidueController::class, 'registerCollector']);
Route::get('/residue/clinicNumber', [ResidueController::class, 'showClinic']);
Route::get('/residue/clinicSelected/{date}/{id}', [ResidueController::class, 'showClinicSelected']);
Route::get('/residue/clinicContinuation/{date}/{id}', [ResidueController::class, 'showClinicConstinuation']);


// ------------------------------- Rutas de precio residuos --------------------------------------- 
Route::get('/manager/residue_price', [ResiduePriceController::class, 'view'])->middleware('can:admin.employees.generalShow');
Route::get('/manager/residuePrice', [ResiduePriceController::class, 'residuePrice']);
Route::put('/manager/registerPrice/{id}', [ResiduePriceController::class, 'register']);

