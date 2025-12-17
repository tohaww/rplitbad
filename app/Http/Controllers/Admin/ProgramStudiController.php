<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class ProgramStudiController extends Controller
{
    public function index()
    {
        $programStudis = ProgramStudi::orderBy('id', 'asc')->get();

        return view('admin.program-studi', [
            'programStudis' => $programStudis,
        ]);
    }

    public function create()
    {
        return view('admin.program-studi-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string|max:255|unique:program_studis,id',
            'kode_prodi' => 'required|string|max:255|unique:program_studis,kode_prodi',
            'nama_prodi' => 'required|string|max:255',
        ]);

        ProgramStudi::create($validated);

        return redirect()->route('admin.program-studi')->with('success', 'Program studi berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $programStudi = ProgramStudi::findOrFail($id);

        $validated = $request->validate([
            'nama_prodi' => 'required|string|max:255',
        ]);

        $programStudi->update($validated);

        return redirect()->route('admin.program-studi')->with('success', 'Program studi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $programStudi = ProgramStudi::findOrFail($id);
        $programStudi->delete();

        return redirect()->route('admin.program-studi')->with('success', 'Program studi berhasil dihapus!');
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

        $file = $request->file('import_file');
        $extension = strtolower($file->getClientOriginalExtension());
        $delimiter = $request->input('delimiter', ','); // Default koma jika tidak ada
        
        // Validasi extension manual jika validasi Laravel gagal
        if (!in_array($extension, ['csv', 'txt'])) {
            return redirect()->back()->withErrors(['import_file' => 'File harus berformat CSV (.csv). Extension yang ditemukan: ' . $extension]);
        }
        
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
                
                // Baca header dengan delimiter yang dipilih
                $header = fgetcsv($handle, 0, $delimiter);
                
                // Debug: log header untuk troubleshooting
                \Log::info('CSV Import Program Studi - Delimiter: ' . $delimiter);
                \Log::info('CSV Import Program Studi - Header: ' . json_encode($header));
                \Log::info('CSV Import Program Studi - Header count: ' . (is_array($header) ? count($header) : 'null'));
                
                if (!$header || !is_array($header) || count($header) < 3) {
                    fclose($handle);
                    @unlink($tempFile);
                    $headerInfo = is_array($header) && count($header) > 0 ? implode(', ', $header) : 'kosong atau tidak valid';
                    return redirect()->back()->withErrors(['import_file' => 'Format file CSV tidak valid. Pastikan header: ID, Kode Prodi, Nama Prodi. Header yang ditemukan: ' . $headerInfo . '. Pastikan delimiter yang dipilih sesuai dengan file CSV Anda.']);
                }

                $lineNumber = 1;
                while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                    $lineNumber++;
                    
                    // Skip baris kosong
                    if (empty(array_filter($row, function($value) { return trim($value) !== ''; }))) {
                        continue;
                    }
                    
                    if (count($row) < 3) {
                        $errors[] = "Baris {$lineNumber}: Jumlah kolom tidak cukup (ditemukan: " . count($row) . ", diharapkan: minimal 3)";
                        continue;
                    }

                    $data = [
                        'id' => trim($row[0] ?? ''),
                        'kode_prodi' => trim($row[1] ?? ''),
                        'nama_prodi' => trim($row[2] ?? ''),
                    ];

                    $validator = Validator::make($data, [
                        'id' => 'required|string|max:255|unique:program_studis,id',
                        'kode_prodi' => 'required|string|max:255|unique:program_studis,kode_prodi',
                        'nama_prodi' => 'required|string|max:255',
                    ]);

                    if ($validator->fails()) {
                        $errors[] = "Baris dengan ID '{$data['id']}': " . implode(', ', $validator->errors()->all());
                        continue;
                    }

                    ProgramStudi::create($data);
                    $imported++;
                }
                fclose($handle);
                @unlink($tempFile); // Hapus file temporary
            } else {
                // For Excel files, we'll use a simple CSV-like approach
                // For full Excel support, you might want to use PhpSpreadsheet package
                return redirect()->back()->withErrors(['import_file' => 'Format Excel belum didukung. Silakan gunakan format CSV.']);
            }

            if ($imported > 0) {
                $message = "Berhasil mengimpor {$imported} data program studi.";
                if (count($errors) > 0) {
                    $message .= " Terdapat " . count($errors) . " baris yang gagal diimpor.";
                }
                return redirect()->route('admin.program-studi')->with('success', $message);
            } else {
                return redirect()->back()->withErrors(['import_file' => 'Tidak ada data yang berhasil diimpor. Pastikan format file benar.']);
            }
        } catch (\Exception $e) {
            \Log::error('CSV Import Program Studi Error: ' . $e->getMessage());
            \Log::error('CSV Import Program Studi Trace: ' . $e->getTraceAsString());
            \Log::error('CSV Import Program Studi Delimiter: ' . ($delimiter ?? 'not set'));
            
            // Pastikan file temporary dihapus jika ada
            if (isset($tempFile) && file_exists($tempFile)) {
                @unlink($tempFile);
            }
            
            return redirect()->back()->withErrors(['import_file' => 'Terjadi kesalahan saat mengimpor file: ' . $e->getMessage() . '. Pastikan delimiter yang dipilih sesuai dengan format file CSV Anda.']);
        }
    }

    public function downloadTemplate()
    {
        $filename = 'contoh_import_program_studi.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() {
            $file = fopen('php://output', 'w');
            
            // Add BOM for UTF-8
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            // Header dengan delimiter koma
            fputcsv($file, ['ID', 'Kode Prodi', 'Nama Prodi'], ',');
            
            // Contoh data dengan delimiter koma
            fputcsv($file, ['PRODI001', 'TI', 'Teknik Informatika'], ',');
            fputcsv($file, ['PRODI002', 'MTK', 'Matematika'], ',');
            fputcsv($file, ['PRODI003', 'FIS', 'Fisika'], ',');
            
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
