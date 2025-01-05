<?php
namespace Database\Seeders;

use App\Models\BillExpenseDetails;
use App\Models\BillExpenses;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Customers;
use App\Models\ExpenseCategory;
use App\Models\ExpensePayments;
use App\Models\ExpenseProducts;
use App\Models\Expenses;
use App\Models\Owner;
use App\Models\OwnerPickup;
use App\Models\SaleDetails;
use App\Models\salePayment;
use App\Models\saleProducts;
use App\Models\sales;
use App\Models\Suppliers;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeders for other models
        Currency::factory()->count(2)->create();
        // salePayment::factory()->count(5)->create();
        // sales::factory()->count(5)->create();
        saleProducts::factory()->count(3)->create();
        // SaleDetails::factory()->count(5)->create();
        ExpenseCategory::factory()->count(3)->create();
        // ExpensePayments::factory()->count(5)->create();
        ExpenseProducts::factory()->count(3)->create();
        // Expenses::factory()->count(5)->create();
        // Customer::factory()->count(3)->create();
        Owner::factory()->count(3)->create();
        // OwnerPickup::factory()->count(5)->create();
        Suppliers::factory()->count(3)->create();
        // BillExpenses::factory()->count(5)->create();
        // BillExpenseDetails::factory()->count(5)->create();

        // Seed roles and permissions
        $this->call(AdminSeeder::class);

       
    }
}
