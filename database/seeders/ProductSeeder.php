<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
        {
            $colors = DB::table('colors')->pluck('id', 'nama_warna');
    
            // [nama produk, harga, daftar ukuran]
            $jenis = [
                ['Gamis Dewasa',   349000, ['S', 'M', 'L', 'XL']],
                ['Koko Dewasa',    229000, ['M', 'L', 'XL']],
                ['Gamis Anak',     229000, ['S', 'M']],
                ['Koko Anak',      179000, ['S']],
                ['Hijab Signature', 169000, ['All Size']],
            ];
    
            foreach ($jenis as [$nama, $harga, $ukuranList]) {
                foreach ($colors as $namaWarna => $colorId) {
                    foreach ($ukuranList as $ukuran) {
                        DB::table('products')->updateOrInsert(
                            [
                                'nama_produk' => $nama,
                                'ukuran'      => $ukuran,
                                'color_id'    => $colorId,
                            ],
                            [
                                'harga'      => $harga,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );
                    }
                }
            }
        }
}
