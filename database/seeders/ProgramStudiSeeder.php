<?php

namespace Database\Seeders;

use App\Models\ProgramStudi;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProgramStudiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $programStudis = [
            [
                'id' => 'PRODI001',
                'kode_prodi' => 'TI',
                'nama_prodi' => 'Teknik Informatika',
            ],
            [
                'id' => 'PRODI002',
                'kode_prodi' => 'MTK',
                'nama_prodi' => 'Matematika',
            ],
            [
                'id' => 'PRODI003',
                'kode_prodi' => 'FIS',
                'nama_prodi' => 'Fisika',
            ],
            [
                'id' => 'PRODI004',
                'kode_prodi' => 'KIM',
                'nama_prodi' => 'Kimia',
            ],
            [
                'id' => 'PRODI005',
                'kode_prodi' => 'SI',
                'nama_prodi' => 'Sistem Informasi',
            ],
        ];

        foreach ($programStudis as $prodi) {
            ProgramStudi::updateOrCreate(
                ['id' => $prodi['id']],
                $prodi
            );
        }
    }
}
