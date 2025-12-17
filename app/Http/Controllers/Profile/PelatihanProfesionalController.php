<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\PelatihanProfesional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PelatihanProfesionalController extends Controller
{
    /**
     * Display the pelatihan profesional form page.
     */
    public function create()
    {
        return view('pages.pelatihan-profesional');
    }

    /**
     * Store pelatihan profesional data.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:255',
            'jenis_pelatihan' => 'required|in:Dalam Negeri,Luar Negeri',
            'penyelenggara' => 'required|string|max:255',
            'jangka_waktu' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        
        // Create new pelatihan profesional
        PelatihanProfesional::create([
            'mahasiswa_id' => $mahasiswa->id_mahasiswa,
            'tahun' => $validated['tahun'],
            'jenis_pelatihan' => $validated['jenis_pelatihan'],
            'penyelenggara' => $validated['penyelenggara'],
            'jangka_waktu' => $validated['jangka_waktu'],
        ]);

        return redirect()->route('profile')->with('success', 'Pelatihan profesional berhasil ditambahkan!');
    }

    /**
     * Update pelatihan profesional data.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'tahun' => 'required|string|max:255',
            'jenis_pelatihan' => 'required|in:Dalam Negeri,Luar Negeri',
            'penyelenggara' => 'required|string|max:255',
            'jangka_waktu' => 'required|string|max:255',
        ]);

        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $pelatihanProfesional = PelatihanProfesional::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        $pelatihanProfesional->update($validated);

        return redirect()->route('profile')->with('success', 'Pelatihan profesional berhasil diperbarui!');
    }

    /**
     * Delete pelatihan profesional data.
     */
    public function destroy($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $pelatihanProfesional = PelatihanProfesional::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        $pelatihanProfesional->delete();

        return redirect()->route('profile')->with('success', 'Pelatihan profesional berhasil dihapus!');
    }

    /**
     * Upload file for pelatihan profesional.
     */
    public function upload(Request $request, $id)
    {
        $validated = $request->validate([
            'keterangan' => 'nullable|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:10240', // 10MB max
        ]);

        $user = Auth::user();
        $pelatihanProfesional = PelatihanProfesional::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        // Upload file
        $file = $request->file('file');
        $filename = 'pelatihan_' . $id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('pelatihan-profesional/' . $mahasiswa->id_mahasiswa, $filename, 'public');

        // Update pelatihan profesional with file path
        $pelatihanProfesional->update([
            'file_path' => $path,
            'keterangan_file' => $validated['keterangan'] ?? null,
        ]);

        return redirect()->route('profile')->with('success', 'File berhasil diupload!');
    }

    /**
     * Download file for pelatihan profesional.
     */
    public function download($id)
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $pelatihanProfesional = PelatihanProfesional::where('id', $id)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        if (!$pelatihanProfesional->file_path || !Storage::disk('public')->exists($pelatihanProfesional->file_path)) {
            abort(404, 'File tidak ditemukan');
        }

        return Storage::disk('public')->download($pelatihanProfesional->file_path);
    }
}
