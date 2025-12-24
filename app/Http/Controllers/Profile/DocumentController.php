<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\KtpKk;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DocumentController extends Controller
{
    /**
     * Handle document upload.
     */
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'document_type' => ['required', 'in:foto,ktp,kartu_keluarga,akta_lahir'],
            'document' => ['required', 'file'],
        ]);

        if ($validator->fails()) {
            return redirect()->route('profile')
                ->withErrors($validator)
                ->withInput();
        }

        $documentType = $request->input('document_type');
        $file = $request->file('document');

        // Validasi berdasarkan tipe dokumen
        $rules = [];
        $maxSize = 10240; // 10MB default

        switch ($documentType) {
            case 'foto':
                $rules = ['mimes:jpeg,jpg,png', 'max:5120']; // 5MB
                break;
            case 'ktp':
                $rules = ['mimes:jpeg,jpg,png', 'max:5120']; // 5MB
                break;
            case 'kartu_keluarga':
                $rules = ['mimes:jpeg,jpg,png', 'max:5120']; // 5MB
                break;
            case 'akta_lahir':
                $rules = ['mimes:jpeg,jpg,png', 'max:5120']; // 5MB
                break;
        }

        $fileValidator = Validator::make(['document' => $file], [
            'document' => array_merge(['required', 'file'], $rules),
        ]);

        if ($fileValidator->fails()) {
            return redirect()->route('profile')
                ->withErrors($fileValidator)
                ->withInput();
        }

        // Simpan file
        $user = $request->user();

        // Pastikan data identitas (mahasiswa) sudah ada.
        // Jika belum ada, jangan lempar 404, tapi kembalikan ke profil dengan pesan yang jelas.
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();
        if (! $mahasiswa) {
            return redirect()
                ->route('profile')
                ->withErrors([
                    'document' => 'Silakan lengkapi identitas diri terlebih dahulu sebelum mengupload dokumen.',
                ])
                ->withInput();
        }

        $filename = $documentType . '_' . $mahasiswa->id_mahasiswa . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('documents/' . $mahasiswa->id_mahasiswa, $filename, 'public');

        // Simpan / update record di tabel ktp_kk berdasarkan id_mahasiswa
        $ktpKk = KtpKk::firstOrNew(['id_mahasiswa' => $mahasiswa->id_mahasiswa]);
        $ktpKk->nama_mahasiswa = $mahasiswa->nama ?? $user->name ?? '';

        switch ($documentType) {
            case 'foto':
                $ktpKk->foto = $path;
                break;
            case 'ktp':
                $ktpKk->ktp = $path;
                break;
            case 'kartu_keluarga':
                $ktpKk->kk = $path;
                break;
            case 'akta_lahir':
                $ktpKk->akta = $path;
                break;
        }

        $ktpKk->save();

        return redirect()->route('profile')->with('success', 'Dokumen berhasil diupload!');
    }

    /**
     * Download stored document for current user by type.
     */
    public function download(Request $request, string $type)
    {
        $allowed = ['foto', 'ktp', 'kartu_keluarga', 'akta_lahir'];
        if (!in_array($type, $allowed, true)) {
            abort(404);
        }

        $user = $request->user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->firstOrFail();
        $ktpKk = KtpKk::where('id_mahasiswa', $mahasiswa->id_mahasiswa)->first();

        if (!$ktpKk) {
            abort(404);
        }

        $map = [
            'foto' => $ktpKk->foto,
            'ktp' => $ktpKk->ktp,
            'kartu_keluarga' => $ktpKk->kk,
            'akta_lahir' => $ktpKk->akta,
        ];

        $path = $map[$type] ?? null;
        if (!$path || !Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $mime = Storage::disk('public')->mimeType($path) ?? 'application/octet-stream';
        $absolutePath = Storage::disk('public')->path($path);
        $filename = basename($path);

        return response()->file($absolutePath, [
            'Content-Type' => $mime,
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
