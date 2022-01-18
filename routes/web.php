<?php

use App\Http\Controllers\CityController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StateController;
use App\Http\Controllers\CountryController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\EmployeeManagementController;
use App\Http\Controllers\ReportController;
use App\Models\Employee;
use App\Models\EmployeeSalary;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/',[DashboardController::class,'index']);

Auth::routes();

Route::get('/dashboard',[DashboardController::class,'index']);
Route::get('/profile', [ProfileController::class,'index']);

Route::post('user-management/search', [UserManagementController::class,'search'])->name('user-management.search');
Route::resource('user-management', UserManagementController::class);

Route::resource('employee-management', EmployeeManagementController::class);
Route::post('employee-management/search', [EmployeeManagementController::class,'search'])->name('employee-management.search');

Route::resource('system-management/department', DepartmentController::class);
Route::post('system-management/department/search', [DepartmentController::class,'search'])->name('department.search');

Route::resource('system-management/division', DivisionController::class);
Route::post('system-management/division/search', [DivisionController::class,'search'])->name('division.search');

Route::resource('system-management/country', CountryController::class);
Route::post('system-management/country/search', [CountryController::class,'search'])->name('country.search');

Route::resource('system-management/state', StateController::class);
Route::post('system-management/state/search', [StateController::class,'search'])->name('state.search');

Route::resource('system-management/city', CityController::class);
Route::post('system-management/city/search', [CityController::class,'search'])->name('city.search');

Route::get('system-management/report', [ReportController::class,'index']);
Route::post('system-management/report/search', [ReportController::class,'search'])->name('report.search');
Route::post('system-management/report/excel', [ReportController::class,'exportExcel'])->name('report.excel');
Route::post('system-management/report/pdf', [ReportController::class,'exportPDF'])->name('report.pdf');

Route::get('avatars/{name}', [EmployeeManagementController::class,'load']);