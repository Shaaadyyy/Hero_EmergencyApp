<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\EmergencyCaseController;
use App\Http\Controllers\EmergencyNumController;
use App\Http\Controllers\HomeARController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MedicalARController;
use App\Http\Controllers\MedicalController;
use App\Http\Controllers\PetARController;
use App\Http\Controllers\PetController;
use Illuminate\Http\Request;
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

// routes for emergency numbers
Route::get('/listAllEmergencyNumbers', [EmergencyNumController::class, 'listAllEmergencyNums']);
Route::post('/addEmergencyNumber', [EmergencyNumController::class, 'addEmergencyNum']);
Route::get('/getEmergencyNumberByID', [EmergencyNumController::class, 'getEmergencyNumByID']);
Route::get('/getEmergencyNumberByName', [EmergencyNumController::class, 'getEmergencyNumByName']);
Route::put('/updateEmergencyNumber', [EmergencyNumController::class, 'updateEmergencyNum']);
Route::delete('/deleteEmergencyNumber', [EmergencyNumController::class, 'deleteEmergencyNum']);

// routes for categories
Route::get('/listAllCategories', [CategoryController::class, 'getAll']);
Route::post('/addCategory', [CategoryController::class, 'addCategory']);
Route::get('/getCategoryByID', [CategoryController::class, 'getCategoryByID']);
Route::get('/getCategoryByName', [CategoryController::class, 'getCategoryByName']);
Route::put('/updateCategory', [CategoryController::class, 'updateCategory']);
Route::delete('/deleteCategory', [CategoryController::class, 'deleteCategory']);

// routes for medical in english
Route::get('/listAllMedicalCases', [MedicalController::class, 'listAllMedicalCases']);
Route::post('/addMedicalCase', [MedicalController::class, 'addMedicalCase']);
Route::get('/getMedicalCaseByID', [MedicalController::class, 'getMedicalCaseByID']);
Route::put('/updateMedicalCase', [MedicalController::class, 'updateMedicalCase']);
Route::delete('/deleteMedicalCase', [MedicalController::class, 'deleteMedicalCase']);

// routes for medical in arabic
Route::get('/listAllMedicalCasesAR', [MedicalARController::class, 'listAllMedicalCasesAR']);
Route::post('/addMedicalCaseAR', [MedicalARController::class, 'addMedicalCaseAR']);
Route::get('/getMedicalCaseByIDAR', [MedicalARController::class, 'getMedicalCaseByIDAR']);
Route::put('/updateMedicalCaseAR', [MedicalARController::class, 'updateMedicalCaseAR']);
Route::delete('/deleteMedicalCaseAR', [MedicalARController::class, 'deleteMedicalCaseAR']);

// routes for home in english
Route::get('/listAllHomeCases', [HomeController::class, 'listAllHomeCases']);
Route::post('/addHomeCase', [HomeController::class, 'addHomeCase']);
Route::get('/getHomeCaseByID', [HomeController::class, 'getHomeCaseByID']);
Route::put('/updateHomeCase', [HomeController::class, 'updateHomeCase']);
Route::delete('/deleteHomeCase', [HomeController::class, 'deleteHomeCase']);

// routes for home in arabic
Route::get('/listAllHomeCasesAR', [HomeARController::class, 'listAllHomeCasesAR']);
Route::post('/addHomeCaseAR', [HomeARController::class, 'addHomeCaseAR']);
Route::get('/getHomeCaseByIDAR', [HomeARController::class, 'getHomeCaseByIDAR']);
Route::put('/updateHomeCaseAR', [HomeARController::class, 'updateHomeCaseAR']);
Route::delete('/deleteHomeCaseAR', [HomeARController::class, 'deleteHomeCaseAR']);

// routes for pets in english
Route::get('/listAllPetsCases', [PetController::class, 'ListAllPetsCases']);
Route::post('/addPetsCase', [PetController::class, 'addPetsCase']);
Route::get('/getPetsCaseByID', [PetController::class, 'getPetsCaseByID']);
Route::put('/updatePetsCase', [PetController::class, 'updatePetsCase']);
Route::delete('/deletePetsCase', [PetController::class, 'deletePetsCase']);

// routes for pets in arabic
Route::get('/listAllPetsCasesAR', [PetARController::class, 'ListAllPetsCasesAR']);
Route::post('/addPetsCaseAR', [PetARController::class, 'addPetsCaseAR']);
Route::get('/getPetsCaseByIDAR', [PetARController::class, 'getPetsCaseByIDAR']);
Route::put('/updatePetsCaseAR', [PetARController::class, 'updatePetsCaseAR']);
Route::delete('/deletePetsCaseAR', [PetARController::class, 'deletePetsCaseAR']);

// route for get case by name in english & arabic
Route::get('/getCaseByName', [EmergencyCaseController::class, 'getCaseByName']);
Route::get('/getCaseByNameAR', [EmergencyCaseController::class, 'getCaseByNameAR']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
