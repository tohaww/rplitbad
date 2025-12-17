<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLearningOutcome;
use App\Models\FormEvaluasi;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query();

        // Filter by search (ID, Kode Matkul, Nama Matkul)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('id_matkul', 'like', "%{$search}%")
                  ->orWhere('kode_matkul', 'like', "%{$search}%")
                  ->orWhere('nama_matkul', 'like', "%{$search}%");
            });
        }

        // Filter by Prodi
        if ($request->filled('prodi')) {
            $query->where('prodi', $request->input('prodi'));
        }

        // Filter by SKS
        if ($request->filled('sks')) {
            $query->where('sks', $request->input('sks'));
        }

        $courses = $query->orderBy('id_matkul', 'asc')->get();
        $programStudis = ProgramStudi::orderBy('kode_prodi', 'asc')->get();
        
        // Get unique prodi names for filter dropdown
        $prodiList = Course::select('prodi')->distinct()->orderBy('prodi', 'asc')->pluck('prodi');
        $sksList = Course::select('sks')->distinct()->orderBy('sks', 'asc')->pluck('sks');

        return view('admin.courses', [
            'courses' => $courses,
            'programStudis' => $programStudis,
            'prodiList' => $prodiList,
            'sksList' => $sksList,
            'filters' => [
                'search' => $request->input('search', ''),
                'prodi' => $request->input('prodi', ''),
                'sks' => $request->input('sks', ''),
            ],
        ]);
    }

    public function create()
    {
        $programStudis = ProgramStudi::orderBy('kode_prodi', 'asc')->get();
        
        return view('admin.courses-create', [
            'programStudis' => $programStudis,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_matkul' => 'required|string|max:255|unique:courses,id_matkul',
            'kode_matkul' => 'required|string|max:255|unique:courses,kode_matkul',
            'nama_matkul' => 'required|string|max:255',
            'prodi' => 'required|string|max:255', // Ini adalah kode prodi
            'sks' => 'required|integer|min:0|max:10',
            'deskripsi' => 'nullable|string',
        ]);

        // Konversi kode prodi ke nama prodi
        $programStudi = ProgramStudi::where('kode_prodi', $validated['prodi'])->first();
        if (!$programStudi) {
            return redirect()->back()->withErrors(['prodi' => 'Kode prodi tidak ditemukan.'])->withInput();
        }

        $validated['prodi'] = $programStudi->nama_prodi;

        Course::create($validated);

        return redirect()->route('admin.courses')->with('success', 'Mata kuliah berhasil ditambahkan!');
    }

    public function import(Request $request)
    {
        // Validasi manual untuk file
        if (!$request->hasFile('import_file')) {
            return redirect()->back()->withErrors(['import_file' => 'File harus dipilih']);
        }
        
        $file = $request->file('import_file');
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Validasi extension
        if (!in_array($extension, ['csv', 'txt'])) {
            return redirect()->back()->withErrors(['import_file' => 'File harus berformat CSV (.csv). Extension yang ditemukan: ' . $extension]);
        }
        
        // Validasi delimiter
        $request->validate([
            'delimiter' => ['required', 'in:,;'], // Hanya koma atau titik koma
        ], [
            'delimiter.required' => 'Pemisah kolom harus dipilih',
        ]);

        $delimiter = $request->input('delimiter', ','); // Default koma jika tidak ada
        $imported = 0;
        $errors = [];

        try {
            if ($extension === 'csv') {
                // Baca file dan hapus BOM jika ada
                $content = file_get_contents($file->getRealPath());
                // Hapus BOM UTF-8 jika ada
                if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
                    $content = substr($content, 3);
                }
                // Simpan ke file temporary tanpa BOM
                $tempFile = tempnam(sys_get_temp_dir(), 'csv_import_');
                file_put_contents($tempFile, $content);
                
                $handle = fopen($tempFile, 'r');
                
                if (!$handle) {
                    @unlink($tempFile);
                    return redirect()->back()->withErrors(['import_file' => 'Gagal membuka file CSV. Pastikan file tidak rusak.']);
                }
                
                // Baca header dengan delimiter yang dipilih
                $header = fgetcsv($handle, 0, $delimiter);
                
                // Debug: log header untuk troubleshooting
                \Log::info('CSV Import - Delimiter: ' . $delimiter);
                \Log::info('CSV Import - Header: ' . json_encode($header));
                \Log::info('CSV Import - Header count: ' . (is_array($header) ? count($header) : 'null'));
                
                if (!$header || !is_array($header) || count($header) < 5) {
                    fclose($handle);
                    @unlink($tempFile);
                    $headerInfo = is_array($header) && count($header) > 0 ? implode(', ', $header) : 'kosong atau tidak valid';
                    return redirect()->back()->withErrors(['import_file' => 'Format file CSV tidak valid. Pastikan header: ID, Kode Matkul, Nama Matkul, Kode Prodi, SKS, Deskripsi. Header yang ditemukan: ' . $headerInfo . '. Pastikan delimiter yang dipilih sesuai dengan file CSV Anda.']);
                }

                $lineNumber = 1;
                while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                    $lineNumber++;
                    
                    // Skip baris kosong
                    if (empty(array_filter($row, function($value) { return trim($value) !== ''; }))) {
                        continue;
                    }
                    
                    if (count($row) < 5) {
                        $errors[] = "Baris {$lineNumber}: Jumlah kolom tidak cukup (ditemukan: " . count($row) . ", diharapkan: minimal 5)";
                        continue;
                    }

                    $kodeProdi = trim($row[3] ?? '');
                    
                    // Konversi kode prodi ke nama prodi
                    $programStudi = ProgramStudi::where('kode_prodi', $kodeProdi)->first();
                    if (!$programStudi) {
                        $errors[] = "Baris dengan ID '{$row[0]}': Kode prodi '{$kodeProdi}' tidak ditemukan.";
                        continue;
                    }

                    $data = [
                        'id_matkul' => trim($row[0] ?? ''),
                        'kode_matkul' => trim($row[1] ?? ''),
                        'nama_matkul' => trim($row[2] ?? ''),
                        'prodi' => $programStudi->nama_prodi, // Gunakan nama prodi
                        'sks' => (int)trim($row[4] ?? 0),
                        'deskripsi' => trim($row[5] ?? '') ?: null,
                    ];

                    $validator = Validator::make($data, [
                        'id_matkul' => 'required|string|max:255|unique:courses,id_matkul',
                        'kode_matkul' => 'required|string|max:255|unique:courses,kode_matkul',
                        'nama_matkul' => 'required|string|max:255',
                        'prodi' => 'required|string|max:255',
                        'sks' => 'required|integer|min:0|max:10',
                        'deskripsi' => 'nullable|string',
                    ]);

                    if ($validator->fails()) {
                        $errors[] = "Baris dengan ID '{$data['id_matkul']}': " . implode(', ', $validator->errors()->all());
                        continue;
                    }

                    Course::create($data);
                    $imported++;
                }
                fclose($handle);
                @unlink($tempFile); // Hapus file temporary (gunakan @ untuk suppress error jika file sudah dihapus)
            } else {
                // For Excel files, we'll use a simple CSV-like approach
                // For full Excel support, you might want to use PhpSpreadsheet package
                return redirect()->back()->withErrors(['import_file' => 'Format Excel belum didukung. Silakan gunakan format CSV.']);
            }

            if ($imported > 0) {
                $message = "Berhasil mengimpor {$imported} data mata kuliah.";
                if (count($errors) > 0) {
                    $message .= " Terdapat " . count($errors) . " baris yang gagal diimpor.";
                }
                return redirect()->route('admin.courses')->with('success', $message);
            } else {
                $errorMessage = 'Tidak ada data yang berhasil diimpor.';
                if (count($errors) > 0) {
                    $errorMessage .= ' Kesalahan: ' . implode('; ', array_slice($errors, 0, 5));
                    if (count($errors) > 5) {
                        $errorMessage .= ' (dan ' . (count($errors) - 5) . ' kesalahan lainnya)';
                    }
                } else {
                    $errorMessage .= ' Pastikan format file benar dan delimiter sesuai dengan pilihan Anda.';
                }
                return redirect()->back()->withErrors(['import_file' => $errorMessage]);
            }
        } catch (\Exception $e) {
            \Log::error('CSV Import Error: ' . $e->getMessage());
            \Log::error('CSV Import Trace: ' . $e->getTraceAsString());
            \Log::error('CSV Import Delimiter: ' . ($delimiter ?? 'not set'));
            
            // Pastikan file temporary dihapus jika ada
            if (isset($tempFile) && file_exists($tempFile)) {
                @unlink($tempFile);
            }
            
            return redirect()->back()->withErrors(['import_file' => 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage() . '. Pastikan delimiter yang dipilih sesuai dengan format file CSV Anda.']);
        }
    }

    public function downloadTemplate()
    {
        $filename = 'contoh_import_mata_kuliah.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header dengan delimiter koma
            fputcsv($file, ['ID Matkul', 'Kode Matkul', 'Nama Matkul', 'Kode Prodi', 'SKS', 'Deskripsi'], ',');
            
            // Ambil contoh kode prodi dari database
            $programStudis = ProgramStudi::orderBy('kode_prodi', 'asc')->limit(3)->get();
            $kodeProdi1 = $programStudis->count() > 0 ? $programStudis[0]->kode_prodi : 'TI';
            $kodeProdi2 = $programStudis->count() > 1 ? $programStudis[1]->kode_prodi : 'TI';
            $kodeProdi3 = $programStudis->count() > 2 ? $programStudis[2]->kode_prodi : 'TI';
            
            // Contoh data dengan delimiter koma (menggunakan kode prodi)
            fputcsv($file, ['MK001', 'MK001', 'Pemrograman Web', $kodeProdi1, '3', 'Mata kuliah yang mempelajari tentang pemrograman web'], ',');
            fputcsv($file, ['MK002', 'MK002', 'Basis Data', $kodeProdi2, '3', 'Mata kuliah yang mempelajari tentang basis data'], ',');
            fputcsv($file, ['MK003', 'MK003', 'Algoritma dan Struktur Data', $kodeProdi3, '4', 'Mata kuliah yang mempelajari tentang algoritma dan struktur data'], ',');
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function update(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $validated = $request->validate([
            'nama_matkul' => 'required|string|max:255',
            'prodi' => 'required|string|max:255', // Ini adalah kode prodi
            'sks' => 'required|integer|min:0|max:10',
            'deskripsi' => 'nullable|string',
        ]);

        // Konversi kode prodi ke nama prodi
        $programStudi = ProgramStudi::where('kode_prodi', $validated['prodi'])->first();
        if (!$programStudi) {
            return redirect()->back()->withErrors(['prodi' => 'Kode prodi tidak ditemukan.'])->withInput();
        }

        $validated['prodi'] = $programStudi->nama_prodi;

        $course->update($validated);

        return redirect()->route('admin.courses')->with('success', 'Mata kuliah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $course = Course::findOrFail($id);
        $course->delete();

        return redirect()->route('admin.courses')->with('success', 'Mata kuliah berhasil dihapus!');
    }

    public function uploadPointPertanyaan(Request $request)
    {
        // Validasi manual untuk file
        if (!$request->hasFile('point_import_file')) {
            return redirect()->back()->withErrors(['point_import_file' => 'File harus dipilih']);
        }
        
        $file = $request->file('point_import_file');
        $extension = strtolower($file->getClientOriginalExtension());
        
        // Validasi extension
        if (!in_array($extension, ['csv', 'txt'])) {
            return redirect()->back()->withErrors(['point_import_file' => 'File harus berformat CSV (.csv). Extension yang ditemukan: ' . $extension]);
        }
        
        // Validasi delimiter
        $request->validate([
            'point_delimiter' => ['required', 'in:,;'], // Hanya koma atau titik koma
        ], [
            'point_delimiter.required' => 'Pemisah kolom harus dipilih',
        ]);

        $delimiter = $request->input('point_delimiter', ',');
        $imported = 0;
        $errors = [];

        try {
            // Baca file dan hapus BOM jika ada
            $content = file_get_contents($file->getRealPath());
            // Hapus BOM UTF-8 jika ada
            if (substr($content, 0, 3) === "\xEF\xBB\xBF") {
                $content = substr($content, 3);
            }
            // Simpan ke file temporary tanpa BOM
            $tempFile = tempnam(sys_get_temp_dir(), 'csv_point_import_');
            file_put_contents($tempFile, $content);
            
            $handle = fopen($tempFile, 'r');
            
            if (!$handle) {
                @unlink($tempFile);
                return redirect()->back()->withErrors(['point_import_file' => 'Gagal membuka file CSV. Pastikan file tidak rusak.']);
            }
            
            // Baca header dengan delimiter yang dipilih
            $header = fgetcsv($handle, 0, $delimiter);
            
            if (!$header || !is_array($header) || count($header) < 2) {
                fclose($handle);
                @unlink($tempFile);
                $headerInfo = is_array($header) && count($header) > 0 ? implode(', ', $header) : 'kosong atau tidak valid';
                return redirect()->back()->withErrors(['point_import_file' => 'Format file CSV tidak valid. Pastikan header: ID Matkul, Capaian Pembelajaran. Header yang ditemukan: ' . $headerInfo . '. Pastikan delimiter yang dipilih sesuai dengan file CSV Anda.']);
            }

            $lineNumber = 1;
            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $lineNumber++;
                
                // Skip baris kosong
                if (empty(array_filter($row, function($value) { return trim($value) !== ''; }))) {
                    continue;
                }
                
                if (count($row) < 2) {
                    $errors[] = "Baris {$lineNumber}: Jumlah kolom tidak cukup (ditemukan: " . count($row) . ", diharapkan: minimal 2)";
                    continue;
                }

                $idMatkul = trim($row[0] ?? '');
                $capaianPembelajaran = trim($row[1] ?? '');

                if (empty($idMatkul)) {
                    $errors[] = "Baris {$lineNumber}: ID Matkul tidak boleh kosong";
                    continue;
                }

                if (empty($capaianPembelajaran)) {
                    $errors[] = "Baris {$lineNumber}: Capaian Pembelajaran tidak boleh kosong";
                    continue;
                }

                // Cek apakah mata kuliah sudah ada (opsional, bisa juga untuk mata kuliah yang akan dibuat)
                // Untuk fleksibilitas, kita tetap simpan meskipun mata kuliah belum ada
                // Mata kuliah bisa dibuat setelahnya

                // Hitung urutan untuk ID matkul yang sama
                $urutan = CourseLearningOutcome::where('id_matkul', $idMatkul)->max('urutan') ?? 0;
                $urutan++;

                try {
                    CourseLearningOutcome::create([
                        'id_matkul' => $idMatkul,
                        'capaian_pembelajaran' => $capaianPembelajaran,
                        'urutan' => $urutan,
                    ]);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "Baris {$lineNumber}: " . $e->getMessage();
                }
            }
            fclose($handle);
            @unlink($tempFile);

            if ($imported > 0) {
                $message = "Berhasil mengimpor {$imported} point pertanyaan.";
                if (count($errors) > 0) {
                    $message .= " Terdapat " . count($errors) . " baris yang gagal diimpor.";
                }
                return redirect()->route('admin.courses.create')->with('success', $message);
            } else {
                $errorMessage = 'Tidak ada data yang berhasil diimpor.';
                if (count($errors) > 0) {
                    $errorMessage .= ' Kesalahan: ' . implode('; ', array_slice($errors, 0, 5));
                    if (count($errors) > 5) {
                        $errorMessage .= ' (dan ' . (count($errors) - 5) . ' kesalahan lainnya)';
                    }
                } else {
                    $errorMessage .= ' Pastikan format file benar dan delimiter sesuai dengan pilihan Anda.';
                }
                return redirect()->back()->withErrors(['point_import_file' => $errorMessage]);
            }
        } catch (\Exception $e) {
            \Log::error('CSV Point Import Error: ' . $e->getMessage());
            \Log::error('CSV Point Import Trace: ' . $e->getTraceAsString());
            
            // Pastikan file temporary dihapus jika ada
            if (isset($tempFile) && file_exists($tempFile)) {
                @unlink($tempFile);
            }
            
            return redirect()->back()->withErrors(['point_import_file' => 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage() . '. Pastikan delimiter yang dipilih sesuai dengan format file CSV Anda.']);
        }
    }

    public function downloadPointTemplate()
    {
        $filename = 'contoh_import_point_pertanyaan.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header dengan delimiter koma
            fputcsv($file, ['ID Matkul', 'Capaian Pembelajaran'], ',');
            
            // Contoh data dengan delimiter koma
            fputcsv($file, ['MK001', 'Mahasiswa mampu memahami dan menggunakan kosakata serta struktur kalimat Bahasa Inggris dalam konteks bisnis.'], ',');
            fputcsv($file, ['MK001', 'Mahasiswa mampu melakukan presentasi bisnis sederhana dalam Bahasa Inggris dengan struktur dan bahasa yang sesuai.'], ',');
            fputcsv($file, ['MK001', 'Mahasiswa mampu melakukan percakapan bisnis dalam situasi seperti negosiasi, rapat, atau wawancara kerja dengan bahasa yang komunikatif dan profesional'], ',');
            fputcsv($file, ['MK001', 'Mahasiswa mampu menggunakan media digital untuk mendukung komunikasi bisnis dalam Bahasa Inggris, seperti email, video conference, dan presentasi daring'], ',');
            fputcsv($file, ['MK002', 'Mahasiswa mampu memahami konsep dasar pemrograman web'], ',');
            fputcsv($file, ['MK002', 'Mahasiswa mampu membuat aplikasi web sederhana menggunakan HTML, CSS, dan JavaScript'], ',');
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function showEvaluasi($id)
    {
        $course = Course::findOrFail($id);
        
        // Ambil semua form evaluasi untuk mata kuliah ini
        $formEvaluasi = FormEvaluasi::where('id_matkul', $id)
            ->with(['mahasiswa.user'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('mahasiswa_id');
        
        // Ambil capaian pembelajaran untuk mata kuliah ini
        $capaianPembelajaran = CourseLearningOutcome::where('id_matkul', $id)
            ->orderBy('urutan', 'asc')
            ->get();

        return view('admin.courses-evaluasi', [
            'course' => $course,
            'formEvaluasi' => $formEvaluasi,
            'capaianPembelajaran' => $capaianPembelajaran,
        ]);
    }

    public function updateLearningOutcome(Request $request, $id)
    {
        $learningOutcome = CourseLearningOutcome::findOrFail($id);

        $validated = $request->validate([
            'capaian_pembelajaran' => 'required|string',
            'urutan' => 'required|integer|min:1',
        ]);

        $learningOutcome->update($validated);

        return redirect()->route('admin.courses.evaluasi', $learningOutcome->id_matkul)
            ->with('success', 'Capaian pembelajaran berhasil diperbarui!');
    }

    public function destroyLearningOutcome($id)
    {
        $learningOutcome = CourseLearningOutcome::findOrFail($id);
        $idMatkul = $learningOutcome->id_matkul;
        
        $learningOutcome->delete();

        return redirect()->route('admin.courses.evaluasi', $idMatkul)
            ->with('success', 'Capaian pembelajaran berhasil dihapus!');
    }
}
