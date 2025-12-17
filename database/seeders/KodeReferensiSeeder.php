<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\KodeReferensi;

class KodeReferensiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $kodeReferensi = [
            [
                'nama_referensi' => 'pmb',
                'kode_referensi' => 'P001',
            ],
            [
                'nama_referensi' => 'optimis',
                'kode_referensi' => 'O001',
            ],
            [
                'nama_referensi' => 'garuda cyber',
                'kode_referensi' => 'G001',
            ],
        ];

        foreach ($kodeReferensi as $kode) {
            KodeReferensi::updateOrCreate(
                ['nama_referensi' => $kode['nama_referensi']],
                ['kode_referensi' => $kode['kode_referensi']]
            );
        }
    }
}
