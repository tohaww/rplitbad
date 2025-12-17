<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AsalPerguruanTinggi;

class AsalPerguruanTinggiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $asalPerguruanTinggi = [
            ['nama' => 'ITBAD/STIEAD/AKPM'],
            ['nama' => 'Selain ITBAD/STIEAD/AKPM'],
        ];

        foreach ($asalPerguruanTinggi as $item) {
            AsalPerguruanTinggi::updateOrCreate(
                ['nama' => $item['nama']],
                $item
            );
        }
    }
}
