<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User Mahasiswa
        User::updateOrCreate(
            ['email' => 'mahasiswa@example.com'],
            [
                'name' => 'Mahasiswa Contoh',
                'password' => Hash::make('password123'),
                'role' => 'mahasiswa',
            ]
        );

        // User Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        // Seed courses
        $this->call(CourseSeeder::class);
        
        // Seed program studi
        $this->call(ProgramStudiSeeder::class);
    }
}
