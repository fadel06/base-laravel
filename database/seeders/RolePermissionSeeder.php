<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Module Configuration
        |--------------------------------------------------------------------------
        | Format:
        | 'module-name' => [
        |     'extra' => ['export', 'print']
        | ]
        */

        $modules = [
            'activity-logs' => [
                'extra' => []
            ],
            'permissions' => [
                'extra' => []
            ],
            'roles' => [
                'extra' => []
            ],
            'regions' => [
                'extra' => []
            ],
            'opds' => [
                'extra' => []
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Default CRUD Actions
        |--------------------------------------------------------------------------
        */

        $defaultActions = ['view', 'create', 'edit', 'delete'];

        /*
        |--------------------------------------------------------------------------
        | Generate Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = $this->generatePermissions($modules, $defaultActions);

        /*
        |--------------------------------------------------------------------------
        | Create / Get Role
        |--------------------------------------------------------------------------
        */

        $role = Role::firstOrCreate([
            'name' => 'super-admin',
            'guard_name' => 'web'
        ]);

        $role->syncPermissions($permissions);

        $superadmin = User::firstOrCreate(
            ['email' => 'superadmin@mode.com'],
            [
                'name' => 'Superadmin',
                'password' => Hash::make('password'),
                'phone_number' => '08123456789'
            ]
        );
        $superadmin->assignRole('super-admin');

        $this->command->info('✅ Roles & Permissions seeded successfully!');
    }

    /**
     * Generate module permissions
     */
    private function generatePermissions(array $modules, array $defaultActions): array
    {
        $permissions = [];

        foreach ($modules as $module => $config) {

            // Default CRUD
            foreach ($defaultActions as $action) {
                $permissions[] = Permission::firstOrCreate([
                    'name' => "{$action}-{$module}",
                    'guard_name' => 'web'
                ]);
            }

            // Extra permission (optional)
            foreach ($config['extra'] ?? [] as $extra) {
                $permissions[] = Permission::firstOrCreate([
                    'name' => "{$extra}-{$module}",
                    'guard_name' => 'web'
                ]);
            }
        }

        return $permissions;
    }
}
