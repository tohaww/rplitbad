<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPerolehanKredit;
use App\Models\PengajuanTransferKredit;
use App\Models\ProgramStudi;
use App\Models\FormEvaluasi;
use App\Models\Course;
use Illuminate\Http\Request;

class PengajuanPerolehanKreditController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanPerolehanKredit::with(['mahasiswa.user', 'programStudi']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan program studi
        if ($request->filled('program_studi_id')) {
            $query->where('program_studi_id', $request->program_studi_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Pencarian berdasarkan nama atau no bukti
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('no_bukti', 'like', "%{$search}%")
                    ->orWhereHas('mahasiswa', function ($mahasiswaQuery) use ($search) {
                        $mahasiswaQuery->where('nama', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', "%{$search}%");
                            });
                    });
            });
        }

        $pengajuan = $query->orderBy('created_at', 'desc')->paginate(15);
        $programStudis = ProgramStudi::orderBy('nama_prodi', 'asc')->get();

        // Get unique statuses for filter
        $statuses = PengajuanPerolehanKredit::select('status')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        return view('admin.pengajuan-perolehan-kredit', [
            'pengajuan' => $pengajuan,
            'programStudis' => $programStudis,
            'statuses' => $statuses,
            'filters' => $request->only(['status', 'program_studi_id', 'tanggal_dari', 'tanggal_sampai', 'search']),
        ]);
    }

    public function show($id)
    {
        $pengajuan = PengajuanPerolehanKredit::with(['mahasiswa.user', 'programStudi'])
            ->findOrFail($id);

        $mahasiswaId = $pengajuan->mahasiswa_id;

        // Ambil semua pengajuan perolehan kredit mahasiswa ini
        $semuaPerolehanKredit = PengajuanPerolehanKredit::with(['programStudi'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua pengajuan transfer kredit mahasiswa ini
        $semuaTransferKredit = PengajuanTransferKredit::with(['matkuls'])
            ->where('mahasiswa_id', $mahasiswaId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.pengajuan-detail', [
            'mahasiswa' => $pengajuan->mahasiswa,
            'pengajuanAktif' => $pengajuan,
            'jenisPengajuanAktif' => 'Perolehan Kredit',
            'semuaPerolehanKredit' => $semuaPerolehanKredit,
            'semuaTransferKredit' => $semuaTransferKredit,
        ]);
    }

    public function showEvaluasi($pengajuanId, $matkulId)
    {
        $pengajuan = PengajuanPerolehanKredit::with(['mahasiswa.user', 'programStudi'])
            ->findOrFail($pengajuanId);

        $course = Course::findOrFail($matkulId);

        // Ambil evaluasi untuk mahasiswa dan mata kuliah ini
        $evaluasi = FormEvaluasi::where('mahasiswa_id', $pengajuan->mahasiswa_id)
            ->where('id_matkul', $matkulId)
            ->orderBy('created_at', 'desc')
            ->get();

        // Cari mata kuliah yang dipilih dari pengajuan
        $selectedMatkul = null;
        if ($pengajuan->mata_kuliah) {
            foreach ($pengajuan->mata_kuliah as $mk) {
                if (($mk['id_matkul'] ?? $mk['course_id'] ?? null) === $matkulId) {
                    $selectedMatkul = $mk;
                    break;
                }
            }
        }

        return view('admin.pengajuan-evaluasi', [
            'pengajuan' => $pengajuan,
            'course' => $course,
            'selectedMatkul' => $selectedMatkul,
            'evaluasi' => $evaluasi,
        ]);
    }
}

