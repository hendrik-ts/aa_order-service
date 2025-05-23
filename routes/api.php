<?php

use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BillController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('auth/google', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

Route::get('/reviews', [ReviewController::class, 'index']);
Route::post('/reviews', [ReviewController::class, 'store']);
Route::get('/reviews/{id}', [ReviewController::class, 'show']);
Route::put('/reviews/{id}', [ReviewController::class, 'update']);
Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);


Route::get('/bills', [BillController::class, 'index']);
Route::get('/bills/{code}', [BillController::class, 'show']);
Route::post('/bills/{code}/pay', [BillController::class, 'pay']);

Route::get('/v1/bills/table_no/{table_no}', [BillController::class, 'showByTableNo']);
Route::post('/v1/bills/table_no/{table_no}/pay', [BillController::class, 'payByTableNo']);

Route::get('/bills/table_no/{tableNo}', [BillController::class, 'showExternalBills']);
Route::post('/bills/table_no/{tableNo}/pay', [BillController::class, 'payExternalBills']);