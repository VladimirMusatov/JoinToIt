<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\EmployeesController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth'])->group(function () {
    // Companies
    Route::get('/companies', [CompaniesController::class, 'index'])->name('companies');
    // Function for table pagination
    Route::get('/companies-data', [CompaniesController::class, 'getData'])->name('companies-data');
    Route::get('/create-companies', [CompaniesController::class, 'create'])->name('create-companies');
    Route::post('/store-companies', [CompaniesController::class, 'store'])->name('store-companies');
    Route::get('/edit_company/{id}', [CompaniesController::class, 'edit'])->name('edit_company');
    Route::post('/update-company/{id}',[CompaniesController::class, 'update'])->name('update-company');
    Route::get('/delete_company/{id}', [CompaniesController::class, 'delete'])->name('delete_company');

    // Employees
    Route::get('/employees', [EmployeesController::class, 'index'])->name('employees');
    // Function for table pagination
    Route::get('/get-employees', [EmployeesController::class, 'getData'])->name('employees-data');
    Route::get('/create-employee', [EmployeesController::class, 'create'])->name('create-employee');
    Route::post('/store-employee', [EmployeesController::class, 'store'])->name('store-employee');
    Route::get('/edit-employee/{id}', [EmployeesController::class, 'edit'])->name('edit-employee');
    Route::post('/update-employee/{id}',[EmployeesController::class, 'update'])->name('update-employee');
    Route::get('/delete_employee/{id}', [EmployeesController::class, 'delete'])->name('delete_employee');
});

require __DIR__.'/auth.php';
