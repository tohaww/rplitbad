<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\RiwayatPendidikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RiwayatPendidikanController extends Controller
{
    /**
     * Display the riwayat pendidikan form page.
     */
    public function create()
    {
        return view('pages.riwayat-pendidikan');
    }

    /**
     * Store riwayat pendidikan data.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'tahun_lulus' => 'required|string|max:255',
            'jurusan_program_studi' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // Create new riwayat pendidikan
        RiwayatPendidikan::create([
            'mahasiswa_id' => $mahasiswa->id_mahasiswa,
            'nama_sekolah' => $validated['nama_sekolah'],
            'tahun_lulus' => $validated['tahun_lulus'],
            'jurusan_program_studi' => $validated['jurusan_program_studi'],
        ]);

        return redirect()->route('profile')->with('success', 'Riwayat pendidikan berhasil ditambahkan!');
    }

    /**
     * Update riwayat pendidikan data.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_sekolah' => 'required|string|max:255',
            'tahun_lulus' => 'required|string|max:255',
            'jurusan_program_studi' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $riwayatPendidikan = RiwayatPendidikan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        $riwayatPendidikan->update($validated);

        return redirect()->route('profile')->with('success', 'Riwayat pendidikan berhasil diperbarui!');
    }

    /**
     * Delete riwayat pendidikan data.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $riwayatPendidikan = RiwayatPendidikan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        $riwayatPendidikan->delete();

        return redirect()->route('profile')->with('success', 'Riwayat pendidikan berhasil dihapus!');
    }

    /**
     * Upload file for riwayat pendidikan.
     */
    public function upload(Request $request, $id)
    {
        $validated = $request->validate([
            'keterangan' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $riwayatPendidikan = RiwayatPendidikan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        // Upload file
        $file = $request->file('file');
        $filename = 'riwayat_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('riwayat-pendidikan/' . $mahasiswa->id_mahasiswa, $filename, 'public');

        // Update riwayat pendidikan with file path
        $riwayatPendidikan->update([
            'file_path' => $path,
            'keterangan_file' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('profile')->with('success', 'File berhasil diupload!');
    }

    /**
     * Download file for riwayat pendidikan.
     */
    public function download($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        $riwayatPendidikan = RiwayatPendidikan::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        if (!$riwayatPendidikan->file_path || !Storage::disk('public')->exists($riwayatPendidikan->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($riwayatPendidikan->file_path);
    }
}
