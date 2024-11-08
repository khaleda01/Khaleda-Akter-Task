<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PersonController;

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [PersonController::class, 'index']);
Route::get('/people', [PersonController::class, 'fetchPeople']);
Route::post('/people', [PersonController::class, 'store']);
Route::post('/people/{person}', [PersonController::class, 'update']);
Route::delete('/people/{person}', [PersonController::class, 'destroy']);