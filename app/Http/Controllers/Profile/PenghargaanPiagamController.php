<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\PenghargaanPiagam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PenghargaanPiagamController extends Controller
{
    /**
     * Display the penghargaan piagam form page.
     */
    public function create()
    {
        return view('pages.penghargaan-piagam');
    }

    /**
     * Store penghargaan piagam data.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:255',
            'bentuk_penghargaan' => 'required|string|max:255',
            'pemberi' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // Create new penghargaan piagam
        PenghargaanPiagam::create([
            'mahasiswa_id' => $mahasiswa->id_mahasiswa,
            'tahun' => $validated['tahun'],
            'bentuk_penghargaan' => $validated['bentuk_penghargaan'],
            'pemberi' => $validated['pemberi'],
        ]);

        return redirect()->route('profile')->with('success', 'Penghargaan/Piagam berhasil ditambahkan!');
    }

    /**
     * Update penghargaan piagam data.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:255',
            'bentuk_penghargaan' => 'required|string|max:255',
            'pemberi' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $penghargaanPiagam = PenghargaanPiagam::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        $penghargaanPiagam->update($validated);

        return redirect()->route('profile')->with('success', 'Penghargaan/Piagam berhasil diperbarui!');
    }

    /**
     * Delete penghargaan piagam data.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $penghargaanPiagam = PenghargaanPiagam::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        $penghargaanPiagam->delete();

        return redirect()->route('profile')->with('success', 'Penghargaan/Piagam berhasil dihapus!');
    }

    /**
     * Upload file for penghargaan piagam.
     */
    public function upload(Request $request, $id)
    {
        $validated = $request->validate([
            'keterangan' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $penghargaanPiagam = PenghargaanPiagam::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Upload file
        $file = $request->file('file');
        $filename = 'penghargaan_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('penghargaan-piagam/' . $mahasiswa->id_mahasiswa, $filename, 'public');

        // Update penghargaan piagam with file path
        $penghargaanPiagam->update([
            'file_path' => $path,
            'keterangan_file' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('profile')->with('success', 'File berhasil diupload!');
    }

    /**
     * Download file for penghargaan piagam.
     */
    public function download($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $penghargaanPiagam = PenghargaanPiagam::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        if (!$penghargaanPiagam->file_path || !Storage::disk('public')->exists($penghargaanPiagam->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($penghargaanPiagam->file_path);
    }
}
