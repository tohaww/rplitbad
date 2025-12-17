<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display the courses page for students.
     */
    public function index(Request $request)
    {
        $programStudis = ProgramStudi::orderBy('kode_prodi', 'asc')->get();
        $courses = collect();

        // Jika program studi dipilih, ambil courses berdasarkan prodi
        if ($request->filled('program_studi')) {
            $kodeProdi = $request->input('program_studi');
            
            // Cari nama prodi berdasarkan kode prodi
            $programStudi = ProgramStudi::where('kode_prodi', $kodeProdi)->first();
            
            if ($programStudi) {
                // Ambil courses yang memiliki nama prodi yang sama
                $courses = Course::where('prodi', $programStudi->nama_prodi)
                    ->orderBy('kode_matkul', 'asc')
                    ->get();
            }
        }

        return view('pages.courses', [
            'programStudis' => $programStudis,
            'courses' => $courses,
        ]);
    }
}

