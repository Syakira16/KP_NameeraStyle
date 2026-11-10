<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ColorSeeder extends Seeder
{
    public function run(): void
    {
        $warna = ['Ivory Blush', 'Mahogany', 'Burgundy', 'Misty Blue'];

        foreach ($warna as $nama) {
            DB::table('colors')->updateOrInsert(
                ['nama_warna' => $nama],
                ['created_at' => now(), 'updated_at' => now()]
            );
        }
    }
}
