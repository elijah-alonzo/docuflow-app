<?php

namespace Database\Seeders;

use App\Features\Users\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use App\Features\Roles\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Generate Shield permissions for every Filament resource in the admin panel.
        Artisan::call('shield:generate', [
            '--all' => true,
            '--option' => 'permissions',
            '--panel' => 'admin',
            '--no-interaction' => true,
            '--quiet' => true,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // The platform ships with a single bootstrap role. All other roles are
        // user-defined at runtime through the RoleResource per README.
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $adminRole->syncPermissions(Permission::query()->pluck('name')->all());

        $admin = User::firstOrCreate(
            ['email' => 'admin@sys.com'],
            [
                'first_name' => 'System',
                'middle_initial' => null,
                'last_name' => 'Admin',
                'contact_number' => '123456789',
                'password' => Hash::make('password'),
            ]
        );

        $admin->syncRoles([$adminRole]);
    }
}

