<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Fahim',
            'phone' => fake()->phoneNumber(),
            'email' => "fahimrahimi305@gmail.com",
            'status' => fake()->boolean(),
            'role' => 'admin',
            'password' => Hash::make('12345678'),
        ]);


        //    $adminRole=Role::create(['name'=>'admin']);
        // $adminRole = Role::create(['name' => 'admin']);
        // $managerRole = Role::create(['name' => 'manager']);
        // $editorRole = Role::create(['name' => 'editor']);
        // $userRole = Role::create(['name' => 'user']);

        // Create roles using firstOrCreate to avoid duplicates
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $editorRole = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $permissions = [
            'dashboard_view',
            'sales_view',
            'sales_create',
            'sales_edit',
            'sales_delete',
            'sales_product_view',
            'sales_product_create',
            'sales_product_edit',
            'sales_product_delete',
            'billable_expense_view',
            'billable_expense_create',
            'billable_expense_edit',
            'billable_expense_delete',
            'none_billable_expense_view',
            'none_billable_expense_create',
            'none_billable_expense_edit',
            'none_billable_expense_delete',
            'expense_product_view',
            'expense_product_create',
            'expense_product_edit',
            'expense_product_delete',
            'expense_category_view',
            'expense_category_create',
            'expense_category_edit',
            'expense_category_delete',
            'customer_view',
            'customer_create',
            'customer_edit',
            'customer_delete',
            'supplier_view',
            'supplier_create',
            'supplier_edit',
            'supplier_delete',
            'owner_view',
            'owner_create',
            'owner_edit',
            'owner_delete',
            'owner_pickup_view',
            'owner_pickup_create',
            'owner_pickup_edit',
            'owner_pickup_delete',
            'user_view',
            'user_create',
            'user_edit',
            'user_delete',
            'reports_profit_loss',
            'reports_customer_report',
            'reports_supplier_report',
            'reports_expense_report',
            'reports_expense_product_report',
            'settings_view',
            'roles_view',
            'roles_create',
            'roles_edit',
            'roles_delete',
        ];
        



    //     foreach ($permissions as $permission) {
    //         Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
    //         $adminRole->givePermissionTo($permission);
    //     }
    //     $user->assignRole($adminRole);
    // }
    foreach ($permissions as $permission) {
        $perm = Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        $adminRole->givePermissionTo($perm);
    }

    // Assign admin role to the user
    $user->assignRole($adminRole);
}

}
