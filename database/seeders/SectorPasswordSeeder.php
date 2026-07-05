<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SectorPassword;
use Illuminate\Support\Str;

class SectorPasswordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            SectorPassword::updateOrCreate(
                ['sector_number' => $i],
                [
                    'sector_name' => 'Sektor ' . $i,
                    'uuid_password' => Str::random(8),
                ]
            );
        }
    }
}
