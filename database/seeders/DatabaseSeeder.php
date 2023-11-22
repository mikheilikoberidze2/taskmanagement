<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::create(['name' => 'admin']);
        $employerRole = Role::create(['name' => 'employer']);
        $employeeRole = Role::create(['name' => 'employee']);

        $createTask = Permission::create(['name' => 'create-task']);
        $editTask = Permission::create(['name' => 'edit-task']);
        $assignTask = Permission::create(['name' => 'assign-task']);
        $completeTask = Permission::create(['name' => 'complete-task']);

        $adminRole->syncPermissions([$createTask, $editTask, $assignTask, $completeTask]);
        $employerRole->syncPermissions([$createTask, $editTask, $assignTask]);
        $employeeRole->syncPermissions([$completeTask]);

        $adminUser = User::find(1);
        if (!$adminUser) {
            $adminUser = User::create([
                'name' => 'AdminUser',
                'email' => 'admin@test.com',
                'password' => bcrypt('password'),
            ]);
        }
        $adminUser->assignRole('admin');
    }
}
