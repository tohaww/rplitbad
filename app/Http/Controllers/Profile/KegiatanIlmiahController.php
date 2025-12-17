<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\KegiatanIlmiah;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class KegiatanIlmiahController extends Controller
{
    /**
     * Display the kegiatan ilmiah form page.
     */
    public function create()
    {
        return view('pages.kegiatan-ilmiah');
    }

    /**
     * Store kegiatan ilmiah data.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:255',
            'judul_kegiatan' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'peran' => 'required|in:Panitia,Peserta,Pembicara',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // Create new kegiatan ilmiah
        KegiatanIlmiah::create([
            'mahasiswa_id' => $mahasiswa->id_mahasiswa,
            'tahun' => $validated['tahun'],
            'judul_kegiatan' => $validated['judul_kegiatan'],
            'penyelenggara' => $validated['penyelenggara'],
            'peran' => $validated['peran'],
        ]);

        return redirect()->route('profile')->with('success', 'Kegiatan ilmiah berhasil ditambahkan!');
    }

    /**
     * Update kegiatan ilmiah data.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:255',
            'judul_kegiatan' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'peran' => 'required|in:Panitia,Peserta,Pembicara',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $kegiatanIlmiah = KegiatanIlmiah::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        $kegiatanIlmiah->update($validated);

        return redirect()->route('profile')->with('success', 'Kegiatan ilmiah berhasil diperbarui!');
    }

    /**
     * Delete kegiatan ilmiah data.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $kegiatanIlmiah = KegiatanIlmiah::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        $kegiatanIlmiah->delete();

        return redirect()->route('profile')->with('success', 'Kegiatan ilmiah berhasil dihapus!');
    }

    /**
     * Upload file for kegiatan ilmiah.
     */
    public function upload(Request $request, $id)
    {
        $validated = $request->validate([
            'keterangan' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $kegiatanIlmiah = KegiatanIlmiah::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Upload file
        $file = $request->file('file');
        $filename = 'kegiatan_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('kegiatan-ilmiah/' . $mahasiswa->id_mahasiswa, $filename, 'public');

        // Update kegiatan ilmiah with file path
        $kegiatanIlmiah->update([
            'file_path' => $path,
            'keterangan_file' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('profile')->with('success', 'File berhasil diupload!');
    }

    /**
     * Download file for kegiatan ilmiah.
     */
    public function download($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $kegiatanIlmiah = KegiatanIlmiah::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        if (!$kegiatanIlmiah->file_path || !Storage::disk('public')->exists($kegiatanIlmiah->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($kegiatanIlmiah->file_path);
    }
}
