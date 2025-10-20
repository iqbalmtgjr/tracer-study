<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat roles
        $admin = Role::create(['name' => 'admin']);
        $alumni = Role::create(['name' => 'alumni']);

        // Buat user admin default
        $adminUser = User::create([
            'name' => 'Administrator',
            'email' => 'admin@persadakhatulistiwa.ac.id',
            'password' => Hash::make('qweasdzxc'),
            'email_verified_at' => now(),
        ]);

        $adminUser->assignRole('admin');
    }
}
