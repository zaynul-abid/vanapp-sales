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
use App\Http\Controllers\Items\AlternativeUnitController;
use App\Http\Controllers\Items\ItemController;
use App\Http\Controllers\Items\TaxController;
use App\Http\Controllers\Sales\CustomerController;
use App\Http\Controllers\Report\ReportController;



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
    Route::resource('alternative-units', AlternativeUnitController::class);

    Route::resource('taxes',TaxController ::class);

Route::get('/change/unit/{id}',[UnitController::class,'showUnit'])->name('show-unit');
    Route::put('change/unit/{item}', [UnitController::class, 'createUnit'])
        ->name('item.create-unit');
    Route::get('/items/units/{id}/delete', [UnitController::class, 'deleteUnit'])->name('unit.item.delete');


    Route::get('/sales/sale-item-details/{saleMasterId}',[SalesController::class,'showSaleItem'])->name('saleItem.details');



});



Route::middleware(['auth', 'usertype:employee'])->group(function () {

    Route::get('/employee/dashboard',[DashboardController::class,'employeeIndex'])->name('employee.dashboard');

    Route::get('/van/show-selection', [VanController::class, 'showSelection'])->name('vans.showSelection');
    Route::post('/vans/assign', [VanController::class, 'assign'])->name('vans.assign');
    Route::post('/vans/{van}/unassign', [VanController::class, 'unassign'])->name('vans.unassign');

    Route::resource('items', ItemController::class);
    Route::resource('sales', SalesController::class)->only([
        'index', 'create', 'store','destroy',
    ]);
    Route::get('/sales/search-bills', [SalesController::class, 'searchBills'])->name('sales.search-bills');
    Route::get('/sales/load-bill/{id}', [SalesController::class, 'loadBill'])->name('sales.load-bill');
    Route::put('/sales/update-bill/{id}', [SalesController::class, 'updateBill'])->name('sales.update-bill');

    Route::resource('customers', CustomerController::class);

    Route::get('/get-customer', [CustomerController::class, 'getCustomer'])->name('get.customer');

    Route::get('/search-customers', [CustomerController::class,'searchCustomers'])->name('search.customers');

    Route::get('/search-items', [ItemController::class,'searchItems'])->name('search.items');

    Route::get('/reports-index',[ReportController::class,'index'])->name('reports.index');

    Route::get('/sale-report', [ReportController::class, 'saleReport'])->name('sale_report.index');
    Route::get('/reports/sale-item-details/{saleMasterId}',[ReportController::class,'showSaleItemDetails'])->name('showSale.item');
    Route::get('/sales-report/export-pdf',[ReportController::class,'salesReportPdf'])->name('sales-report.pdf');

    Route::get('/customer-report', [ReportController::class, 'customerReport'])->name('customer_report.index');
    Route::get('/customer-report/details/{customer_id}', [ReportController::class, 'showCustomerDetails'])->name('customer_report.details');
    Route::get('/customer-report/pdf', [ReportController::class, 'customerReportPdf'])->name('customer_report.pdf');

    Route::get('/employee-report', [ReportController::class, 'employeeReport'])->name('employee_report.index');
    Route::get('/employee-report/details/{employee_id}', [ReportController::class, 'ShowEmployeeDetails'])->name('employee_report.details');
    Route::get('/employee-report/pdf', [ReportController::class, 'EmployeeReportPdf'])->name('employee_report.pdf');

    Route::get('/van-report', [ReportController::class, 'vanReport'])->name('van_report.index');
    Route::get('/van-report/details/{van_id}', [ReportController::class, 'ShowVanDetails'])->name('van_report.details');
    Route::get('/van-report/pdf', [ReportController::class, 'vanReportPdf'])->name('van_report.pdf');

    Route::get('/stock-report', [ReportController::class, 'stockReport'])->name('stock_report.index');
    Route::get('/stock-report/details/{item_id}', [ReportController::class, 'ShowStockDetails'])->name('stock_report.details');
    Route::get('/stock-report/pdf', [ReportController::class, 'stockReportPdf'])->name('stock_report.pdf');


    Route::get('/employee/create-item', [\App\Http\Controllers\Employee\LoggedEmployeeController::class, 'createItem'])->name('employee.create-item');

});







Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
