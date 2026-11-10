<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $nama = [
            'Aisyah Putri', 'Siti Nurhaliza', 'Dewi Lestari', 'Rina Marlina', 'Nurul Fadilah',
            'Fitri Handayani', 'Yuni Astuti', 'Lilis Suryani', 'Eka Wulandari', 'Ratna Sari',
            'Maya Anggraini', 'Intan Permata', 'Sri Rahayu', 'Winda Kurnia', 'Alya Rahma',
            'Citra Maharani', 'Nabila Zahra', 'Salsabila Aulia', 'Hana Pertiwi', 'Mega Safitri',
            'Tiara Amelia', 'Desi Ratnasari', 'Neneng Komala', 'Euis Rohaeni', 'Teti Suhartini',
            'Ani Yulianti', 'Lia Amalia', 'Rizka Maulida', 'Putri Ayu', 'Elis Nurjanah',
            'Susi Susanti', 'Wati Setiawati', 'Yeni Purwanti', 'Rosa Melati', 'Dian Kusuma',
            'Farah Nadia', 'Gina Amalia', 'Heni Marlina', 'Imas Masitoh', 'Jihan Kamila',
        ];
 
        $kota = [
            ['Cianjur', 'Jawa Barat'],
            ['Bandung', 'Jawa Barat'],
            ['Bogor', 'Jawa Barat'],
            ['Sukabumi', 'Jawa Barat'],
            ['Jakarta Timur', 'DKI Jakarta'],
            ['Tangerang', 'Banten'],
            ['Semarang', 'Jawa Tengah'],
            ['Surabaya', 'Jawa Timur'],
            ['Medan', 'Sumatera Utara'],
            ['Makassar', 'Sulawesi Selatan'],
        ];
 
        $jalan = ['Mawar', 'Melati', 'Anggrek', 'Kenanga', 'Cempaka', 'Merdeka', 'Pahlawan', 'Sudirman'];
        $prefix = ['0812', '0813', '0821', '0852', '0857', '0878'];
 
        foreach ($nama as $i => $n) {
            [$namaKota, $provinsi] = $kota[$i % count($kota)];
 
            // 4 digit prefix + 8 digit = 12 karakter, dijamin unik per $i
            $noHp = $prefix[$i % count($prefix)] . str_pad((string) (31000000 + $i * 73117), 8, '0', STR_PAD_LEFT);
 
            $alamat = 'Jl. ' . $jalan[$i % count($jalan)] . ' No. ' . (3 + $i * 2)
                . ' RT 0' . (1 + $i % 5) . ' RW 0' . (1 + $i % 4)
                . ', ' . $namaKota . ', ' . $provinsi;
 
            DB::table('customers')->updateOrInsert(
                ['no_hp' => $noHp],
                [
                    'nama'       => $n,
                    'alamat'     => $alamat,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
