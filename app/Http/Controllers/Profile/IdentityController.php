<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\AsalPerguruanTinggi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class IdentityController extends Controller
{
    /**
     * Display the identity form page.
     */
    public function create()
    {
        $user = Auth::user();
        $mahasiswa = Mahasiswa::where('user_id', $user->id)->first();

        // Cek apakah biodata sudah lengkap
        // Biodata dianggap lengkap jika semua field wajib sudah terisi
        $isComplete = false;
        if ($mahasiswa) {
            $requiredFields = [
                'nama', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
                'status_pernikahan', 'agama', 'pekerjaan', 'alamat_kantor',
                'telepon_fax', 'alamat_rumah', 'rt', 'rw', 'kelurahan_desa',
                'kecamatan', 'kab_kota', 'provinsi', 'kode_pos', 'telp_hp',
                'asal_perguruan_tinggi'
            ];
            
            $isComplete = true;
            foreach ($requiredFields as $field) {
                if (empty($mahasiswa->$field)) {
                    $isComplete = false;
                    break;
                }
            }
        }

        // Ambil data asal perguruan tinggi dari database
        $asalPerguruanTinggi = AsalPerguruanTinggi::orderBy('nama', 'asc')->get();

        return view('pages.identity', [
            'mahasiswa' => $mahasiswa,
            'isComplete' => $isComplete,
            'asalPerguruanTinggi' => $asalPerguruanTinggi,
        ]);
    }

    /**
     * Store or update identity data.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status_pernikahan' => 'required|in:Belum Menikah,Menikah,Cerai',
            'agama' => 'required|in:Islam,Kristen,Katolik,Hindu,Buddha,Konghucu',
            'pekerjaan' => 'required|in:Mahasiswa,Pelajar,Karyawan Swasta,Pegawai Negeri Sipil,Wiraswasta,Pengusaha,Guru,Dosen,Dokter,Pengacara,Arsitek,Insinyur,Pensiunan,Tidak Bekerja,Lainnya',
            'alamat_kantor' => 'required|string',
            'telepon_fax' => 'required|string|max:255',
            'alamat_rumah' => 'required|string',
            'rt' => 'required|string|max:10',
            'rw' => 'required|string|max:10',
            'kelurahan_desa' => 'required|string|max:255',
            'kecamatan' => 'required|string|max:255',
            'kab_kota' => 'required|string|max:255',
            'provinsi' => 'required|string|max:255',
            'kode_pos' => 'required|string|max:10',
            'telp_hp' => 'required|string|max:255',
            'asal_perguruan_tinggi' => ['required', 'string', 'max:255', Rule::exists('asal_perguruan_tinggi', 'nama')],
        ]);

        $user = Auth::user();
        
        // Update or create mahasiswa data
        Mahasiswa::updateOrCreate(
            ['user_id' => $user->id],
            array_merge($validated, ['user_id' => $user->id])
        );

        return redirect()->route('profile')->with('success', 'Identitas diri berhasil disimpan!');
    }
}
