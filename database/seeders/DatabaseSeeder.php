<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $this->call(PublicContentSeeder::class);

        User::updateOrCreate(
            ['email' => 'peserta@rotasi.id'],
            [
                'name' => 'Dummy Peserta',
                'nim' => '12345678',
                'role' => 'peserta',
                'sektor' => 1,
                'password' => bcrypt('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'acara@rotasi.id'],
            [
                'name' => 'Dummy Acara',
                'nim' => 'ACR-123',
                'role' => 'acara',
                'password' => bcrypt('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'admin@rotasi.id'],
            [
                'name' => 'Dummy Admin',
                'nim' => 'ADM-123',
                'role' => 'admin',
                'password' => bcrypt('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'mentor@rotasi.id'],
            [
                'name' => 'Dummy Mentor',
                'nim' => 'MNT-123',
                'role' => 'mentor',
                'password' => bcrypt('password'),
            ]
        );
    }
}
