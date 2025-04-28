<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Sales\SalesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticationController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Vans\VanController;
use App\Http\Controllers\Department\DepartmentController;
use App\Http\Controllers\Employee\EmployeeController;
use App\Http\Controllers\Items\CategoryController;
use App\Http\Controllers\Items\UnitController;
use App\Http\Controllers\Items\ItemController;
use App\Http\Controllers\Items\TaxController;
use App\Http\Controllers\Sales\CustomerController;



Route::get('/',[AuthenticationController::class,'login'])->name('login');




Route::middleware(['auth', 'usertype:superadmin'])->group(function () {
    Route::get('/superadmin/dashboard',[DashboardController::class,'superadminIndex'])->name('superadmin.dashboard');

});



Route::middleware(['auth', 'usertype:admin'])->group(function () {

    Route::get('/admin/dashboard',[DashboardController::class,'adminIndex'])->name('admin.dashboard');
    Route::resource('vans', VanController::class);
    Route::resource('departments', DepartmentController::class);
    Route::resource('employees', EmployeeController::class);
    Route::resource('categories', CategoryController::class);

    Route::resource('units', UnitController::class);

    Route::resource('taxes',TaxController ::class);

Route::get('/change/unit/{id}',[UnitController::class,'showUnit'])->name('show-unit');
    Route::put('change/unit/{item}', [UnitController::class, 'createUnit'])
        ->name('item.create-unit');
    Route::get('/items/units/{id}/delete', [UnitController::class, 'deleteUnit'])->name('unit.item.delete');





});



Route::middleware(['auth', 'usertype:employee'])->group(function () {

    Route::get('/employee/dashboard',[DashboardController::class,'employeeIndex'])->name('employee.dashboard');

    Route::get('/van/show-selection', [VanController::class, 'showSelection'])->name('vans.showSelection');
    Route::post('/vans/assign', [VanController::class, 'assign'])->name('vans.assign');
    Route::post('/vans/{van}/unassign', [VanController::class, 'unassign'])->name('vans.unassign');

    Route::resource('items', ItemController::class);
    Route::resource('sales', SalesController::class);

    Route::resource('customers', CustomerController::class);

    Route::get('/get-customer', [CustomerController::class, 'getCustomer'])->name('get.customer');

    Route::get('/search-customers', [CustomerController::class,'searchCustomers'])->name('search.customers');

    Route::get('/search-items', [ItemController::class,'searchItems'])->name('search.items');

});







Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
