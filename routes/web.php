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

// Companies
Route::get('/companies', [CompaniesController::class, 'index'])->name('companies');
Route::get('/create-companies', [CompaniesController::class, 'create'])->name('create-companies');
Route::post('/store-companies', [CompaniesController::class, 'store'])->name('store-companies');
Route::get('/edit_company/{id}', [CompaniesController::class, 'edit'])->name('edit_company');
Route::post('/update-company/{id}',[CompaniesController::class, 'update'])->name('update-company');
Route::get('/delete_company/{id}', [CompaniesController::class, 'delete'])->name('delete_company');

// Employees
Route::get('/employees', [EmployeesController::class, 'index'])->name('employees');
Route::get('/get-employees', [EmployeeController::class, 'getEmployees'])->name('get-employees');
Route::get('/create-employee', [EmployeesController::class, 'create'])->name('create-employee');
Route::post('/store-employee', [EmployeesController::class, 'store'])->name('store-employee');
Route::get('/edit-employee/{id}', [EmployeesController::class, 'edit'])->name('edit-employee');
Route::post('/update-employee/{id}',[EmployeesController::class, 'update'])->name('update-employee');
Route::get('/delete_employee/{id}', [EmployeesController::class, 'delete'])->name('delete_employee');

Route::get('/dashboard', function () {
    return redirect()->route('companies');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
