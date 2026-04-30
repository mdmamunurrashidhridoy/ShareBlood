<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BloodRequestApiController;

Route::get('/blood-requests', [BloodRequestApiController::class, 'index']);
Route::get('/blood-requests/{bloodRequest}', [BloodRequestApiController::class, 'show']);