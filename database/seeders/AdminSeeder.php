<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek apakah email admin sudah ada agar tidak terjadi duplikat (error) saat di-run berkali-kali
        if (!User::where('email', 'admin@rotasi.com')->exists()) {
            User::create([
                'custom_id' => 'ADM-' . date('Ym') . rand(1000, 9999),
                'name' => 'Admin ROTASI',
                'email' => 'admin@rotasi.com',
                'nim' => '0000000',
                'role' => 'admin',
                'is_approved' => true,
                'is_active' => true,
                // Menggunakan Hash::make() sangat aman dan mudah untuk diatur
                'password' => Hash::make('rahasia123'),
                'login_password_hash' => Hash::make('rahasia123'),
            ]);
            
            $this->command->info('Akun Admin berhasil dibuat!');
        } else {
            $this->command->warn('Akun Admin sudah pernah dibuat sebelumnya.');
        }
    }
}
