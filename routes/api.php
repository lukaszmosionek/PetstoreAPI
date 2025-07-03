<?php

use App\Http\Controllers\PetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/pet/{petId}/uploadImage', [PetController::class, 'uploadImage']);
Route::post('/pet', [PetController::class, 'store']);
Route::get('/pet/findByStatus', [PetController::class, 'findByStatus']);
Route::get('/pet/{petId}', [PetController::class, 'findPetById']);
Route::post('/pet/{petId}', [PetController::class, 'updatePetWithForm']);
