<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
    {
        // Buat role superadmin dan admin (atau sesuaikan dengan role yang Anda gunakan)
        Role::create(['role_name' => 'superadmin']);
        Role::create(['role_name' => 'admin']);
        // Tambahkan role lain jika diperlukan
        // Role::create(['role_name' => 'user']);
    }
}
