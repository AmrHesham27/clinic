<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\patientsContoller;
use App\Http\Controllers\billsController;
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


Route::post('/checkDate', [visitsController::class, 'checkDate']);
Route::get('/checkDate', [visitsController::class, 'checkDateView']);

// add diagnoses, prescriptions and tests
Route::get('/Visits/addDiagnose/{id}', [visitsController::class, 'addDiagnoseView']);
Route::post('/addDiagnose', [visitsController::class, 'addDiagnose']);

Route::get('/Visits/addPrescription/{id}', [visitsController::class, 'addPrescriptionView']);
Route::post('/addPrescription', [visitsController::class, 'addPrescription']);

Route::get('/Visits/addTest/{id}', [visitsController::class, 'addTestView']);
Route::post('/addTest', [visitsController::class, 'addTest']);

Route::get('/Bills/addProcedure/{id}', [billsController::class, 'addProcedureView']);
Route::post('/addProcedure', [billsController::class, 'addProcedure']);

// print receipts
Route::get('/printFirstReceipt/{id}', [billsController::class, 'printFirstReceipt']);
Route::get('/printSecondReceipt/{id}', [billsController::class, 'printSecondReceipt']);
