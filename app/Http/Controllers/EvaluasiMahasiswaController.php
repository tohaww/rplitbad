<?php

namespace App\Http\Controllers;

use App\Models\PengajuanPerolehanKredit;
use App\Models\FormEvaluasi;
use App\Models\CourseLearningOutcome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EvaluasiMahasiswaController extends Controller
{
    /**
     * Display the evaluation page.
     */
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Cari data mahasiswa milik user ini
        $mahasiswa = \App\Models\Mahasiswa::where('user_id', $user->id)->first();

        if (! $mahasiswa) {
            // Jika belum ada data mahasiswa, kembalikan tabel kosong
            return view('pages.evaluasi-mahasiswa', [
                'evaluasiData' => [],
            ]);
        }

        // Get semua pengajuan perolehan kredit milik mahasiswa ini
        // dengan status "Sudah Diajukan"
        $pengajuanList = PengajuanPerolehanKredit::where('status', 'Sudah Diajukan')
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->with(['mahasiswa.user', 'programStudi'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Ambil semua data evaluasi yang sudah ada untuk mahasiswa ini
        $existingEvaluasi = FormEvaluasi::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->pluck('id_matkul')
            ->unique()
            ->toArray();

        // Transform data: setiap pengajuan menjadi satu grup,
        // dengan beberapa baris mata kuliah di dalamnya
        $evaluasiData = [];
        $no = 1;

        foreach ($pengajuanList as $pengajuan) {
            $mataKuliah = $pengajuan->mata_kuliah ?? [];

            $courses = [];

            if (empty($mataKuliah)) {
                // Jika belum ada mata kuliah, tetap tampilkan satu baris kosong
                $courses[] = [
                    'notab' => '-',
                    'nama_matakuliah' => '-',
                    'sks' => 0,
                    'status' => 'Belum',
                    'pengajuan_id' => $pengajuan->id,
                    'id_matkul' => null,
                ];
            } else {
                foreach ($mataKuliah as $mk) {
                    $idMatkul = $mk['id_matkul'] ?? null;
                    // Cek apakah sudah ada data evaluasi untuk matkul ini
                    $status = ($idMatkul && in_array($idMatkul, $existingEvaluasi)) ? 'Sudah' : 'Belum';
                    
                    $courses[] = [
                        'notab' => $mk['kode_matkul'] ?? '-',
                        'nama_matakuliah' => $mk['nama_matkul'] ?? '-',
                        'sks' => $this->getSksFromCourse($idMatkul),
                        'status' => $status,
                        'pengajuan_id' => $pengajuan->id,
                        'id_matkul' => $idMatkul,
                    ];
                }
            }

            $evaluasiData[] = [
                'no' => $no++,
                'id' => $pengajuan->no_bukti,
                'tgl' => $pengajuan->created_at,
                'prodi' => $pengajuan->programStudi->nama_prodi ?? '-',
                'nama' => optional($pengajuan->mahasiswa?->user)->name ?? '-',
                'biaya_pendaftaran' => 'Belum',
                'courses' => $courses,
            ];
        }

        return view('pages.evaluasi-mahasiswa', [
            'evaluasiData' => $evaluasiData,
        ]);
    }

    /**
     * Tampilkan form evaluasi diri untuk satu mata kuliah.
     */
    public function showForm($pengajuanId, $matkulId)
    {
        $user = Auth::user();
        $mahasiswa = \App\Models\Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Pastikan pengajuan milik mahasiswa yang login
        $pengajuan = PengajuanPerolehanKredit::where('id', $pengajuanId)
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        $mataKuliah = $pengajuan->mata_kuliah ?? [];
        $selected = collect($mataKuliah)->firstWhere('id_matkul', $matkulId);

        if (! $selected) {
            abort(404);
        }

        // Ambil info matkul dari tabel courses untuk SKS
        $course = \App\Models\Course::find($matkulId);

        // Ambil capaian pembelajaran dari database
        $capaianPembelajaran = CourseLearningOutcome::where('id_matkul', $matkulId)
            ->orderBy('urutan', 'asc')
            ->pluck('capaian_pembelajaran')
            ->toArray();

        // Jika tidak ada capaian pembelajaran di database, gunakan default (fallback)
        if (empty($capaianPembelajaran)) {
            $capaianPembelajaran = [
                'Mampu secara mandiri menjelaskan tentang laporan keuangan ; jenis dan pengguna laporan',
                'Mampu secara mandiri menyusun prosedur dan metode analisis laporan keuangan, analisis rasio keuangan perusahaan dan perbankan, analisis laba kotor, analisis komparatif, analisis modal kerja, analisis biaya-volume-laba, analisis trend dan analisis common size',
                'Mampu secara mandiri menjelaskan konsep analisis laporan keuangan, analisis rasio keuangan perusahaan dan perbankan, analisis laba kotor, analisis komparatif, analisis modal kerja, analisis biaya-volume-laba, analisis trend dan analisis common size',
                'Mampu secara mandiri menjelaskan konsep teori kebangkrutan dari metode Altman, Fulmer , Springate dll',
                'Mampu secara mandiri menjelaskan analisis sumber dan penggunaan modal kerja, kas',
            ];
        }

        // Ambil data evaluasi yang sudah ada
        $existingEvaluasi = FormEvaluasi::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->where('id_matkul', $matkulId)
            ->get()
            ->keyBy('capaian_pembelajaran');

        return view('pages.self-evaluation-form', [
            'pengajuan' => $pengajuan,
            'course' => $course,
            'selectedMatkul' => $selected,
            'existingEvaluasi' => $existingEvaluasi,
            'capaianPembelajaran' => $capaianPembelajaran,
        ]);
    }

    /**
     * Store form evaluasi data.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pengajuan_id' => 'required|exists:pengajuan_perolehan_kredit,id',
            'id_matkul' => 'required|string|exists:courses,id_matkul',
            'evaluasi' => 'required|array',
            'evaluasi.*.capaian_pembelajaran' => 'required|string',
            'evaluasi.*.profisiensi' => 'nullable|string',
            'evaluasi.*.jenis_dokumen' => 'nullable|string|max:255',
            'evaluasi.*.bukti' => 'nullable|string',
        ]);

        $user = Auth::user();
        $mahasiswa = \App\Models\Mahasiswa::where('user_id', $user->id)->firstOrFail();

        // Pastikan pengajuan milik mahasiswa yang login
        $pengajuan = PengajuanPerolehanKredit::where('id', $validated['pengajuan_id'])
            ->where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->firstOrFail();

        // Hapus data evaluasi lama untuk matkul ini (jika ada)
        FormEvaluasi::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
            ->where('id_matkul', $validated['id_matkul'])
            ->delete();

        // Simpan setiap baris evaluasi sebagai record baru
        foreach ($validated['evaluasi'] as $evaluasi) {
            FormEvaluasi::create([
                'mahasiswa_id' => $mahasiswa->id_mahasiswa,
                'id_matkul' => $validated['id_matkul'],
                'capaian_pembelajaran' => $evaluasi['capaian_pembelajaran'],
                'profisiensi' => $evaluasi['profisiensi'] ?? null,
                'jenis_dokumen' => $evaluasi['jenis_dokumen'] ?? null,
                'bukti' => $evaluasi['bukti'] ?? null,
            ]);
        }

        return redirect()->route('evaluasi-mahasiswa')
            ->with('success', 'Form evaluasi berhasil disimpan.');
    }

    /**
     * Get SKS from course.
     */
    private function getSksFromCourse($courseId)
    {
        if (!$courseId) {
            return 0;
        }

        $course = \App\Models\Course::find($courseId);
        return $course->sks ?? 0;
    }
}
