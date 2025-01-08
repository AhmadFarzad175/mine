<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OwnerController;
use App\Http\Controllers\salesController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ExpensesController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BillExpenseController;
use App\Http\Controllers\OwnerPickupController;
use App\Http\Controllers\SalePaymentController;
use App\Http\Controllers\salesProductsController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\CustomerReportController;
use App\Http\Controllers\ExpensePaymentController;
use App\Http\Controllers\ExpenseProductController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ExpenseCategoryController;
use App\Http\Controllers\ProfitLossReportController;
use App\Http\Controllers\OwnerPickupReportController;
use App\Http\Controllers\CustomerSalesReportController;
use App\Http\Controllers\ExpenseProductReportController;
use App\Http\Controllers\ExpenseCategoryReportController;
use App\Http\Controllers\LoanPeopleController;
use App\Http\Controllers\LoanPaymentSentController;
use App\Http\Controllers\LoanPaymentReceivedController;
use App\Http\Controllers\MoneyAccountController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StakeholderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/register',[AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])->group(function () {

///////////////////////// EARNING SECTIONS //////////////////////////

Route::apiResource('sales',salesController::class);////////////
Route::apiResource('sales_Payments',SalePaymentController::class);
Route::apiResource('sales_products', SalesProductsController::class);
// Route::get('sale_products/search', [salesProductsController::class, 'searchProduct']);


///////////////////////// EXPENSE SECTIONS //////////////////////////
Route::apiResource('ExpenseCategory',ExpenseCategoryController::class);
Route::apiResource('ExpensePayment',ExpensePaymentController::class);
Route::apiResource('ExpenseProduct',ExpenseProductController::class);
Route::apiResource('Expenses',ExpensesController::class);
Route::apiResource('BillExpenses',BillExpenseController::class);
Route::delete('BillExpense/bulk-delete', [BillExpenseController::class, 'bulkDelete']);


////////////////////// PEOPLE SECTIONS  //////////////////////////////
Route::post('Customers/update/{id}',[CustomerController::class, 'update']);
Route::apiResource('Customers',CustomerController::class);

Route::post('Users/update/{id}',[UsersController::class, 'update']);

Route::apiResource('Users',UsersController::class);
Route::post('users/switch/{user}', [UsersController::class, 'switchUser']);
Route::post('users/update/{user}', [UsersController::class, 'updateUser']);

Route::post('Owners/update/{id}',[OwnerController::class, 'update']);
Route::apiResource('Owners',OwnerController::class);
Route::apiResource('OwnerPickup',OwnerPickupController::class);
Route::apiResource('Suppliers',SupplierController::class);
// Route::post('Suppliers/update/{id}',[SupplierController::class, 'update']);

Route::apiResource('stakeholders', StakeholderController::class);
Route::post('stakeholders/update/{stakeholder}', [StakeholderController::class, 'update']);




//////////////////// Currency //////////////////////
// currency added backend
Route::apiResource('currency', CurrencyController::class);
Route::apiResource('moneyAccounts', MoneyAccountController::class);

//////////////////// REports //////////////////////




// Route::get('/reports/sale-customers', CustomerReportController::class)
//     ->middleware('auth');

Route::get('/customerSaleReport', CustomerSalesReportController::class);
Route::get('/customerReport', CustomerReportController::class);
Route::get('/expenseCategoryReport', ExpenseCategoryReportController::class);
Route::get('/productReport', ExpenseProductReportController::class);
Route::get('/ownerPickupReport', OwnerPickupReportController::class);
Route::get('/profitLoss', [ReportController::class , 'profitAndLoss']);
Route::get('/categoryExpenseReport', [ReportController::class , 'categoryExpenseReport']);
Route::get('/productExpenseReport', [ReportController::class , 'productExpenseReport']);
Route::get('/ownersReport', [ReportController::class , 'ownersReport']);


Route::get('/customerReports', [ReportController::class , 'customerReports']);
Route::get('/customerDetailsReports/{id}', [ReportController::class , 'customerDetails']);

Route::get('/supplierReports', [ReportController::class , 'SupplierReport']);
Route::get('/supplierDetailsReports/{id}', [ReportController::class , 'supplierDetails']);

///////////////////// DASHBOARD //////////////////

Route::get('/dashboards',[DashboardController::class, 'index']);
Route::apiResource('/Rolepermissions', RolePermissionController::class);
Route::get('/roles', [RolePermissionController::class , 'indexRole']);




Route::post('SystemSetting/update/{id}',[SystemSettingController::class, 'update']);
Route::apiResource('/SystemSetting', SystemSettingController::class);


Route::apiResource('loan-people', LoanPeopleController::class);
Route::apiResource('loan-payment-sent', LoanPaymentSentController::class);
Route::apiResource('loan-payment-received', LoanPaymentReceivedController::class);

});



