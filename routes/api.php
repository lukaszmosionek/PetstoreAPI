<?php

use App\Http\Controllers\PetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/pet/findByStatus', [PetController::class, 'findByStatus']);
Route::post('/pet/{petId}/uploadImage', [PetController::class, 'uploadImage']);

// CRUD
// Route::post('/pet', [PetController::class, 'store']);
// Route::get('/pet/{petId}', [PetController::class, 'show']);
// Route::post('/pet/{petId}', [PetController::class, 'update']);
// Route::delete('/pet/{petId}', [PetController::class, 'delete']);

Route::resource('pet', PetController::class);




