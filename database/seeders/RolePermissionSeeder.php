<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        $permissions = [
            'manage access control',
            'manage staff access control',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | Super Admin Role
        |--------------------------------------------------------------------------
        */

        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',
        ]);

        // Super Admin gets all permissions
        $superAdminRole->syncPermissions(
            Permission::all()
        );


        /*
        |--------------------------------------------------------------------------
        | Users Role
        |--------------------------------------------------------------------------
        |
        | Normal customers/users do NOT receive administrative permissions.
        |
        */

        $usersRole = Role::firstOrCreate([
            'name' => 'Users',
        ]);

        // Make sure Users role has no admin permissions
        $usersRole->syncPermissions([]);


        /*
        |--------------------------------------------------------------------------
        | Administrator User
        |--------------------------------------------------------------------------
        */

        $admin = User::firstOrCreate(
            [
                'email' => 'Administrator@gmail.com',
            ],
            [
                'name' => 'Administrator',
                'password' => Hash::make('12345678'),
            ]
        );

        // Make sure administrator has Super Admin role
        $admin->syncRoles([
            $superAdminRole,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Normal User
        |--------------------------------------------------------------------------
        |
        | This creates a test customer account.
        |
        */

        $user = User::firstOrCreate(
            [
                'email' => 'user@gmail.com',
            ],
            [
                'name' => 'Normal User',
                'password' => Hash::make('12345678'),
            ]
        );

        // Make sure this account has ONLY the Users role
        $user->syncRoles([
            $usersRole,
        ]);


        /*
        |--------------------------------------------------------------------------
        | Done
        |--------------------------------------------------------------------------
        |
        | Run:
        |
        | php artisan db:seed --class=RolePermissionSeeder
        |
        */
    }
}