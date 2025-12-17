<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $courses = [
            [
                'id_matkul' => 'MK001',
                'kode_matkul' => 'MK001',
                'nama_matkul' => 'Pemrograman Web',
                'prodi' => 'Teknik Informatika',
            ],
            [
                'id_matkul' => 'MK002',
                'kode_matkul' => 'MK002',
                'nama_matkul' => 'Basis Data',
                'prodi' => 'Teknik Informatika',
            ],
            [
                'id_matkul' => 'MK003',
                'kode_matkul' => 'MK003',
                'nama_matkul' => 'Algoritma dan Struktur Data',
                'prodi' => 'Teknik Informatika',
            ],
            [
                'id_matkul' => 'MK004',
                'kode_matkul' => 'MK004',
                'nama_matkul' => 'Jaringan Komputer',
                'prodi' => 'Teknik Informatika',
            ],
            [
                'id_matkul' => 'MK005',
                'kode_matkul' => 'MK005',
                'nama_matkul' => 'Sistem Operasi',
                'prodi' => 'Teknik Informatika',
            ],
            [
                'id_matkul' => 'MK006',
                'kode_matkul' => 'MK006',
                'nama_matkul' => 'Kalkulus',
                'prodi' => 'Matematika',
            ],
            [
                'id_matkul' => 'MK007',
                'kode_matkul' => 'MK007',
                'nama_matkul' => 'Fisika Dasar',
                'prodi' => 'Fisika',
            ],
            [
                'id_matkul' => 'MK008',
                'kode_matkul' => 'MK008',
                'nama_matkul' => 'Kimia Dasar',
                'prodi' => 'Kimia',
            ],
        ];

        foreach ($courses as $course) {
            Course::updateOrCreate(
                ['id_matkul' => $course['id_matkul']],
                $course
            );
        }
    }
}
