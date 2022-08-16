<?php

use App\Http\Controllers\patientsContoller;
use App\Http\Controllers\visitsController;
use App\Http\Controllers\TestResultController;
use App\Http\Controllers\testsController;
use App\Http\Controllers\diagnosesController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/* Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

Route::group(['middleware' => 'cors'], function () {
    Route::resource('Patients', patientsContoller::class);
    Route::get('Patients/search/{entry}', [patientsContoller::class, 'search']);

    Route::resource('Visits', visitsController::class);
    Route::post('Visits/search', [visitsController::class, 'search']);
    Route::post('/checkDate', [visitsController::class, 'checkDate']);
    Route::get('/checkWorkingDays', [visitsController::class, 'checkWorkingDays']);

    Route::resource('Diagnoses', diagnosesController::class);
    Route::get('/show_diagnoses/{id}', [diagnosesController::class, 'show_patient_diagnoses']);

    Route::resource('Tests', testsController::class);

    Route::resource('TestResults', TestResultController::class);
    Route::post('TestResults/saveImage', [TestResultController::class, 'saveImage']);
});
