<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPerolehanKredit;
use App\Models\PengajuanTransferKredit;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PengajuanDashboardController extends Controller
{
    public function index()
    {
        // Statistik Pengajuan Perolehan Kredit
        $perolehanKreditTotal = PengajuanPerolehanKredit::count();
        $perolehanKreditHariIni = PengajuanPerolehanKredit::whereDate('created_at', Carbon::today())->count();
        $perolehanKreditMingguIni = PengajuanPerolehanKredit::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $perolehanKreditBulanIni = PengajuanPerolehanKredit::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Statistik Pengajuan Transfer Kredit
        $transferKreditTotal = PengajuanTransferKredit::count();
        $transferKreditHariIni = PengajuanTransferKredit::whereDate('created_at', Carbon::today())->count();
        $transferKreditMingguIni = PengajuanTransferKredit::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $transferKreditBulanIni = PengajuanTransferKredit::whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // Statistik per Status - Perolehan Kredit
        $perolehanKreditByStatus = PengajuanPerolehanKredit::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        // Statistik per Status - Transfer Kredit
        $transferKreditByStatus = PengajuanTransferKredit::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->get()
            ->pluck('total', 'status');

        // Pengajuan Pending (yang perlu ditinjau)
        $perolehanKreditPending = PengajuanPerolehanKredit::where('status', 'Sudah Diajukan')
            ->with(['mahasiswa.user', 'programStudi'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $transferKreditPending = PengajuanTransferKredit::where('status', 'Sudah Diajukan')
            ->with(['mahasiswa.user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Pengajuan Terbaru
        $pengajuanTerbaru = collect();
        
        $perolehanTerbaru = PengajuanPerolehanKredit::with(['mahasiswa.user', 'programStudi'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->mahasiswa->user->name ?? $item->mahasiswa->nama ?? '-',
                    'kode_referensi' => $item->mahasiswa->user->kode_referensi ?? '-',
                    'program_studi' => $item->programStudi->nama_prodi ?? '-',
                    'status' => $item->status,
                    'tanggal' => $item->created_at,
                ];
            });

        $transferTerbaru = PengajuanTransferKredit::with(['mahasiswa.user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'nama' => $item->mahasiswa->user->name ?? $item->mahasiswa->nama ?? '-',
                    'kode_referensi' => $item->mahasiswa->user->kode_referensi ?? '-',
                    'program_studi' => $item->program_studi_tertuju ?? '-',
                    'status' => $item->status,
                    'tanggal' => $item->created_at,
                ];
            });

        $pengajuanTerbaru = $perolehanTerbaru->merge($transferTerbaru)
            ->sortByDesc('tanggal')
            ->take(10);

        return view('admin.pengajuan-dashboard', [
            'perolehanKreditTotal' => $perolehanKreditTotal,
            'perolehanKreditHariIni' => $perolehanKreditHariIni,
            'perolehanKreditMingguIni' => $perolehanKreditMingguIni,
            'perolehanKreditBulanIni' => $perolehanKreditBulanIni,
            'transferKreditTotal' => $transferKreditTotal,
            'transferKreditHariIni' => $transferKreditHariIni,
            'transferKreditMingguIni' => $transferKreditMingguIni,
            'transferKreditBulanIni' => $transferKreditBulanIni,
            'perolehanKreditByStatus' => $perolehanKreditByStatus,
            'transferKreditByStatus' => $transferKreditByStatus,
            'perolehanKreditPending' => $perolehanKreditPending,
            'transferKreditPending' => $transferKreditPending,
            'pengajuanTerbaru' => $pengajuanTerbaru,
        ]);
    }
}

