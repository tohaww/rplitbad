<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanTransferKredit;
use App\Models\PengajuanPerolehanKredit;
use Illuminate\Http\Request;

class PengajuanTransferKreditController extends Controller
{
    public function index(Request $request)
    {
        $query = PengajuanTransferKredit::with(['mahasiswa.user']);

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan program studi tertuju
        if ($request->filled('program_studi_tertuju')) {
            $query->where('program_studi_tertuju', $request->program_studi_tertuju);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Pencarian berdasarkan nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('mahasiswa', function ($mahasiswaQuery) use ($search) {
                $mahasiswaQuery->where('nama', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $pengajuan = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get unique statuses for filter
        $statuses = PengajuanTransferKredit::select('status')
            ->distinct()
            ->orderBy('status')
            ->pluck('status');

        // Get unique program studi tertuju for filter
        $programStudiTertuju = PengajuanTransferKredit::select('program_studi_tertuju')
            ->distinct()
            ->whereNotNull('program_studi_tertuju')
            ->orderBy('program_studi_tertuju')
            ->pluck('program_studi_tertuju');

        return view('admin.pengajuan-transfer-kredit', [
            'pengajuan' => $pengajuan,
            'statuses' => $statuses,
            'programStudiTertuju' => $programStudiTertuju,
            'filters' => $request->only(['status', 'program_studi_tertuju', 'tanggal_dari', 'tanggal_sampai', 'search']),
        ]);
    }

    public function show($id)
    {
        $pengajuan = PengajuanTransferKredit::with(['mahasiswa.user', 'matkuls'])
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
            'jenisPengajuanAktif' => 'Transfer Kredit',
            'semuaPerolehanKredit' => $semuaPerolehanKredit,
            'semuaTransferKredit' => $semuaTransferKredit,
        ]);
    }
}

