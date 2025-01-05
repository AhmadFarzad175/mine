<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Create a user
        $user = User::create([
            'name' => 'elitevalley',
            'phone' => fake()->phoneNumber(),
            'email' => "admin@elitevalley.af",
            'status' => fake()->boolean(),
            'password' => Hash::make('123456'), // Ensure a strong password in production
        ]);

        // Create roles using firstOrCreate to prevent duplicates
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
       

        // List of permissions
        $permissions = [
            // Dashboard
            'dashboard_view',
        
            // None Billable Expenses
            'viewNoneBillExpenses',
            'viewNoneBillExpense',
            'createNoneBillExpense',
            'editNoneBillExpense',
            'deleteNoneBillExpense',
        
            // Expenses
            'viewExpenses',
            'viewBillexpenses',
            'viewBillexpense',
            'createBillexpense',
            'editBillexpense',
            'deleteBillexpense',
        
            // Currency Management
            'viewCurrencies',
            'viewCurrency',
            'createCurrency',
            'editCurrency',
            'deleteCurrency',
        
            // Owner Pickup
            'viewOwnerPickup',
            'createOwnerPickup',
            'editOwnerPickup',
            'deleteOwnerPickup',

            // Owner 
            'viewOwners',
            'viewOwner',
            'createOwner',
            'editOwner',
            'deleteOwner',
        
            // Reports
            'viewCustomerReport',
            'viewCustomerReports',
            'viewSaleCustomerReport',
            'viewProfitLossReport',
            'viewProductReport',
            'viewExpenseCategoryReport',
            'viewOwnerReport',
            'viewOwnerPickupReport',
            'viewSalePaymentReport',
            'viewSaleReport',
            'viewSaleProductReport',
            'viewSupplierReport',
            'viewSupplierReports',
            'viewUserReport',
            'viewExpenseReport',
            'viewExpenseProductReport',
            'viewCustomerSalesReport',
        
            // Expense Categories
            'viewExpenseCategories',
            'viewExpenseCategory',
            'createExpenseCategory',
            'editExpenseCategory',
            'deleteExpenseCategory',
        
            // Expense Payments
            'viewExpensePayments',
            'viewExpensePaymentes',
            'createExpensePayments',
            'editExpensePayments',
            'deleteExpensePayments',
        
            // Expense Products
            'viewExpenseProducts',
            'viewExpenseProduct',
            'createExpenseProduct',
            'editExpenseProduct',
            'deleteExpenseProduct',
        
            // Sales
            'viewSale',
            'viewSales',
            'createSale',
            'editSale',
            'deleteSale',
        
            // Sale Products
            'viewSaleProducts',
            'viewSaleProduct',
            'createSaleProduct',
            'editSaleProduct',
            'deleteSaleProduct',
        
            // Sale Payments
            'viewSalePaymentses',
            'viewSalePayments',
            'createSalePayments',
            'editSalePayments',
            'deleteSalePayments',
        
            // Customers
            'viewCustomerses',
            'viewCustomers',
            'createCustomers',
            'updateCustomers',
            'deleteCustomers',
        
            // Suppliers
            'viewSupplierses',
            'viewSuppliers',
            'createSuppliers',
            'editSuppliers',
            'deleteSuppliers',
        
            // Loans
            'loanPeopleCreate',
            'loanPeopleEdit',
            'loanPeopleView',
            'loanPeopleViews',
            'loanPeopleDelete',
        
            // Users
            'viewUsers',
            'viewUser',
            'createUser',
            'editUser',
            'deleteUser',
        
            // Roles and Permissions
            'viewRoleses',
            'viewRoles',
            'createRoles',
            'editRoles',
            'deleteRoles',
        
            // System Settings
            'viewSystemSettings',
        ];
        
        // Create permissions and assign them to the admin role
        foreach ($permissions as $permission) {
            $permissionModel = Permission::firstOrCreate(['name' => $permission]);
            $adminRole->givePermissionTo($permissionModel);
        }

        // Assign the admin role to the user
        $user->assignRole($adminRole);
    }
}
