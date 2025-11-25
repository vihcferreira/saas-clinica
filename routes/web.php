<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\OrganizationController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\AppointmentController;

Route::middleware('guest')->group(function () {
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

// Rotas Autenticadas
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::resource('organizations', OrganizationController::class);

    Route::post('organizations/{organization}/switch', [OrganizationController::class, 'switch'])
        ->name('organizations.switch');

    //Rotas de membros
    Route::post('organizations/{organization}/members', [OrganizationController::class, 'inviteMember'])
        ->name('organizations.members.invite');

    Route::delete('organizations/{organization}/members/{user}', [OrganizationController::class, 'removeMember'])
        ->name('organizations.members.remove');

    //ROTAS DE PACIENTES E CONSULTAS
    Route::resource('patients', PatientController::class);
    Route::resource('appointments', AppointmentController::class);
});
