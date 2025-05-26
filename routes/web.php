<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use App\Http\Controllers\CompanyController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

// Authenticated users only
Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('companies', CompanyController::class);

    Route::post('employees', [EmployeeController::class, 'store'])->name('employees.store');
    Route::delete('employees/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
