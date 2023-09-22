<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UploadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/employee', [UploadController::class, 'uploadEmployeeData']);
Route::get('/employee', [UploadController::class, 'index']);
Route::get('/employee/{id}', [UploadController::class, 'show']);
Route::delete('/employee/{id}', [UploadController::class, 'delete']);
