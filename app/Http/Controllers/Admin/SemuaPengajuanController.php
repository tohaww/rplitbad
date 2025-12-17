<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPerolehanKredit;
use App\Models\PengajuanTransferKredit;
use App\Models\RiwayatPendidikan;
use App\Models\PelatihanProfesional;
use App\Models\KegiatanIlmiah;
use App\Models\PenghargaanPiagam;
use App\Models\RiwayatPekerjaan;
use App\Models\KtpKk;
use Illuminate\Http\Request;

class SemuaPengajuanController extends Controller
{
    public function index(Request $request)
    {
        // Query untuk Perolehan Kredit
        $queryPerolehan = PengajuanPerolehanKredit::with(['mahasiswa.user', 'programStudi']);

        // Query untuk Transfer Kredit
        $queryTransfer = PengajuanTransferKredit::with(['mahasiswa.user']);

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
                                $userQuery->where('name', 'like', "%{$search}%");
                            });
                    });
            });

            $queryTransfer->whereHas('mahasiswa', function ($mahasiswaQuery) use ($search) {
                $mahasiswaQuery->where('nama', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // Get all data
        $perolehanKredit = $queryPerolehan->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'jenis' => 'Perolehan Kredit',
                'nama' => $item->mahasiswa->user->name ?? $item->mahasiswa->nama ?? '-',
                'program_studi' => $item->programStudi->nama_prodi ?? '-',
                'status' => $item->status,
                'tanggal' => $item->created_at,
                'no_bukti' => $item->no_bukti ?? '-',
                'total_sks' => $item->total_sks ?? 0,
                'mahasiswa_id' => $item->mahasiswa_id,
                'kode_referensi' => $item->mahasiswa->user->kode_referensi ?? '-',
            ];
        });

        $transferKredit = $queryTransfer->get()->map(function ($item) {
            return [
                'id' => $item->id,
                'jenis' => 'Transfer Kredit',
                'nama' => $item->mahasiswa->user->name ?? $item->mahasiswa->nama ?? '-',
                'program_studi' => $item->program_studi_tertuju ?? '-',
                'status' => $item->status,
                'tanggal' => $item->created_at,
                'no_bukti' => '-',
                'total_sks' => 0,
                'mahasiswa_id' => $item->mahasiswa_id,
                'kode_referensi' => $item->mahasiswa->user->kode_referensi ?? '-',
            ];
        });

        // Gabungkan, lalu grup berdasarkan mahasiswa supaya satu mahasiswa hanya muncul sekali
        // Ambil pengajuan terbaru per mahasiswa
        $semuaPengajuan = $perolehanKredit->merge($transferKredit)
            ->groupBy('mahasiswa_id')
            ->map(function ($items) {
                return $items->sortByDesc('tanggal')->first();
            })
            ->values()
            ->sortByDesc('tanggal');

        // Pagination manual
        $perPage = 15;
        $currentPage = $request->get('page', 1);
        $items = $semuaPengajuan->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $total = $semuaPengajuan->count();
        $pengajuan = new \Illuminate\Pagination\LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        // Get unique statuses for filter
        $statusesPerolehan = PengajuanPerolehanKredit::select('status')
            ->distinct()
            ->pluck('status');
        $statusesTransfer = PengajuanTransferKredit::select('status')
            ->distinct()
            ->pluck('status');
        $statuses = $statusesPerolehan->merge($statusesTransfer)->unique()->sort()->values();

        return view('admin.semua-pengajuan', [
            'pengajuan' => $pengajuan,
            'statuses' => $statuses,
            'filters' => $request->only(['status', 'tanggal_dari', 'tanggal_sampai', 'search']),
        ]);
    }

    public function show(Request $request, $jenis, $id)
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

        return view('admin.pengajuan-detail', [
            'mahasiswa' => $mahasiswa,
            'pengajuanAktif' => $pengajuanAktif,
            'jenisPengajuanAktif' => $jenisPengajuanAktif,
            'semuaPerolehanKredit' => $semuaPerolehanKredit,
            'semuaTransferKredit' => $semuaTransferKredit,
            'dariSemuaPengajuan' => true, // Flag untuk menandai dari halaman semua pengajuan
        ]);
    }

    public function showIdentitas($mahasiswaId)
    {
        $mahasiswa = \App\Models\Mahasiswa::with('user')->findOrFail($mahasiswaId);
        
        // Ambil semua data terkait mahasiswa
        $riwayatPendidikan = \App\Models\RiwayatPendidikan::where('mahasiswa_id', $mahasiswaId)->get();
        $pelatihanProfesional = \App\Models\PelatihanProfesional::where('mahasiswa_id', $mahasiswaId)->get();
        $kegiatanIlmiah = \App\Models\KegiatanIlmiah::where('mahasiswa_id', $mahasiswaId)->get();
        $penghargaanPiagam = \App\Models\PenghargaanPiagam::where('mahasiswa_id', $mahasiswaId)->get();
        $riwayatPekerjaan = \App\Models\RiwayatPekerjaan::where('mahasiswa_id', $mahasiswaId)->get();
        $ktpKk = \App\Models\KtpKk::find($mahasiswaId);

        return view('admin.identitas-mahasiswa', [
            'mahasiswa' => $mahasiswa,
            'riwayatPendidikan' => $riwayatPendidikan,
            'pelatihanProfesional' => $pelatihanProfesional,
            'kegiatanIlmiah' => $kegiatanIlmiah,
            'penghargaanPiagam' => $penghargaanPiagam,
            'riwayatPekerjaan' => $riwayatPekerjaan,
            'ktpKk' => $ktpKk,
        ]);
    }
}

