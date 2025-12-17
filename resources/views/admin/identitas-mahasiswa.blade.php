@extends('layouts.admin')

@section('title', 'Identitas Mahasiswa')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
    @endphp
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Identitas Mahasiswa</h1>
            <a
                href="{{ route('admin.pengajuan.semua') }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                ← Kembali
            </a>
        </div>

        <!-- Informasi Mahasiswa -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <h1 class="mb-4 text-2xl font-semibold text-[#1b1b18]">IDENTITAS DIRI</h1>
            
            <div class="overflow-hidden rounded-lg border border-[#dbdbd7]">
                @php
                    $tanggalLahirFormatted = '-';
                    if ($mahasiswa->tempat_lahir && $mahasiswa->tanggal_lahir) {
                        $tanggalLahirFormatted = strtoupper($mahasiswa->tempat_lahir) . ', ' . \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d/m/Y');
                    } elseif ($mahasiswa->tempat_lahir) {
                        $tanggalLahirFormatted = strtoupper($mahasiswa->tempat_lahir);
                    } elseif ($mahasiswa->tanggal_lahir) {
                        $tanggalLahirFormatted = \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d/m/Y');
                    }
                    
                    // Format alamat lengkap
                    $alamatParts = [];
                    if ($mahasiswa->alamat_rumah) {
                        $alamatParts[] = $mahasiswa->alamat_rumah;
                    }
                    if ($mahasiswa->rt) {
                        $alamatParts[] = 'RT ' . $mahasiswa->rt;
                    }
                    if ($mahasiswa->rw) {
                        $alamatParts[] = 'RW ' . $mahasiswa->rw;
                    }
                    if ($mahasiswa->kelurahan_desa) {
                        $alamatParts[] = $mahasiswa->kelurahan_desa;
                    }
                    if ($mahasiswa->kecamatan) {
                        $alamatParts[] = $mahasiswa->kecamatan;
                    }
                    if ($mahasiswa->kab_kota) {
                        $alamatParts[] = $mahasiswa->kab_kota;
                    }
                    if ($mahasiswa->provinsi) {
                        $alamatParts[] = $mahasiswa->provinsi;
                    }
                    if ($mahasiswa->kode_pos) {
                        $alamatParts[] = $mahasiswa->kode_pos;
                    }
                    $alamatLengkap = !empty($alamatParts) ? implode(', ', $alamatParts) : '-';
                    
                    $fields = [
                        ['label' => 'Asal Perguruan Tinggi', 'value' => $mahasiswa->asal_perguruan_tinggi ?? '-'],
                        ['label' => 'Nama', 'value' => $mahasiswa->user->name ?? $mahasiswa->nama ?? '-'],
                        ['label' => 'Tempat, Tanggal Lahir', 'value' => $tanggalLahirFormatted],
                        ['label' => 'Jenis Kelamin', 'value' => $mahasiswa->jenis_kelamin ?? '-'],
                        ['label' => 'Status Pernikahan', 'value' => $mahasiswa->status_pernikahan ?? '-'],
                        ['label' => 'Agama', 'value' => $mahasiswa->agama ?? '-'],
                        ['label' => 'Pekerjaan', 'value' => $mahasiswa->pekerjaan ?? '-'],
                        ['label' => 'Alamat Kantor', 'value' => $mahasiswa->alamat_kantor ?? '-'],
                        ['label' => 'Telepon/Fax', 'value' => $mahasiswa->telepon_fax ?? '-'],
                        ['label' => 'Alamat', 'value' => $alamatLengkap],
                        ['label' => 'Telp/HP', 'value' => $mahasiswa->telp_hp ?? '-'],
                    ];
                @endphp

                @foreach ($fields as $index => $field)
                    <div class="flex border-b border-[#dbdbd7] last:border-b-0 {{ $index % 2 === 0 ? 'bg-white' : 'bg-[#f9f9f9]' }}">
                        <div class="w-1/3 border-r border-[#dbdbd7] px-4 py-3 font-medium text-gray-700">
                            {{ $field['label'] }}
                        </div>
                        <div class="flex-1 px-4 py-3 text-gray-900">
                            {{ $field['value'] }}
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Section: Upload Dokumen -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <h1 class="mb-6 text-2xl font-semibold text-[#1b1b18]">DOKUMEN</h1>

            @php
                $documents = [
                    'foto' => [
                        'label' => 'Foto',
                        'status' => $ktpKk && !empty($ktpKk->foto) ? 'Sudah Upload' : 'Belum Upload',
                        'file' => $ktpKk && !empty($ktpKk->foto) ? $ktpKk->foto : null,
                    ],
                    'ktp' => [
                        'label' => 'KTP',
                        'status' => $ktpKk && !empty($ktpKk->ktp) ? 'Sudah Upload' : 'Belum Upload',
                        'file' => $ktpKk && !empty($ktpKk->ktp) ? $ktpKk->ktp : null,
                    ],
                    'kartu_keluarga' => [
                        'label' => 'Kartu Keluarga',
                        'status' => $ktpKk && !empty($ktpKk->kk) ? 'Sudah Upload' : 'Belum Upload',
                        'file' => $ktpKk && !empty($ktpKk->kk) ? $ktpKk->kk : null,
                    ],
                    'akta_lahir' => [
                        'label' => 'Akta Lahir',
                        'status' => $ktpKk && !empty($ktpKk->akta) ? 'Sudah Upload' : 'Belum Upload',
                        'file' => $ktpKk && !empty($ktpKk->akta) ? $ktpKk->akta : null,
                    ],
                ];
            @endphp

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($documents as $key => $doc)
                            <tr class="hover:bg-gray-50">
                                <td class="border-r border-gray-200 px-4 py-3 text-sm font-medium text-gray-900 w-1/3">
                                    {{ $doc['label'] }}
                                </td>
                                <td class="border-r border-gray-200 px-4 py-3 text-sm text-gray-900 w-12">
                                    :
                                </td>
                                <td class="px-4 py-3">
                                    @if ($doc['file'])
                                        <p class="text-sm text-gray-700">
                                            <span class="font-semibold text-green-700">{{ $doc['status'] }}</span>
                                            <br>
                                            <a href="{{ Storage::url($doc['file']) }}" target="_blank" class="text-blue-600 hover:underline">
                                                {{ basename($doc['file']) }}
                                            </a>
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-400">
                                            {{ $doc['status'] }}
                                        </p>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section: Riwayat Pendidikan -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <h1 class="mb-4 text-2xl font-semibold text-[#1b1b18]">RIWAYAT PENDIDIKAN</h1>
            
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">No</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Nama Sekolah</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Tahun Lulus</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Jurusan/Program Studi</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">File</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if($riwayatPendidikan->count() > 0)
                            @foreach ($riwayatPendidikan as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->nama_sekolah }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->tahun_lulus }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->jurusan_program_studi }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">
                                        @if($item->file_path)
                                            <a
                                                href="{{ Storage::url($item->file_path) }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ basename($item->file_path) }}</span>
                                            </a>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada data riwayat pendidikan
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section: Pelatihan Profesional/Sertifikat Kompetensi -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <h1 class="mb-4 text-2xl font-semibold text-[#1b1b18]">PELATIHAN PROFESIONAL/SERTIFIKAT KOMPETENSI</h1>
            
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Tahun</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Jenis Pelatihan (Dalam/Luar Negeri)</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Penyelenggara</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Jangka Waktu</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">File</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if($pelatihanProfesional->count() > 0)
                            @foreach ($pelatihanProfesional as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->tahun }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->jenis_pelatihan }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->penyelenggara }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->jangka_waktu }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">
                                        @if($item->file_path)
                                            <a
                                                href="{{ Storage::url($item->file_path) }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ basename($item->file_path) }}</span>
                                            </a>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada data pelatihan profesional
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section: Konferensi/Seminar/Lokakarya/Simposium -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <h1 class="mb-4 text-2xl font-semibold text-[#1b1b18]">KONFERENSI/SEMINAR/LOKAKARYA/SIMPOSIUM</h1>
            
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Tahun</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Judul Kegiatan</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Penyelenggara</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Panitia/Peserta/Pembicara</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">File</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if($kegiatanIlmiah->count() > 0)
                            @foreach ($kegiatanIlmiah as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->tahun }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->judul_kegiatan }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->penyelenggara }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->peran }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">
                                        @if($item->file_path)
                                            <a
                                                href="{{ Storage::url($item->file_path) }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ basename($item->file_path) }}</span>
                                            </a>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada data kegiatan ilmiah
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section: Penghargaan/Piagam -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <h1 class="mb-4 text-2xl font-semibold text-[#1b1b18]">PENGHARGAAN/PIAGAM</h1>
            
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Tahun</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Bentuk Penghargaan</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Pemberi</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">File</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if($penghargaanPiagam->count() > 0)
                            @foreach ($penghargaanPiagam as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->tahun }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->bentuk_penghargaan }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->pemberi }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">
                                        @if($item->file_path)
                                            <a
                                                href="{{ Storage::url($item->file_path) }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ basename($item->file_path) }}</span>
                                            </a>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="4" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada data penghargaan/piagam
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section: Riwayat Pekerjaan/Pengalaman Kerja -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <h1 class="mb-4 text-2xl font-semibold text-[#1b1b18]">RIWAYAT PEKERJAAN/PENGALAMAN KERJA</h1>
            
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Instansi/Perusahaan</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Periode Kerja</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Posisi/Jabatan</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Uraian Tugas</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">File</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if($riwayatPekerjaan->count() > 0)
                            @foreach ($riwayatPekerjaan as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->instansi_perusahaan }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->periode_kerja }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->posisi_jabatan }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ Str::limit($item->uraian_tugas, 100) }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">
                                        @if($item->file_path)
                                            <a
                                                href="{{ Storage::url($item->file_path) }}"
                                                target="_blank"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline"
                                            >
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                                <span>{{ basename($item->file_path) }}</span>
                                            </a>
                                        @else
                                            <span class="text-gray-500">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada data riwayat pekerjaan
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
