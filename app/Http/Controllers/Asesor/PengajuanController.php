<?php

namespace App\Http\Controllers\Asesor;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPerolehanKredit;
use App\Models\PengajuanTransferKredit;
use App\Models\FormEvaluasi;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    public function show($jenis, $id)
    {
        $mahasiswa = null;
        $pengajuanAktif = null;
        $jenisPengajuanAktif = null;
        $semuaPerolehanKredit = collect();
        $semuaTransferKredit = collect();

        if ($jenis === 'perolehan-kredit') {
            $pengajuanAktif = PengajuanPerolehanKredit::with(['mahasiswa.user', 'programStudi'])
                ->findOrFail($id);
            $mahasiswa = $pengajuanAktif->mahasiswa;
            $jenisPengajuanAktif = 'Perolehan Kredit';
            $mahasiswaId = $pengajuanAktif->mahasiswa_id;

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
        } elseif ($jenis === 'transfer-kredit') {
            $pengajuanAktif = PengajuanTransferKredit::with(['mahasiswa.user', 'matkuls'])
                ->findOrFail($id);
            $mahasiswa = $pengajuanAktif->mahasiswa;
            $jenisPengajuanAktif = 'Transfer Kredit';
            $mahasiswaId = $pengajuanAktif->mahasiswa_id;

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
        } else {
            abort(404);
        }

        return view('asesor.pengajuan-detail', [
            'mahasiswa' => $mahasiswa,
            'pengajuanAktif' => $pengajuanAktif,
            'jenisPengajuanAktif' => $jenisPengajuanAktif,
            'semuaPerolehanKredit' => $semuaPerolehanKredit,
            'semuaTransferKredit' => $semuaTransferKredit,
        ]);
    }

    public function index(Request $request)
    {
        // Query untuk Perolehan Kredit - hanya yang sudah diajukan (status bukan Draft)
        $queryPerolehan = PengajuanPerolehanKredit::with(['mahasiswa.user', 'programStudi'])
            ->where('status', '!=', 'Draft');

        // Query untuk Transfer Kredit - hanya yang sudah diajukan
        $queryTransfer = PengajuanTransferKredit::with(['mahasiswa.user'])
            ->where('status', '!=', 'Draft');

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $queryPerolehan->where('status', $request->status);
            $queryTransfer->where('status', $request->status);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $queryPerolehan->whereDate('created_at', '>=', $request->tanggal_dari);
            $queryTransfer->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $queryPerolehan->whereDate('created_at', '<=', $request->tanggal_sampai);
            $queryTransfer->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            
            $queryPerolehan->where(function ($q) use ($search) {
                $q->where('no_bukti', 'like', "%{$search}%")
                    ->orWhereHas('mahasiswa', function ($mahasiswaQuery) use ($search) {
                        $mahasiswaQuery->where('nama', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($userQuery) use ($search) {
                                $userQuery->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
            });

            $queryTransfer->whereHas('mahasiswa', function ($mahasiswaQuery) use ($search) {
                $mahasiswaQuery->where('nama', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        // Get all data
        $perolehanKredit = $queryPerolehan->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'jenis' => 'Perolehan Kredit',
                'nama' => $item->mahasiswa->user->name ?? $item->mahasiswa->nama ?? '-',
                'email' => $item->mahasiswa->user->email ?? '-',
                'program_studi' => $item->programStudi->nama_prodi ?? '-',
                'status' => $item->status,
                'tanggal' => $item->created_at,
                'no_bukti' => $item->no_bukti ?? '-',
                'total_sks' => $item->total_sks ?? 0,
                'mahasiswa_id' => $item->mahasiswa_id,
            ];
        });

        $transferKredit = $queryTransfer->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'jenis' => 'Transfer Kredit',
                'nama' => $item->mahasiswa->user->name ?? $item->mahasiswa->nama ?? '-',
                'email' => $item->mahasiswa->user->email ?? '-',
                'program_studi' => $item->program_studi_tertuju ?? '-',
                'status' => $item->status,
                'tanggal' => $item->created_at,
                'no_bukti' => '-',
                'total_sks' => 0,
                'mahasiswa_id' => $item->mahasiswa_id,
            ];
        });

        // Gabungkan, grup per mahasiswa, ambil pengajuan terbaru per mahasiswa lalu urutkan
        $semuaPengajuan = $perolehanKredit->merge($transferKredit)
            ->groupBy('mahasiswa_id')
            ->map(function ($items) {
                return $items->sortByDesc('tanggal')->first();
            })
            ->values()
            ->sortByDesc('tanggal');

        // Get unique statuses for filter
        $statuses = collect()
            ->merge(PengajuanPerolehanKredit::select('status')->distinct()->pluck('status'))
            ->merge(PengajuanTransferKredit::select('status')->distinct()->pluck('status'))
            ->unique()
            ->sort()
            ->values();

        return view('asesor.pengajuan', [
            'pengajuan' => $semuaPengajuan,
            'statuses' => $statuses,
            'filters' => $request->only(['status', 'tanggal_dari', 'tanggal_sampai', 'search']),
        ]);
    }

    public function showEvaluasi($pengajuanId, $matkulId)
    {
        $user = Auth::user();
        if (!$user || !$user->isAsesor()) {
            abort(403);
        }

        $pengajuan = PengajuanPerolehanKredit::with(['mahasiswa.user', 'programStudi'])
            ->findOrFail($pengajuanId);

        $course = Course::findOrFail($matkulId);

        $evaluasi = FormEvaluasi::where('mahasiswa_id', $pengajuan->mahasiswa_id)
            ->where('id_matkul', $matkulId)
            ->orderBy('created_at', 'desc')
            ->get();

        $selectedMatkul = null;
        if ($pengajuan->mata_kuliah) {
            foreach ($pengajuan->mata_kuliah as $mk) {
                if (($mk['id_matkul'] ?? $mk['course_id'] ?? null) === $matkulId) {
                    $selectedMatkul = $mk;
                    break;
                }
            }
        }

        return view('asesor.pengajuan-evaluasi', [
            'pengajuan' => $pengajuan,
            'course' => $course,
            'selectedMatkul' => $selectedMatkul,
            'evaluasi' => $evaluasi,
        ]);
    }
}

