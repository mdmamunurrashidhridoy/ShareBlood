<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DonorController;
use App\Http\Controllers\BloodRequestController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DonorSearchController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminBloodRequestController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\BloodRequestDonorController;

Route::get('/', function () {
    return view('welcome');
})->name('landingPage');


Route::get('/locations/divisions/{division}/districts', [ProfileController::class, 'districtsByDivision']);
Route::get('/locations/districts/{district}/upazillas', [ProfileController::class, 'upazillasByDistrict']);
Route::get('/locations/dhaka/city-corporations', [ProfileController::class, 'dhakaCityCorporation']);
Route::get('/locations/dhaka/city-corporations/{cityCorporation}/areas', [ProfileController::class, 'areasByCityCorporation']);

Route::get('/blood-requests', [BloodRequestController::class, 'index'])
    ->name('blood-requests.index');

Route::middleware('auth')->group(function () {
    Route::get('/profile/complete', [ProfileController::class, 'completeForm'])->name('profile.complete');
    Route::post('/profile/complete', [ProfileController::class, 'completeStore'])->name('profile.complete.store');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/update', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/donor/dashboard', [DonorController::class, 'dashboard'])->name('donor.dashboard');
    Route::post('/donor/dashboard', [DonorController::class, 'update'])->name('donor.update');

    // Donor responds to a blood request
    Route::post(
        '/blood-requests/{bloodRequest}/respond',
        [BloodRequestDonorController::class, 'store']
    )->name('blood-requests.respond');

    // Requester selects a donor
    Route::post(
        '/blood-requests/{bloodRequest}/responses/{response}/select',
        [BloodRequestDonorController::class, 'select']
    )->name('blood-requests.responses.select');

    // Requester rejects a donor
    Route::post(
        '/blood-requests/{bloodRequest}/responses/{response}/reject',
        [BloodRequestDonorController::class, 'reject']
    )->name('blood-requests.responses.reject');

    // Requester marks donor as donated
    Route::post(
        '/blood-requests/{bloodRequest}/responses/{response}/donated',
        [BloodRequestDonorController::class, 'markDonated']
    )->name('blood-requests.responses.donated');

    // Donor cancels their response
    Route::post(
        '/blood-requests/{bloodRequest}/responses/{response}/cancel',
        [BloodRequestDonorController::class, 'cancelResponse']
    )->name('blood-requests.responses.cancel');

});


Route::middleware('auth', 'blocked.redirect')->group(function () {
    Route::get('/account-blocked', [ProfileController::class, 'blocked'])
        ->name('account.blocked');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');

    Route::get('/blood-requests/create', [BloodRequestController::class, 'create'])->name('blood-requests.create');
    Route::post('/blood-requests', [BloodRequestController::class, 'store'])->name('blood-requests.store');

    Route::get('/my/blood-requests', [BloodRequestController::class, 'my'])->name('blood-requests.my');

    Route::patch('/blood-requests/{bloodRequest}/cancel', [BloodRequestController::class, 'cancel'])
        ->name('blood-requests.cancel');

    Route::patch('/blood-requests/{bloodRequest}/complete', [BloodRequestController::class, 'complete'])
        ->name('blood-requests.complete');

    Route::get('/blood-requests/{bloodRequest}', [BloodRequestController::class, 'show'])
        ->name('blood-requests.show');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/notifications', [NotificationController::class, 'index'])
        ->name('notifications.index');

    Route::post('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])
        ->name('notifications.read');

    Route::post('notifications/read-all', [NotificationController::class, 'readAll'])
        ->name('notifications.readAll');
});



Route::get('/donors', [DonorSearchController::class, 'index'])->name('donors.index');

require __DIR__ . '/auth.php';


Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])
            ->name('dashboard');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('/users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::patch('/users/{user}/block', [AdminUserController::class, 'block'])->name('users.block');
        Route::patch('/users/{user}/unblock', [AdminUserController::class, 'unblock'])->name('users.unblock');
        Route::patch('/users/{user}/verify', [AdminUserController::class, 'verify'])->name('users.verify');
        Route::patch('/users/{user}/unverify', [AdminUserController::class, 'unverify'])->name('users.unverify');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        Route::get('/blood-requests', [AdminBloodRequestController::class, 'index'])->name('blood-requests.index');
        Route::get('/blood-requests/{bloodRequest}', [AdminBloodRequestController::class, 'show'])->name('blood-requests.show');
        Route::patch('/blood-requests/{bloodRequest}/status', [AdminBloodRequestController::class, 'updateStatus'])->name('blood-requests.update-status');
        Route::delete('/blood-requests/{bloodRequest}', [AdminBloodRequestController::class, 'destroy'])->name('blood-requests.destroy');

    });
