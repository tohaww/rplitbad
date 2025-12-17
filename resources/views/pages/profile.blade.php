@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    @php
        use Illuminate\Support\Facades\Storage;
        $user = Auth::user();
        $mahasiswa = \App\Models\Mahasiswa::where('user_id', $user->id)->first();
        $riwayatPendidikan = $mahasiswa ? \App\Models\RiwayatPendidikan::where('mahasiswa_id', $mahasiswa->id_mahasiswa)->get() : collect();
        $pelatihanProfesional = $mahasiswa ? \App\Models\PelatihanProfesional::where('mahasiswa_id', $mahasiswa->id_mahasiswa)->get() : collect();
        $kegiatanIlmiah = $mahasiswa ? \App\Models\KegiatanIlmiah::where('mahasiswa_id', $mahasiswa->id_mahasiswa)->get() : collect();
        $penghargaanPiagam = $mahasiswa ? \App\Models\PenghargaanPiagam::where('mahasiswa_id', $mahasiswa->id_mahasiswa)->get() : collect();
        $riwayatPekerjaan = $mahasiswa ? \App\Models\RiwayatPekerjaan::where('mahasiswa_id', $mahasiswa->id_mahasiswa)->get() : collect();
        
        // Ambil data dokumen dari ktp_kk
        $ktpKk = $mahasiswa ? \App\Models\KtpKk::find($mahasiswa->id_mahasiswa) : null;
        
        // Data profil dari database atau default
        // Format alamat lengkap
        $alamatParts = [];
        if ($mahasiswa?->alamat_rumah) {
            $alamatParts[] = $mahasiswa->alamat_rumah;
        }
        if ($mahasiswa?->rt) {
            $alamatParts[] = 'RT ' . $mahasiswa->rt;
        }
        if ($mahasiswa?->rw) {
            $alamatParts[] = 'RW ' . $mahasiswa->rw;
        }
        if ($mahasiswa?->kelurahan_desa) {
            $alamatParts[] = $mahasiswa->kelurahan_desa;
        }
        if ($mahasiswa?->kecamatan) {
            $alamatParts[] = $mahasiswa->kecamatan;
        }
        if ($mahasiswa?->kab_kota) {
            $alamatParts[] = $mahasiswa->kab_kota;
        }
        if ($mahasiswa?->provinsi) {
            $alamatParts[] = $mahasiswa->provinsi;
        }
        if ($mahasiswa?->kode_pos) {
            $alamatParts[] = $mahasiswa->kode_pos;
        }
        $alamatLengkap = !empty($alamatParts) ? implode(', ', $alamatParts) : '-';
        
        $profile = [
            'nama' => $mahasiswa?->nama ?? $user->name ?? '-',
            'tempat_lahir' => $mahasiswa?->tempat_lahir ?? '-',
            'tanggal_lahir' => $mahasiswa?->tanggal_lahir ? $mahasiswa->tanggal_lahir->format('Y-m-d') : '-',
            'jenis_kelamin' => $mahasiswa?->jenis_kelamin ?? '-',
            'status_pernikahan' => $mahasiswa?->status_pernikahan ?? '-',
            'agama' => $mahasiswa?->agama ?? '-',
            'pekerjaan' => $mahasiswa?->pekerjaan ?? '-',
            'alamat_kantor' => $mahasiswa?->alamat_kantor ?? '-',
            'telepon_fax' => $mahasiswa?->telepon_fax ?? '-',
            'alamat' => $alamatLengkap,
            'telp_hp' => $mahasiswa?->telp_hp ?? '-',
        ];
        
        // Cek apakah profil sudah lengkap.
        // Di sini cukup dicek bahwa record mahasiswa sudah ada.
        // (Validasi detail kelengkapan sudah dilakukan di form identitas.)
        $isComplete = (bool) $mahasiswa;

        // Cek apakah dokumen dasar (foto, KTP, KK, akta) sudah lengkap
        $needsDocumentsProfile = false;
        if ($isComplete) {
            $needsDocumentsProfile = ! $ktpKk || ! $ktpKk->foto || ! $ktpKk->ktp || ! $ktpKk->kk || ! $ktpKk->akta;
        }
    @endphp

    <div class="space-y-6">
        @if ($errors->any())
            <div class="rounded-lg border border-red-500/30 bg-red-50 p-4 text-sm text-red-800">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div id="uploadDocumentsSection" class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-[#1b1b18]">IDENTITAS DIRI</h1>
                @if($mahasiswa)
                    <a
                        href="{{ route('identity.create') }}"
                        class="rounded-lg bg-yellow-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
                    >
                        Edit Data Riri
                    </a>
                @endif
            </div>
            
            @if (!$isComplete)
                <div class="mb-6 rounded-lg border border-[#f53003]/20 bg-[#fff2f2]/50 p-4">
                    <p class="text-sm text-[#1b1b18]">
                        Untuk melanjutkan, Anda diwajibkan mengisi identitas diri secara lengkap. silakan klik tombol <strong class="font-semibold">*Lengkapi Identitas Diri*</strong>
                    </p>
                    <a
                        href="{{ route('identity.create') }}"
                        class="mt-4 inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Lengkapi Identitas Diri
                    </a>
                </div>
            @endif

            <div class="overflow-hidden rounded-lg border border-[#dbdbd7]">
                @php
                    $tanggalLahirFormatted = '-';
                    if ($profile['tempat_lahir'] !== '-' && $profile['tanggal_lahir'] !== '-') {
                        $tanggalLahirFormatted = $profile['tempat_lahir'] . ', ' . \Carbon\Carbon::parse($profile['tanggal_lahir'])->format('d/m/Y');
                    } elseif ($profile['tempat_lahir'] !== '-') {
                        $tanggalLahirFormatted = $profile['tempat_lahir'];
                    } elseif ($profile['tanggal_lahir'] !== '-') {
                        $tanggalLahirFormatted = \Carbon\Carbon::parse($profile['tanggal_lahir'])->format('d/m/Y');
                    }
                    
                    $fields = [
                        ['label' => 'Nama', 'value' => $profile['nama']],
                        ['label' => 'Tempat, Tanggal Lahir', 'value' => $tanggalLahirFormatted],
                        ['label' => 'Jenis Kelamin', 'value' => $profile['jenis_kelamin']],
                        ['label' => 'Status Pernikahan', 'value' => $profile['status_pernikahan']],
                        ['label' => 'Agama', 'value' => $profile['agama']],
                        ['label' => 'Pekerjaan', 'value' => $profile['pekerjaan']],
                        ['label' => 'Alamat Kantor', 'value' => $profile['alamat_kantor']],
                        ['label' => 'Telepon/Fax', 'value' => $profile['telepon_fax']],
                        ['label' => 'Alamat', 'value' => $profile['alamat']],
                        ['label' => 'Telp/HP', 'value' => $profile['telp_hp']],
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

        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <h1 class="mb-6 text-2xl font-semibold text-[#1b1b18]">Silakan upload dokumen anda</h1>

            @php
                // Ambil data dokumen dari database ktp_kk
                $documents = [
                    'foto' => [
                        'label' => 'Upload Foto',
                        'status' => $ktpKk && !empty($ktpKk->foto) ? 'Sudah Upload' : 'Belum Upload',
                        'file' => $ktpKk && !empty($ktpKk->foto) ? $ktpKk->foto : null,
                        'format' => 'jpeg, jpg, png',
                        'max_size' => '10MB',
                        'field' => 'foto',
                    ],
                    'ktp' => [
                        'label' => 'Upload KTP',
                        'status' => $ktpKk && !empty($ktpKk->ktp) ? 'Sudah Upload' : 'Belum Upload',
                        'file' => $ktpKk && !empty($ktpKk->ktp) ? $ktpKk->ktp : null,
                        'format' => 'PDF',
                        'max_size' => '10MB',
                        'field' => 'ktp',
                    ],
                    'kartu_keluarga' => [
                        'label' => 'Upload Kartu Keluarga',
                        'status' => $ktpKk && !empty($ktpKk->kk) ? 'Sudah Upload' : 'Belum Upload',
                        'file' => $ktpKk && !empty($ktpKk->kk) ? $ktpKk->kk : null,
                        'format' => 'PDF',
                        'max_size' => '20MB',
                        'field' => 'kartu_keluarga',
                    ],
                    'akta_lahir' => [
                        'label' => 'Upload Akta Lahir',
                        'status' => $ktpKk && !empty($ktpKk->akta) ? 'Sudah Upload' : 'Belum Upload',
                        'file' => $ktpKk && !empty($ktpKk->akta) ? $ktpKk->akta : null,
                        'format' => 'PDF',
                        'max_size' => '10MB',
                        'field' => 'akta_lahir',
                    ],
                ];
            @endphp

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($documents as $key => $doc)
                            <tr class="hover:bg-gray-50">
                                <td class="border-r border-gray-200 px-4 py-3 text-sm font-medium text-gray-900">
                                    {{ $doc['label'] }}
                                </td>
                                <td class="border-r border-gray-200 px-4 py-3 text-sm text-gray-900">
                                    :
                                </td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('documents.upload') }}" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="document_type" value="{{ $doc['field'] }}">
                                        
                                        <div class="flex items-center gap-4">
                                            <div class="w-52 text-xs">
                                                @if ($doc['file'])
                                                    <p class="truncate text-gray-700">
                                                        <span class="font-semibold text-green-700">{{ $doc['status'] }}</span>
                                                        <br>
                                                        <a href="{{ Storage::url($doc['file']) }}" target="_blank" class="text-blue-600 hover:underline">
                                                            {{ basename($doc['file']) }}
                                                        </a>
                                                    </p>
                                                @else
                                                    <p class="text-gray-400">
                                                        {{ $doc['status'] }}
                                                    </p>
                                                @endif
                                            </div>
                                            <div class="flex-1">
                                                <input
                                                    type="file"
                                                    name="document"
                                                    id="file_{{ $doc['field'] }}"
                                                    accept="{{ $doc['format'] === 'PDF' ? '.pdf' : '.jpeg,.jpg,.png' }}"
                                                    class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200"
                                                    required
                                                />
                                            </div>
                                            <button
                                                type="submit"
                                                class="rounded-lg bg-green-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                            >
                                                Upload File
                                            </button>
                                        </div>
                                        
                                        <p class="mt-2 text-xs text-gray-500">
                                            <strong>Format : {{ $doc['format'] }}. Size max : {{ $doc['max_size'] }}</strong>
                                        </p>
                                    </form>
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
            
            <p class="mb-4 text-sm text-gray-700">
                Riwayat pendidikan berisi ijazah, transkrip nilai/daftar nilai, surat pengunduran diri silakan klik tombol <strong>Isi Riwayat Pendidikan</strong>
            </p>

            <div class="mb-4">
                <a
                    href="{{ route('riwayat-pendidikan.create') }}"
                    onclick="return handleProfilAction(event)"
                    class="inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Isi Riwayat Pendidikan
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">No</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Nama Sekolah</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Tahun Lulus</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Jurusan/Program Studi</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">File</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
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
                                                href="{{ route('riwayat-pendidikan.download', $item->id) }}"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline"
                                                target="_blank"
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
                                    <td class="border border-gray-300 px-4 py-3">
                                        <div class="flex gap-2">
                                            <a
                                                href="{{ route('riwayat-pendidikan.create') }}"
                                                class="inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                                            >
                                                Edit
                                            </a>
                                            <form action="{{ route('riwayat-pendidikan.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                            <button
                                                onclick="openUploadModal({{ $item->id }}, '{{ $item->nama_sekolah }}', '{{ $item->tahun_lulus }}')"
                                                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700"
                                            >
                                                Upload File
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
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
            
            <p class="mb-4 text-sm text-gray-700">
                Bagian ini berisi riwayat pelatihan profesional serta sertifikat kompetensi yang telah diperoleh sebagai bentuk pengembangan keahlian dan pengakuan resmi atas kompetensi yang dimiliki. silakan klik tombol <strong>Isi Pelatihan Profesional</strong>
            </p>

            <div class="mb-4">
                <a
                    href="{{ route('pelatihan-profesional.create') }}"
                    onclick="return handleProfilAction(event)"
                    class="inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Isi Pelatihan Profesional
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Tahun</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Jenis Pelatihan (Dalam/Luar Negeri)</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Penyelenggara</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Jangka Waktu</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">File</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
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
                                                href="{{ route('pelatihan-profesional.download', $item->id) }}"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline"
                                                target="_blank"
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
                                    <td class="border border-gray-300 px-4 py-3">
                                        <div class="flex gap-2">
                                            <a
                                                href="{{ route('pelatihan-profesional.create') }}"
                                                class="inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                                            >
                                                Edit
                                            </a>
                                            <form action="{{ route('pelatihan-profesional.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                            <button
                                                onclick="openUploadModalPelatihan({{ $item->id }}, '{{ $item->jenis_pelatihan }}', '{{ $item->tahun }}')"
                                                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700"
                                            >
                                                Upload File
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
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
            
            <p class="mb-4 text-sm text-gray-700">
                Bagian ini memuat riwayat keikutsertaan Anda dalam berbagai kegiatan ilmiah, seperti konferensi, seminar, lokakarya, maupun simposium yang telah diikuti sebagai sarana pengembangan pengetahuan dan jejaring profesional. silakan klik tombol <strong>Isi Konferensi/Seminar/lokakarya/simpsium</strong>
            </p>

            <div class="mb-4">
                <a
                    href="{{ route('kegiatan-ilmiah.create') }}"
                    onclick="return handleProfilAction(event)"
                    class="inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Isi Konferensi/Seminar/Lokakarya/Simposium
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Tahun</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Judul Kegiatan</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Penyelenggaran</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Panitia/Peserta/Pembicara</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">File</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
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
                                                href="{{ route('kegiatan-ilmiah.download', $item->id) }}"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline"
                                                target="_blank"
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
                                    <td class="border border-gray-300 px-4 py-3">
                                        <div class="flex gap-2">
                                            <a
                                                href="{{ route('kegiatan-ilmiah.create') }}"
                                                class="inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                                            >
                                                Edit
                                            </a>
                                            <form action="{{ route('kegiatan-ilmiah.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                            <button
                                                onclick="openUploadModalKegiatan({{ $item->id }}, '{{ $item->judul_kegiatan }}', '{{ $item->tahun }}')"
                                                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700"
                                            >
                                                Upload File
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
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
            
            <p class="mb-4 text-sm text-gray-700">
                Bagian ini berisi daftar penghargaan dan piagam yang telah Anda peroleh sebagai bentuk pengakuan atas prestasi, dedikasi, maupun kontribusi dalam bidang tertentu. silakan klik tombol <strong>Isi Penghargaan/Piagam</strong>
            </p>

            <div class="mb-4">
                <a
                    href="{{ route('penghargaan-piagam.create') }}"
                    onclick="return handleProfilAction(event)"
                    class="inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Isi Penghargaan/Piagam
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Tahun</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Bentuk Penghargaan</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Pemberi</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">File</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
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
                                                href="{{ route('penghargaan-piagam.download', $item->id) }}"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline"
                                                target="_blank"
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
                                    <td class="border border-gray-300 px-4 py-3">
                                        <div class="flex gap-2">
                                            <a
                                                href="{{ route('penghargaan-piagam.create') }}"
                                                class="inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                                            >
                                                Edit
                                            </a>
                                            <form action="{{ route('penghargaan-piagam.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                            <button
                                                onclick="openUploadModalPenghargaan({{ $item->id }}, '{{ $item->bentuk_penghargaan }}', '{{ $item->tahun }}')"
                                                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700"
                                            >
                                                Upload File
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
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
            
            <p class="mb-4 text-sm text-gray-700">
                Bagian ini memuat riwayat pekerjaan atau pengalaman kerja yang pernah Anda jalani, sebagai gambaran perjalanan karier, tanggung jawab yang diemban, serta keahlian yang telah dikembangkan. silakan klik tombol <strong>Isi Pekerjaan/Pengalaman Kerja</strong>
            </p>

            <div class="mb-4">
                <a
                    href="{{ route('riwayat-pekerjaan.create') }}"
                    onclick="return handleProfilAction(event)"
                    class="inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Isi Pekerjaan/Pengalaman Kerja
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Instansi/Perusahaan</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Periode Kerja</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Posisi/Jabatan</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Uraian Tugas</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">File</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
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
                                                href="{{ route('riwayat-pekerjaan.download', $item->id) }}"
                                                class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800 hover:underline"
                                                target="_blank"
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
                                    <td class="border border-gray-300 px-4 py-3">
                                        <div class="flex gap-2">
                                            <a
                                                href="{{ route('riwayat-pekerjaan.create') }}"
                                                class="inline-block rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                                            >
                                                Edit
                                            </a>
                                            <form action="{{ route('riwayat-pekerjaan.destroy', $item->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                                                >
                                                    Hapus
                                                </button>
                                            </form>
                                            <button
                                                onclick="openUploadModalPekerjaan({{ $item->id }}, '{{ $item->instansi_perusahaan }}', '{{ $item->periode_kerja }}')"
                                                class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700"
                                            >
                                                Upload File
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada data riwayat pekerjaan
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal: Wajib Lengkapi Identitas Diri / Upload Dokumen (untuk aksi di menu profil) --}}
    <div
        id="identityProfileModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40"
        aria-hidden="true"
    >
        <div class="mx-4 w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-100">
                    <svg class="h-7 w-7 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M4.93 19.07a10 10 0 1114.14 0A10 10 0 014.93 19.07z" />
                    </svg>
                </div>
                <div>
                    <h3 id="identityProfileModalTitle" class="text-base font-semibold text-[#1b1b18]">Lengkapi Identitas Diri</h3>
                    <p id="identityProfileModalBody" class="mt-1 text-sm text-gray-600">
                        Sebelum mengisi data ini, silakan lengkapi terlebih dahulu identitas diri Anda.
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    onclick="closeIdentityProfileModal()"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300"
                >
                    Nanti Saja
                </button>
                <button
                    type="button"
                    onclick="goToIdentityProfile()"
                    class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Lengkapi Sekarang
                </button>
            </div>
        </div>
    </div>

    {{-- Modal: Identitas Diri Berhasil Disimpan --}}
    @if (session('success'))
        <div
            id="identitySuccessModal"
            class="fixed inset-0 z-50 flex items-center justify-center bg-black/40"
            aria-modal="true"
            role="dialog"
        >
            <div class="mx-4 w-full max-w-md rounded-2xl bg-white p-6 shadow-xl text-center">
                <div class="mb-4 flex justify-center">
                    <div class="flex h-12 w-12 items-center justify-center rounded-full bg-green-100">
                        <svg class="h-7 w-7 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                        </svg>
                    </div>
                </div>
                <h3 class="mb-2 text-base font-semibold text-[#1b1b18]">Berhasil</h3>
                <p class="mb-6 text-sm text-gray-700">
                    {{ session('success') }}
                </p>
                <button
                    type="button"
                    onclick="closeIdentitySuccessModal()"
                    class="inline-flex justify-center rounded-lg bg-green-600 px-5 py-2 text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                >
                    OK
                </button>
            </div>
        </div>
    @endif

    <!-- Modal Upload File -->
    <div id="uploadModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-[#1b1b18]">Upload File Berkas</h3>
                <button
                    type="button"
                    onclick="closeUploadModal()"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="uploadForm" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" id="upload_riwayat_id" name="riwayat_pendidikan_id" value="">

                <div>
                    <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                        Keterangan
                    </label>
                    <input
                        type="text"
                        id="keterangan"
                        name="keterangan"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan keterangan"
                    />
                </div>

                <div>
                    <label for="file_upload" class="block text-sm font-medium text-gray-700 mb-1">
                        Pilih File
                    </label>
                    <input
                        type="file"
                        id="file_upload"
                        name="file"
                        class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200"
                        required
                    />
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        onclick="closeUploadModal()"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Tutup
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Upload File Pelatihan Profesional -->
    <div id="uploadModalPelatihan" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-[#1b1b18]">Upload File Berkas</h3>
                <button
                    type="button"
                    onclick="closeUploadModalPelatihan()"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="uploadFormPelatihan" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" id="upload_pelatihan_id" name="pelatihan_profesional_id" value="">

                <div>
                    <label for="keterangan_pelatihan" class="block text-sm font-medium text-gray-700 mb-1">
                        Keterangan
                    </label>
                    <input
                        type="text"
                        id="keterangan_pelatihan"
                        name="keterangan"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan keterangan"
                    />
                </div>

                <div>
                    <label for="file_upload_pelatihan" class="block text-sm font-medium text-gray-700 mb-1">
                        Pilih File
                    </label>
                    <input
                        type="file"
                        id="file_upload_pelatihan"
                        name="file"
                        class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200"
                        required
                    />
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        onclick="closeUploadModalPelatihan()"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Tutup
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Upload File Kegiatan Ilmiah -->
    <div id="uploadModalKegiatan" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-[#1b1b18]">Upload File Berkas</h3>
                <button
                    type="button"
                    onclick="closeUploadModalKegiatan()"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="uploadFormKegiatan" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" id="upload_kegiatan_id" name="kegiatan_ilmiah_id" value="">

                <div>
                    <label for="keterangan_kegiatan" class="block text-sm font-medium text-gray-700 mb-1">
                        Keterangan
                    </label>
                    <input
                        type="text"
                        id="keterangan_kegiatan"
                        name="keterangan"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan keterangan"
                    />
                </div>

                <div>
                    <label for="file_upload_kegiatan" class="block text-sm font-medium text-gray-700 mb-1">
                        Pilih File
                    </label>
                    <input
                        type="file"
                        id="file_upload_kegiatan"
                        name="file"
                        class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200"
                        required
                    />
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        onclick="closeUploadModalKegiatan()"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Tutup
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Upload File Penghargaan Piagam -->
    <div id="uploadModalPenghargaan" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-[#1b1b18]">Upload File Berkas</h3>
                <button
                    type="button"
                    onclick="closeUploadModalPenghargaan()"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="uploadFormPenghargaan" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" id="upload_penghargaan_id" name="penghargaan_piagam_id" value="">

                <div>
                    <label for="keterangan_penghargaan" class="block text-sm font-medium text-gray-700 mb-1">
                        Keterangan
                    </label>
                    <input
                        type="text"
                        id="keterangan_penghargaan"
                        name="keterangan"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan keterangan"
                    />
                </div>

                <div>
                    <label for="file_upload_penghargaan" class="block text-sm font-medium text-gray-700 mb-1">
                        Pilih File
                    </label>
                    <input
                        type="file"
                        id="file_upload_penghargaan"
                        name="file"
                        class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200"
                        required
                    />
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        onclick="closeUploadModalPenghargaan()"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Tutup
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Upload File Riwayat Pekerjaan -->
    <div id="uploadModalPekerjaan" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
            <div class="mb-4 flex items-center justify-between">
                <h3 class="text-lg font-semibold text-[#1b1b18]">Upload File Berkas</h3>
                <button
                    type="button"
                    onclick="closeUploadModalPekerjaan()"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <form id="uploadFormPekerjaan" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <input type="hidden" id="upload_pekerjaan_id" name="riwayat_pekerjaan_id" value="">

                <div>
                    <label for="keterangan_pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">
                        Keterangan
                    </label>
                    <input
                        type="text"
                        id="keterangan_pekerjaan"
                        name="keterangan"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan keterangan"
                    />
                </div>

                <div>
                    <label for="file_upload_pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">
                        Pilih File
                    </label>
                    <input
                        type="file"
                        id="file_upload_pekerjaan"
                        name="file"
                        class="block w-full text-sm text-gray-600 file:mr-4 file:rounded-lg file:border-0 file:bg-gray-100 file:px-4 file:py-2 file:text-sm file:font-medium file:text-gray-700 hover:file:bg-gray-200"
                        required
                    />
                </div>

                <div class="flex justify-end gap-2">
                    <button
                        type="button"
                        onclick="closeUploadModalPekerjaan()"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Tutup
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const needsIdentityProfile = @json(!$isComplete);
        const needsDocumentsProfile = @json($needsDocumentsProfile ?? false);
        const identityProfileUrl = @json(route('identity.create'));
        const hasIdentitySuccess = @json(session('success') ? true : false);

        function handleProfilAction(event) {
            if (!needsIdentityProfile && !needsDocumentsProfile) {
                return true; // identitas & dokumen lengkap, lanjut ke link
            }
            event.preventDefault();
            openIdentityProfileModal();
            return false;
        }

        function openIdentityProfileModal() {
            const modal = document.getElementById('identityProfileModal');
            const titleEl = document.getElementById('identityProfileModalTitle');
            const bodyEl = document.getElementById('identityProfileModalBody');

            if (needsIdentityProfile) {
                if (titleEl) titleEl.textContent = 'Lengkapi Identitas Diri';
                if (bodyEl) bodyEl.textContent = 'Sebelum mengisi data ini, silakan lengkapi terlebih dahulu identitas diri Anda.';
            } else if (needsDocumentsProfile) {
                if (titleEl) titleEl.textContent = 'Lengkapi Upload Dokumen';
                if (bodyEl) bodyEl.textContent = 'Identitas diri Anda sudah lengkap. Sebelum mengisi data ini, silakan upload dokumen (foto, KTP, KK, akta) terlebih dahulu di bagian "Silakan upload dokumen anda".';
            }

            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeIdentityProfileModal() {
            const modal = document.getElementById('identityProfileModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function goToIdentityProfile() {
            if (needsIdentityProfile) {
                window.location.href = identityProfileUrl;
                return;
            }

            if (needsDocumentsProfile) {
                const section = document.getElementById('uploadDocumentsSection');
                if (section) {
                    section.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
                closeIdentityProfileModal();
            }
        }

        function closeIdentitySuccessModal() {
            const modal = document.getElementById('identitySuccessModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            // Jika tidak ada pesan sukses, pastikan modal tidak tampil.
            if (!hasIdentitySuccess) {
                const modal = document.getElementById('identitySuccessModal');
                if (modal) {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }
            }
        });

        function openUploadModal(id, namaSekolah, tahunLulus) {
            if (needsIdentityProfile) {
                openIdentityProfileModal();
                return;
            }
            document.getElementById('upload_riwayat_id').value = id;
            document.getElementById('keterangan').value = namaSekolah + '(' + tahunLulus + ')';
            document.getElementById('uploadForm').action = '{{ route("riwayat-pendidikan.upload", ":id") }}'.replace(':id', id);
            document.getElementById('uploadModal').classList.remove('hidden');
            document.getElementById('uploadModal').classList.add('flex');
        }

        function closeUploadModal() {
            document.getElementById('uploadModal').classList.add('hidden');
            document.getElementById('uploadModal').classList.remove('flex');
            document.getElementById('uploadForm').reset();
        }

        function openUploadModalPelatihan(id, jenisPelatihan, tahun) {
            if (needsIdentityProfile) {
                openIdentityProfileModal();
                return;
            }
            document.getElementById('upload_pelatihan_id').value = id;
            document.getElementById('keterangan_pelatihan').value = jenisPelatihan + '(' + tahun + ')';
            document.getElementById('uploadFormPelatihan').action = '{{ route("pelatihan-profesional.upload", ":id") }}'.replace(':id', id);
            document.getElementById('uploadModalPelatihan').classList.remove('hidden');
            document.getElementById('uploadModalPelatihan').classList.add('flex');
        }

        function closeUploadModalPelatihan() {
            document.getElementById('uploadModalPelatihan').classList.add('hidden');
            document.getElementById('uploadModalPelatihan').classList.remove('flex');
            document.getElementById('uploadFormPelatihan').reset();
        }

        function openUploadModalKegiatan(id, judulKegiatan, tahun) {
            if (needsIdentityProfile) {
                openIdentityProfileModal();
                return;
            }
            document.getElementById('upload_kegiatan_id').value = id;
            document.getElementById('keterangan_kegiatan').value = judulKegiatan + '(' + tahun + ')';
            document.getElementById('uploadFormKegiatan').action = '{{ route("kegiatan-ilmiah.upload", ":id") }}'.replace(':id', id);
            document.getElementById('uploadModalKegiatan').classList.remove('hidden');
            document.getElementById('uploadModalKegiatan').classList.add('flex');
        }

        function closeUploadModalKegiatan() {
            document.getElementById('uploadModalKegiatan').classList.add('hidden');
            document.getElementById('uploadModalKegiatan').classList.remove('flex');
            document.getElementById('uploadFormKegiatan').reset();
        }

        function openUploadModalPenghargaan(id, bentukPenghargaan, tahun) {
            if (needsIdentityProfile) {
                openIdentityProfileModal();
                return;
            }
            document.getElementById('upload_penghargaan_id').value = id;
            document.getElementById('keterangan_penghargaan').value = bentukPenghargaan + '(' + tahun + ')';
            document.getElementById('uploadFormPenghargaan').action = '{{ route("penghargaan-piagam.upload", ":id") }}'.replace(':id', id);
            document.getElementById('uploadModalPenghargaan').classList.remove('hidden');
            document.getElementById('uploadModalPenghargaan').classList.add('flex');
        }

        function closeUploadModalPenghargaan() {
            document.getElementById('uploadModalPenghargaan').classList.add('hidden');
            document.getElementById('uploadModalPenghargaan').classList.remove('flex');
            document.getElementById('uploadFormPenghargaan').reset();
        }

        function openUploadModalPekerjaan(id, instansiPerusahaan, periodeKerja) {
            if (needsIdentityProfile) {
                openIdentityProfileModal();
                return;
            }
            document.getElementById('upload_pekerjaan_id').value = id;
            document.getElementById('keterangan_pekerjaan').value = instansiPerusahaan + '(' + periodeKerja + ')';
            document.getElementById('uploadFormPekerjaan').action = '{{ route("riwayat-pekerjaan.upload", ":id") }}'.replace(':id', id);
            document.getElementById('uploadModalPekerjaan').classList.remove('hidden');
            document.getElementById('uploadModalPekerjaan').classList.add('flex');
        }

        function closeUploadModalPekerjaan() {
            document.getElementById('uploadModalPekerjaan').classList.add('hidden');
            document.getElementById('uploadModalPekerjaan').classList.remove('flex');
            document.getElementById('uploadFormPekerjaan').reset();
        }
    </script>
@endsection

