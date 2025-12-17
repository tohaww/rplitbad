<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPerolehanKredit;
use App\Models\PengajuanTransferKredit;
use App\Models\Mahasiswa;
use App\Models\KtpKk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseSubmissionController extends Controller
{
    /**
     * Display the course submission page.
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        $ktpKk = $mahasiswa ? KtpKk::find($mahasiswa->id_mahasiswa) : null;
        
        // Get pengajuan perolehan kredit for current user only
        $perolehanKredit = collect();
        if ($mahasiswa) {
            $perolehanKredit = PengajuanPerolehanKredit::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->with('programStudi')
                ->orderBy('created_at', 'desc')
                ->get();
        }

        // Get latest draft pengajuan for this user
        $draftPengajuan = null;
        if ($mahasiswa) {
            $draftPengajuan = PengajuanPerolehanKredit::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->where('status', 'Draft')
                ->orderBy('created_at', 'desc')
                ->first();
        }

        // Get pengajuan transfer kredit for current user only
        $transferKredit = collect();
        if ($mahasiswa) {
            $transferKredit = PengajuanTransferKredit::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->orderBy('created_at', 'desc')
                ->get();
        }

        $needsIdentity = $mahasiswa ? false : true;

        // Dokumen wajib lengkap jika identitas sudah ada
        $needsDocuments = false;
        if (! $needsIdentity) {
            $needsDocuments = ! $ktpKk || ! $ktpKk->foto || ! $ktpKk->ktp || ! $ktpKk->kk || ! $ktpKk->akta;
        }

        return view('pages.course-submission', [
            'perolehanKredit' => $perolehanKredit,
            'draftPengajuan' => $draftPengajuan,
            'transferKredit' => $transferKredit,
            'needsIdentity' => $needsIdentity,
            'needsDocuments' => $needsDocuments,
        ]);
    }
}
