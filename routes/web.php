<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\patientsContoller;
use App\Http\Controllers\billsController;
use App\Http\Controllers\diagnosesController;
use App\Http\Controllers\prescriptionsController;
use App\Http\Controllers\testsController;
use App\Http\Controllers\visitsController;

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

Route::get('/', [patientsContoller::class,'index']);


Route::resource('Patients' ,patientsContoller::class);


Route::resource('Bills' ,billsController::class)->except(['create', 'store']);
Route::get('Bills/store/{id}', [billsController::class, 'store']);


Route::resource('Visits' ,visitsController::class)->except('create');
Route::get('Visits/create/{id}', [visitsController::class, 'create']);

Route::get('/showPatientVisits/{id}', [visitsController::class, 'showPatientVisits']);
Route::get('/showLastVisit/{id}', [visitsController::class, 'showLastVisit']);

Route::post('/checkDate', [visitsController::class, 'checkDate']);
Route::get('/checkDate', [visitsController::class, 'checkDateView']);

// add diagnoses, prescriptions and tests
Route::get('/Visits/addDiagnose/{id}', [diagnosesController::class, 'create']);
Route::post('/addDiagnose', [diagnosesController::class, 'store']);

Route::get('/Visits/addPrescription/{id}', [prescriptionsController::class, 'create']);
Route::post('/addPrescription', [prescriptionsController::class, 'store']);

Route::get('/Visits/addTest/{id}', [testsController::class, 'create']);
Route::post('/addTest', [testsController::class, 'store']);

Route::get('/Bills/addProcedure/{id}', [billsController::class, 'addProcedureView']);
Route::post('/addProcedure', [billsController::class, 'addProcedure']);

// print receipts
Route::get('/printFirstReceipt/{id}', [billsController::class, 'printFirstReceipt']);
Route::get('/printSecondReceipt/{id}', [billsController::class, 'printSecondReceipt']);


