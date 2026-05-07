<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BloodRequestApiController;
use App\Http\Controllers\Api\BloodRequestDonorApiController;
use App\Http\Controllers\Api\AuthApiController;


Route::get('/blood-requests', [BloodRequestApiController::class, 'index']);
Route::get('/blood-requests/{bloodRequest}', [BloodRequestApiController::class, 'show']);

Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'store']);

    Route::post('/blood-requests', [BloodRequestApiController::class, 'store']);
    Route::post('/blood-requests/{bloodRequest}/respond', [BloodRequestDonorApiController::class, 'store']);
    Route::post(
        '/blood-requests/{bloodRequest}/responses/{response}/select',
        [BloodRequestDonorApiController::class, 'select']
    );
    Route::post(
        '/blood-requests/{bloodRequest}/responses/{response}/reject',
        [BloodRequestDonorApiController::class, 'reject']
    );
    Route::post(
        '/blood-requests/{bloodRequest}/responses/{response}/donated',
        [BloodRequestDonorApiController::class, 'markDonated']
    );
});