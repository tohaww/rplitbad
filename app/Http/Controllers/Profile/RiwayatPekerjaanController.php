<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\RiwayatPekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatPekerjaanController extends Controller
{
    /**
     * Display the riwayat pekerjaan form page.
     */
    public function create()
    {
        return view('pages.riwayat-pekerjaan');
    }

    /**
     * Store riwayat pekerjaan data.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'instansi_perusahaan' => 'required|string|max:255',
            'periode_kerja' => 'required|string|max:255',
            'posisi_jabatan' => 'required|string|max:255',
            'uraian_tugas' => 'required|string',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // Create new riwayat pekerjaan
        RiwayatPekerjaan::create([
            'mahasiswa_id' => $mahasiswa->id_mahasiswa,
            'instansi_perusahaan' => $validated['instansi_perusahaan'],
            'periode_kerja' => $validated['periode_kerja'],
            'posisi_jabatan' => $validated['posisi_jabatan'],
            'uraian_tugas' => $validated['uraian_tugas'],
        ]);

        return redirect()->route('profile')->with('success', 'Riwayat pekerjaan berhasil ditambahkan!');
    }

    /**
     * Update riwayat pekerjaan data.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'instansi_perusahaan' => 'required|string|max:255',
            'periode_kerja' => 'required|string|max:255',
            'posisi_jabatan' => 'required|string|max:255',
            'uraian_tugas' => 'required|string',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $riwayatPekerjaan = RiwayatPekerjaan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        $riwayatPekerjaan->update($validated);

        return redirect()->route('profile')->with('success', 'Riwayat pekerjaan berhasil diperbarui!');
    }

    /**
     * Delete riwayat pekerjaan data.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $riwayatPekerjaan = RiwayatPekerjaan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        $riwayatPekerjaan->delete();

        return redirect()->route('profile')->with('success', 'Riwayat pekerjaan berhasil dihapus!');
    }

    /**
     * Upload file for riwayat pekerjaan.
     */
    public function upload(Request $request, $id)
    {
        $validated = $request->validate([
            'keterangan' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        $riwayatPekerjaan = RiwayatPekerjaan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        // Upload file
        $file = $request->file('file');
        $filename = 'pekerjaan_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('riwayat-pekerjaan/' . $mahasiswa->id_mahasiswa, $filename, 'public');

        // Update riwayat pekerjaan with file path
        $riwayatPekerjaan->update([
            'file_path' => $path,
            'keterangan_file' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('profile')->with('success', 'File berhasil diupload!');
    }

    /**
     * Download file for riwayat pekerjaan.
     */
    public function download($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $riwayatPekerjaan = RiwayatPekerjaan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        if (!$riwayatPekerjaan->file_path || !Storage::disk('public')->exists($riwayatPekerjaan->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($riwayatPekerjaan->file_path);
    }
}
