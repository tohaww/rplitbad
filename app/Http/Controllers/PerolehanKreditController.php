<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\PengajuanPerolehanKredit;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PerolehanKreditController extends Controller
{
    /**
     * Display the pengajuan perolehan kredit page.
     */
    public function create(Request $request)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        $programStudi = ProgramStudi::all();
        
        // Get pengajuan by ID if provided, otherwise get latest draft
        $pengajuanId = $request->input('id');
        
        $draftPengajuan = null;
        if ($mahasiswa) {
            if ($pengajuanId) {
                // Get specific pengajuan (can be any status) - ensure it belongs to current user
                $draftPengajuan = PengajuanPerolehanKredit::where('id', $pengajuanId)
                    ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                    ->with('programStudi')
                    ->first();
            } else {
                // Get latest draft pengajuan for this user
                $draftPengajuan = PengajuanPerolehanKredit::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                    ->where('status', 'Draft')
                    ->with('programStudi')
                    ->orderBy('created_at', 'desc')
                    ->first();
            }
        }
        
        return view('pages.pengajuan-perolehan-kredit', compact('programStudi', 'draftPengajuan'));
    }

    /**
     * Get courses by program studi ID.
     */
    public function getCoursesByProdi(Request $request)
    {
        $prodiId = $request->input('prodi_id');
        
        $programStudi = ProgramStudi::find($prodiId);
        
        if (!$programStudi) {
            return response()->json(['courses' => []]);
        }

        $courses = Course::where('prodi', $programStudi->nama_prodi)
            ->orderBy('kode_matkul', 'asc')
            ->get();

        return response()->json(['courses' => $courses]);
    }

    /**
     * Store draft pengajuan (when Set Program Studi is clicked).
     */
    public function storeDraft(Request $request)
    {
        $validated = $request->validate([
            'program_studi_id' => 'required|string|exists:program_studis,id',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        try {
            // Generate no_bukti
            $noBukti = 'PLK-' . date('Ymd') . '-' . str_pad($user->id, 4, '0', STR_PAD_LEFT) . '-' . time();

            // Create draft pengajuan
            $pengajuan = PengajuanPerolehanKredit::create([
                'mahasiswa_id' => $mahasiswa->id_mahasiswa,
                'program_studi_id' => $validated['program_studi_id'],
                'no_bukti' => $noBukti,
                'tanggal' => now(),
                'status' => 'Draft',
                'total_sks' => 0,
                'mata_kuliah' => [],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Program studi berhasil diset',
                'pengajuan_id' => $pengajuan->id,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Add mata kuliah to draft pengajuan.
     */
    public function addMataKuliah(Request $request)
    {
        $validated = $request->validate([
            'pengajuan_id' => 'required|integer|exists:pengajuan_perolehan_kredit,id',
            'id_matkul' => 'required|string|exists:courses,id_matkul',
            'kode_matkul' => 'required|string',
            'nama_matkul' => 'required|string',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        try {
            $pengajuan = PengajuanPerolehanKredit::where('id', $validated['pengajuan_id'])
                ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->first();

            if (!$pengajuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan tidak ditemukan',
                ], 404);
            }

            // Get current mata kuliah
            $mataKuliah = $pengajuan->mata_kuliah ?? [];
            
            // Check if course already exists
            $exists = collect($mataKuliah)->contains('id_matkul', $validated['id_matkul']);
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mata kuliah ini sudah ditambahkan',
                ], 400);
            }

            // Get course to calculate SKS
            $course = Course::find($validated['id_matkul']);
            $sks = $course->sks ?? 0;

            // Add new mata kuliah
            $mataKuliah[] = [
                'id_matkul' => $validated['id_matkul'],
                'kode_matkul' => $validated['kode_matkul'],
                'nama_matkul' => $validated['nama_matkul'],
            ];

            // Calculate total SKS
            $totalSks = 0;
            foreach ($mataKuliah as $mk) {
                $mkCourse = Course::find($mk['id_matkul']);
                if ($mkCourse) {
                    $totalSks += $mkCourse->sks ?? 0;
                }
            }

            // Update pengajuan
            $pengajuan->update([
                'mata_kuliah' => $mataKuliah,
                'total_sks' => $totalSks,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah berhasil ditambahkan',
                'total_sks' => $totalSks,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove mata kuliah from draft pengajuan.
     */
    public function removeMataKuliah(Request $request)
    {
        $validated = $request->validate([
            'pengajuan_id' => 'required|integer|exists:pengajuan_perolehan_kredit,id',
            'id_matkul' => 'required|string',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        try {
            $pengajuan = PengajuanPerolehanKredit::where('id', $validated['pengajuan_id'])
                ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->first();

            if (!$pengajuan) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengajuan tidak ditemukan',
                ], 404);
            }

            // Get current mata kuliah
            $mataKuliah = $pengajuan->mata_kuliah ?? [];
            
            // Remove mata kuliah
            $mataKuliah = collect($mataKuliah)->reject(function ($mk) use ($validated) {
                return ($mk['id_matkul'] ?? $mk['course_id'] ?? '') === $validated['id_matkul'];
            })->values()->toArray();

            // Calculate total SKS
            $totalSks = 0;
            foreach ($mataKuliah as $mk) {
                $mkCourse = Course::find($mk['id_matkul'] ?? $mk['course_id'] ?? '');
                if ($mkCourse) {
                    $totalSks += $mkCourse->sks ?? 0;
                }
            }

            // Update pengajuan
            $pengajuan->update([
                'mata_kuliah' => $mataKuliah,
                'total_sks' => $totalSks,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Mata kuliah berhasil dihapus',
                'total_sks' => $totalSks,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store pengajuan perolehan kredit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_studi_id' => 'required|string|exists:program_studis,id',
            'courses' => 'required|array|min:1',
            'courses.*.id_matkul' => 'required|string|exists:courses,id_matkul',
            'courses.*.kode_matkul' => 'required|string',
            'pengajuan_id' => 'nullable|integer|exists:pengajuan_perolehan_kredit,id',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        DB::beginTransaction();
        try {
            // Check if pengajuan_id is provided (from draft)
            if ($request->has('pengajuan_id') && $request->pengajuan_id) {
                $pengajuan = PengajuanPerolehanKredit::where('id', $request->pengajuan_id)
                    ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                    ->first();
                
                if (!$pengajuan) {
                    throw new \Exception('Pengajuan tidak ditemukan');
                }
            } else {
                // Generate no_bukti (contoh: format tanggal + id_mahasiswa)
                $noBukti = 'PLK-' . date('Ymd') . '-' . str_pad($mahasiswa->id_mahasiswa, 4, '0', STR_PAD_LEFT);

                // Create new pengajuan
                $pengajuan = PengajuanPerolehanKredit::create([
                    'mahasiswa_id' => $mahasiswa->id_mahasiswa,
                    'program_studi_id' => $validated['program_studi_id'],
                    'no_bukti' => $noBukti,
                    'tanggal' => now(),
                    'status' => 'Sudah Diajukan',
                    'total_sks' => 0,
                ]);
            }

            // Prepare mata kuliah data
            $mataKuliah = [];
            $totalSks = 0;
            
            foreach ($validated['courses'] as $courseData) {
                $course = Course::find($courseData['id_matkul']);
                if ($course) {
                    $totalSks += $course->sks ?? 0;
                    
                    $mataKuliah[] = [
                        'id_matkul' => $courseData['id_matkul'],
                        'kode_matkul' => $courseData['kode_matkul'],
                        'nama_matkul' => $course->nama_matkul ?? '',
                    ];
                }
            }

            // Update pengajuan with mata kuliah, total SKS, and status
            $pengajuan->update([
                'total_sks' => $totalSks,
                'mata_kuliah' => $mataKuliah,
                'status' => 'Sudah Diajukan', // Update status from Draft to Sudah Diajukan
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pengajuan berhasil dikirim!',
                'pengajuan_id' => $pengajuan->id,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan pengajuan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete pengajuan perolehan kredit.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        $pengajuan = PengajuanPerolehanKredit::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->first();
        
        if (!$pengajuan) {
            return redirect()->route('course-submission')
                ->with('error', 'Pengajuan tidak ditemukan atau Anda tidak memiliki akses.');
        }
        
        $pengajuan->delete();
        
        return redirect()->route('course-submission')
            ->with('success', 'Pengajuan berhasil dihapus.');
    }
}
