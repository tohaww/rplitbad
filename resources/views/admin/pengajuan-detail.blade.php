@extends('layouts.admin')

@section('title', 'Detail Pengajuan Mahasiswa')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Detail Pengajuan Mahasiswa</h1>
            <a
                href="{{ route('admin.pengajuan.semua') }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                ← Kembali
            </a>
        </div>

        @php
            $statusAktif = $pengajuanAktif->status ?? '';
            $step1Completed = true; // entri
            $step2Completed = $statusAktif !== 'Draft'; // ajukan
            $step3Completed = in_array($statusAktif, ['Sudah Diajukan', 'Assesment', 'Verifikasi', 'Disetujui', 'Diterima', 'Ditolak']);
            $step4Completed = in_array($statusAktif, ['Assesment', 'Verifikasi', 'Disetujui', 'Diterima', 'Ditolak']);
            $step5Completed = in_array($statusAktif, ['Verifikasi', 'Disetujui', 'Diterima']);
            $step6Completed = in_array($statusAktif, ['Disetujui', 'Diterima']);
            $progressPercentage = collect([$step1Completed, $step2Completed, $step3Completed, $step4Completed, $step5Completed, $step6Completed])->filter()->count() / 6 * 100;
        @endphp

        <!-- Progress Pengajuan -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Progress Pengajuan</h2>
            <div class="mb-4 flex flex-wrap gap-2 text-xs">
                <div class="rounded-lg px-3 py-1.5 {{ $step1Completed ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Entri RPL (Mhs)</div>
                <div class="rounded-lg px-3 py-1.5 {{ $step2Completed ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Ajukan RPL (Mhs)</div>
                <div class="rounded-lg px-3 py-1.5 {{ $step3Completed ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Form Evaluasi (Mhs)</div>
                <div class="rounded-lg px-3 py-1.5 {{ $step4Completed ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Form Assessment (Asesor)</div>
                <div class="rounded-lg px-3 py-1.5 {{ $step5Completed ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Verifikasi Nilai (Prodi)</div>
                <div class="rounded-lg px-3 py-1.5 {{ $step6Completed ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Finish</div>
            </div>
            <div class="h-2 w-full rounded-full bg-gray-200">
                <div class="h-2 rounded-full bg-green-500" style="width: {{ $progressPercentage }}%"></div>
            </div>
        </div>

        <!-- Informasi Mahasiswa -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Informasi Mahasiswa</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-sm font-medium text-gray-500">Nama</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $mahasiswa->user->name ?? $mahasiswa->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Tempat, Tanggal Lahir</p>
                    <p class="mt-1 text-sm text-gray-900">
                        {{ strtoupper($mahasiswa->tempat_lahir ?? '-') }},
                        {{ $mahasiswa->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d-m-Y') : '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">Alamat</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $mahasiswa->alamat_rumah ?? $mahasiswa->alamat_korespondensi ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500">No. HP/Telepon</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $mahasiswa->telp_hp ?? '-' }}, {{ $mahasiswa->telepon_fax ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Pengajuan Perolehan Kredit -->
        @if($jenisPengajuanAktif !== 'Transfer Kredit' || ($dariSemuaPengajuan ?? false))
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Pengajuan Perolehan Kredit</h2>
                </div>
                <div class="overflow-x-auto">
                    @forelse($semuaPerolehanKredit as $index => $pengajuan)
                        <div class="border-b border-gray-200 px-6 py-4 {{ $pengajuan->id === $pengajuanAktif->id && $jenisPengajuanAktif === 'Perolehan Kredit' ? 'bg-blue-50' : '' }}">
                            <div class="mb-4 flex items-center justify-between">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">
                                        Pengajuan #{{ $index + 1 }}
                                        @if($pengajuan->id === $pengajuanAktif->id && $jenisPengajuanAktif === 'Perolehan Kredit')
                                            <span class="ml-2 text-xs text-blue-600">(Sedang dilihat)</span>
                                        @endif
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">No. Bukti: {{ $pengajuan->no_bukti ?? '-' }}</p>
                                </div>
                                <div class="text-right">
                                    @if ($pengajuan->status === 'Diterima' || $pengajuan->status === 'Disetujui')
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                            {{ $pengajuan->status }}
                                        </span>
                                    @elseif ($pengajuan->status === 'Ditolak')
                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">
                                            {{ $pengajuan->status }}
                                        </span>
                                    @elseif ($pengajuan->status === 'Sudah Diajukan')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">
                                            {{ $pengajuan->status }}
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800">
                                            {{ $pengajuan->status }}
                                        </span>
                                    @endif
                                    <p class="mt-1 text-xs text-gray-500">{{ $pengajuan->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Program Studi</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->programStudi->nama_prodi ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Total SKS</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->total_sks ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Tanggal Pengajuan</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->tanggal ? \Carbon\Carbon::parse($pengajuan->tanggal)->format('d/m/Y') : '-' }}</p>
                                </div>
                            </div>
                            @if($pengajuan->mata_kuliah && count($pengajuan->mata_kuliah) > 0)
                                <div class="mt-4">
                                    <p class="mb-2 text-xs font-medium text-gray-500">Mata Kuliah:</p>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">No</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Kode Matkul</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama Matkul</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">SKS</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @foreach($pengajuan->mata_kuliah as $idx => $mk)
                                                    @php
                                                        $idMatkul = $mk['id_matkul'] ?? $mk['course_id'] ?? null;
                                                        $course = $idMatkul ? \App\Models\Course::find($idMatkul) : null;
                                                        $sks = $course->sks ?? 0;
                                                    @endphp
                                                    <tr>
                                                        <td class="whitespace-nowrap px-3 py-2 text-xs text-gray-900">{{ $idx + 1 }}</td>
                                                        <td class="whitespace-nowrap px-3 py-2 text-xs text-gray-500">{{ $mk['kode_matkul'] ?? '-' }}</td>
                                                        <td class="px-3 py-2 text-xs text-gray-500">{{ $mk['nama_matkul'] ?? $mk['course_id'] ?? '-' }}</td>
                                                        <td class="whitespace-nowrap px-3 py-2 text-xs text-gray-500">{{ $sks }}</td>
                                                        <td class="whitespace-nowrap px-3 py-2 text-xs">
                                                            @if($idMatkul)
                                                                <a
                                                                    href="{{ route('admin.pengajuan.evaluasi', ['pengajuanId' => $pengajuan->id, 'matkulId' => $idMatkul]) }}"
                                                                    class="text-blue-600 hover:text-blue-900 hover:underline"
                                                                >
                                                                    View Evaluasi
                                                                </a>
                                                            @else
                                                                <span class="text-gray-400">-</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-gray-500">
                            Tidak ada pengajuan perolehan kredit
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif

        <!-- Pengajuan Transfer Kredit -->
        @if($jenisPengajuanAktif !== 'Perolehan Kredit' || ($dariSemuaPengajuan ?? false))
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
                <div class="border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Pengajuan Transfer Kredit</h2>
                </div>
                <div class="overflow-x-auto">
                    @forelse($semuaTransferKredit as $index => $pengajuan)
                        <div class="border-b border-gray-200 px-6 py-4 {{ $pengajuan->id === $pengajuanAktif->id && $jenisPengajuanAktif === 'Transfer Kredit' ? 'bg-blue-50' : '' }}">
                            <div class="mb-4 flex items-center justify-between">
                                <div>
                                    <h3 class="text-base font-semibold text-gray-900">
                                        Pengajuan #{{ $index + 1 }}
                                        @if($pengajuan->id === $pengajuanAktif->id && $jenisPengajuanAktif === 'Transfer Kredit')
                                            <span class="ml-2 text-xs text-blue-600">(Sedang dilihat)</span>
                                        @endif
                                    </h3>
                                </div>
                                <div class="text-right">
                                    @if ($pengajuan->status === 'Diterima' || $pengajuan->status === 'Disetujui')
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                            {{ $pengajuan->status }}
                                        </span>
                                    @elseif ($pengajuan->status === 'Ditolak')
                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">
                                            {{ $pengajuan->status }}
                                        </span>
                                    @elseif ($pengajuan->status === 'Sudah Diajukan')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">
                                            {{ $pengajuan->status }}
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800">
                                            {{ $pengajuan->status }}
                                        </span>
                                    @endif
                                    <p class="mt-1 text-xs text-gray-500">{{ $pengajuan->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Perguruan Tinggi Asal</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->perguruan_tinggi_asal ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Jenjang Pendidikan</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->jenjang_pendidikan ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Program Studi Asal</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->program_studi_asal ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">NIM Asal</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->nim_asal ?? '-' }}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">Program Studi Tertuju</p>
                                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->program_studi_tertuju ?? '-' }}</p>
                                </div>
                                <div>
                                </div>
                            </div>
                            @if($pengajuan->matkuls && $pengajuan->matkuls->count() > 0)
                                <div class="mt-4">
                                    <p class="mb-2 text-xs font-medium text-gray-500">Mata Kuliah Transfer:</p>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">No</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Kode Matkul Asal</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama Matkul Asal</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">SKS Asal</th>
                                                    <th class="px-3 py-2 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nilai Asal</th>
                                                </tr>
                                            </thead>
                                            <tbody class="divide-y divide-gray-200 bg-white">
                                                @foreach($pengajuan->matkuls as $idx => $matkul)
                                                    <tr>
                                                        <td class="whitespace-nowrap px-3 py-2 text-xs text-gray-900">{{ $idx + 1 }}</td>
                                                        <td class="whitespace-nowrap px-3 py-2 text-xs text-gray-500">{{ $matkul->kode_matkul_asal ?? '-' }}</td>
                                                        <td class="px-3 py-2 text-xs text-gray-500">{{ $matkul->nama_matkul_asal ?? '-' }}</td>
                                                        <td class="whitespace-nowrap px-3 py-2 text-xs text-gray-500">{{ $matkul->sks_asal ?? '-' }}</td>
                                                        <td class="whitespace-nowrap px-3 py-2 text-xs text-gray-500">{{ $matkul->nilai_asal ?? '-' }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="px-6 py-8 text-center text-sm text-gray-500">
                            Tidak ada pengajuan transfer kredit
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        @endif
    </div>
@endsection

