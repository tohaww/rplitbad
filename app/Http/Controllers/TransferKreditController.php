<?php

namespace App\Http\Controllers;

use App\Models\Mahasiswa;
use App\Models\PengajuanTransferKredit;
use App\Models\TransferKreditMatkul;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferKreditController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        // Opsi jenjang pendidikan yang ditampilkan di form
        $jenjangOptions = [
            'D3' => 'Diploma 3 (D3)',
            'S1' => 'Strata 1 (S1)',
            'S2' => 'Strata 2 (S2)',
        ];

        // Ambil pengajuan transfer kredit terakhir milik mahasiswa (jika ada)
        $lastPengajuan = null;
        $matkuls = collect();
        if ($mahasiswa) {
            $lastPengajuan = PengajuanTransferKredit::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                ->orderByDesc('created_at')
                ->first();

            if ($lastPengajuan) {
                $matkuls = $lastPengajuan->matkuls()->orderBy('id')->get();
            }
        }
        $programStudi = \App\Models\ProgramStudi::all();

        return view('pages.transfer-kredit-create', compact(
            'mahasiswa',
            'jenjangOptions',
            'programStudi',
            'lastPengajuan',
            'matkuls',
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'perguruan_tinggi_asal' => 'required|string|max:255',
            'jenjang_pendidikan' => 'required|string|max:50',
            'program_studi_asal' => 'required|string|max:255',
            'nim_asal' => 'required|string|max:50',
            'program_studi_tertuju' => 'required|string|max:255',
            'lokasi_kuliah_tertuju' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Jika sudah pernah mengajukan, update data yang ada.
        // Kalau belum ada, buat baru.
        $existing = PengajuanTransferKredit::where('mahasiswa_id', $mahasiswa->id_mahasiswa)->first();

        $pengajuan = PengajuanTransferKredit::updateOrCreate(
            ['mahasiswa_id' => $mahasiswa->id_mahasiswa],
            [
                'perguruan_tinggi_asal' => $validated['perguruan_tinggi_asal'],
                'jenjang_pendidikan' => $validated['jenjang_pendidikan'],
                'program_studi_asal' => $validated['program_studi_asal'],
                'nim_asal' => $validated['nim_asal'],
                'program_studi_tertuju' => $validated['program_studi_tertuju'],
                'lokasi_kuliah_tertuju' => $validated['lokasi_kuliah_tertuju'] ?? null,
                'status' => $existing->status ?? 'Draft',
            ]
        );

        $message = $existing
            ? 'Pengajuan Transfer Kredit berhasil diubah.'
            : 'Pengajuan Transfer Kredit berhasil disimpan.';

        return back()->with('success', $message);
    }

    public function storeMatkul(Request $request)
    {
        $validated = $request->validate([
            'kode_matkul' => 'required|string|max:50',
            'nama_matakuliah' => 'required|string|max:255',
            'sks' => 'required|integer|min:1',
            'nilai' => 'required|string|max:5',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        $pengajuan = PengajuanTransferKredit::where('mahasiswa_id', $mahasiswa->id_mahasiswa)->firstOrFail();

        $nextNotab = TransferKreditMatkul::where('pengajuan_id', $pengajuan->id)->max('notab');
        $nextNotab = $nextNotab ? $nextNotab + 1 : 1;

        TransferKreditMatkul::create([
            'pengajuan_id' => $pengajuan->id,
            'mahasiswa_id' => $mahasiswa->id_mahasiswa,
            'notab' => $nextNotab,
            'kode_matkul_asal' => $validated['kode_matkul'],
            'nama_matkul_asal' => $validated['nama_matakuliah'],
            'sks_asal' => $validated['sks'],
            'nilai_asal' => $validated['nilai'],
        ]);

        return back()->with('success', 'Mata kuliah transfer kredit berhasil disimpan.');
    }

    public function destroyMatkul($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        $matkul = TransferKreditMatkul::findOrFail($id);
        
        // Pastikan matkul ini milik pengajuan mahasiswa yang login
        $pengajuan = PengajuanTransferKredit::where('id', $matkul->pengajuan_id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        $matkul->delete();

        return back()->with('success', 'Mata kuliah transfer kredit berhasil dihapus.');
    }

    public function destroy($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        $pengajuan = PengajuanTransferKredit::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        // Hapus semua matkul terkait terlebih dahulu
        $pengajuan->matkuls()->delete();

        // Hapus pengajuan
        $pengajuan->delete();

        return redirect()->route('course-submission')
            ->with('success', 'Pengajuan Transfer Kredit berhasil dihapus.');
    }

    public function submit($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();

        $pengajuan = PengajuanTransferKredit::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        // Pastikan ada matakuliah yang sudah disimpan
        $matkuls = $pengajuan->matkuls()->count();
        if ($matkuls === 0) {
            return back()->with('error', 'Harap tambahkan matakuliah terlebih dahulu sebelum mengajukan penyetaraan.');
        }

        // Update status menjadi Sudah Diajukan
        $pengajuan->update([
            'status' => 'Sudah Diajukan'
        ]);

        return redirect()->route('transfer-kredit.create')
            ->with('success', 'Pengajuan penyetaraan berhasil diajukan.');
    }
}
