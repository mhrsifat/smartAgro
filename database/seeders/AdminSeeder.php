<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

public function run(): void
{
    $admin = User::updateOrCreate(
        ['email' => 'admin@gmail.com'],
        [
            'name' => 'Super Admin',
            'password' => Hash::make('12345678'),
            'email_verified_at' => now(),
        ]
    );

    // Create role if not exists
    $role = Role::firstOrCreate(['name' => 'admin']);

    // Assign role
    $admin->assignRole($role);
}
    
}
