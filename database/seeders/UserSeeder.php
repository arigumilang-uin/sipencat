<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Administrator',
            'username' => 'admin',
            'password' => 'password123', // Will be auto-hashed by model cast
            'role' => UserRole::ADMIN,
            'is_active' => true,
        ]);

        // Create Gudang User
        User::create([
            'name' => 'Staff Gudang',
            'username' => 'gudang',
            'password' => 'password123', // Will be auto-hashed by model cast
            'role' => UserRole::GUDANG,
            'is_active' => true,
        ]);

        // Create Pemilik User
        User::create([
            'name' => 'Pemilik Toko',
            'username' => 'pemilik',
            'password' => 'password123', // Will be auto-hashed by model cast
            'role' => UserRole::PEMILIK,
            'is_active' => true,
        ]);

        // Output success message
        $this->command->info('✅ 3 Default Users created successfully!');
        $this->command->info('   - Admin: username = admin, password = password123');
        $this->command->info('   - Gudang: username = gudang, password = password123');
        $this->command->info('   - Pemilik: username = pemilik, password = password123');
    }
}
