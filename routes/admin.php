<?php

use App\Http\Controllers\AccessRequestController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\AllianceController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\GameDataController;
use App\Http\Controllers\MasterListController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get('lang/{locale}', function ($locale) {
    $availableLanguages = array_keys(config('game.languages'));

    if (in_array($locale, $availableLanguages)) {
        session(['locale' => $locale]);
        app()->setLocale($locale);
        Log::info('getLocal: ' . app()->getLocale());
    }

    return redirect()->back();
})->name('change.language');

Route::get('/questionnaire', [AccessRequestController::class, 'showForm']);
Route::post('/questionnaire', [AccessRequestController::class, 'submitForm'])->name('questionnaire.submit');
Route::get('/register', function () {
    return redirect('/questionnaire');
})->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
Route::get('/register/{token}', [RegisteredUserController::class, 'showRegistrationFormFromToken'])->middleware('check.registration.token')->name('register.from_token');

Route::middleware(['auth'])->group(function () {
    Route::get('/game-data', [GameDataController::class, 'edit'])->name('game-data.edit');
    Route::post('/game-data', [GameDataController::class, 'store'])->name('game-data.store');
    Route::get('/show-castles', [GameDataController::class, 'showCastles'])->name('game-data.show_castles');
    Route::post('/select-castle', [GameDataController::class, 'selectCastle'])->name('game-data.select_castle');
});

Route::middleware(['auth', 'game-data-check'])->group(function () {
    Route::get('/', [ProfileController::class, 'dashboard'])->name('/');
    Route::delete('/castles/{castle}', [ProfileController::class, 'destroyCastle'])->name('castles.destroy');
    Route::post('/app-name/update', [ProfileController::class, 'updateAppName'])->name('app-name.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/alliance', [AllianceController::class, 'show'])->name('alliance');
    Route::get('/alliance-data', [AllianceController::class, 'getUpdatedMembers'])->name('alliance.data');
    Route::get('/master-list', [MasterListController::class, 'show'])->name('master-list');
    Route::get('/masterlist-data', [MasterListController::class, 'getSoretedUsers'])->name('masterlist.data');
    Route::get('/distribution', [DistributionController::class, 'show'])->name('distribution');
    Route::get('/distribution-data', [DistributionController::class, 'getDistributionData'])->name('distribution.data');

    Route::middleware('king')->group(function () {
        Route::post('/distribution-save', [DistributionController::class, 'saveDistribution'])->name('distribution.save');
        Route::post('/distribution-save-assignment', [DistributionController::class, 'saveAssignment'])->name('distribution.save.assignment');
        Route::post('/distribution/reset-all', [DistributionController::class, 'resetAllAssignments'])->name('distribution.reset.all');
        Route::post('/distribution/save-assignments', [DistributionController::class, 'saveBulkAssignments'])->name('distribution.save.assignment.bulk');
    });
});

Route::middleware(['auth', 'superAdmin'])->group(function () {
    Route::get('/access-requests', [AccessRequestController::class, 'index'])->name('access-requests');
    Route::get('/access-requests-data', [AccessRequestController::class, 'getData'])->name('access-requests.data');
    Route::post('/access-requests/{id}/approve', [AccessRequestController::class, 'approve']);
    Route::post('/access-requests/{id}/deny', [AccessRequestController::class, 'deny']);
    Route::delete('/access-requests/{id}', [AccessRequestController::class, 'destroy'])->name('access-requests.delete');
    Route::get('/users', [AdminUserController::class, 'index'])->name('users');
    Route::get('/users/data', [AdminUserController::class, 'getData'])->name('users.data');
    Route::post('/users/{id}/reset-password', [AdminUserController::class, 'resetPassword'])->name('users.reset-password');
    Route::delete('/users/{id}', [AdminUserController::class, 'destroy'])->name('users.delete');
    Route::post('/users/{id}/toggle-access', [AdminUserController::class, 'toggleAccess'])->name('users.toggle-access');
});
