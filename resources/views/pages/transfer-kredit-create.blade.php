@extends('layouts.app')

@section('title', 'Pengajuan Transfer Kredit')

@section('content')
    @php
        $user = Auth::user();
        $mahasiswa = \App\Models\Mahasiswa::where('user_id', $user->id)->first();
        $nama = $mahasiswa?->nama ?? $user->name ?? 'Pengguna';
    @endphp

    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#1b1b18]">Pengajuan Transfer Kredit</h1>
                <div class="mt-2 flex items-center gap-2 text-sm text-gray-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Transfer Kredit</span>
                </div>
            </div>
            <div class="text-sm text-gray-600">
                <span class="text-blue-600">Menu</span> / <span class="text-[#1b1b18]">Transfer Kredit</span>
            </div>
        </div>

        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <div class="flex flex-col gap-3">
                <div class="rounded-xl bg-gray-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-widest text-gray-500">Nama</p>
                    <p class="text-base font-semibold text-[#1b1b18]">{{ $nama }}</p>
                </div>
                <div class="rounded-xl bg-gray-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-widest text-gray-500">Tempat, Tgl Lahir</p>
                    <p class="text-base font-semibold text-[#1b1b18]">
                        {{ strtoupper($mahasiswa?->tempat_lahir ?? '-') }},
                        {{ $mahasiswa?->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d-m-Y') : '-' }}
                    </p>
                </div>
                <div class="rounded-xl bg-gray-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-widest text-gray-500">Alamat</p>
                    <p class="text-base font-semibold text-[#1b1b18]">
                        {{ $mahasiswa?->alamat_rumah ?? $mahasiswa?->alamat_korespondensi ?? '-' }}
                    </p>
                </div>
                <div class="rounded-xl bg-gray-50 px-4 py-3">
                    <p class="text-xs uppercase tracking-widest text-gray-500">No HP/No Telp</p>
                    <p class="text-base font-semibold text-[#1b1b18]">
                        {{ $mahasiswa?->telp_hp ?? '-' }}, {{ $mahasiswa?->telepon_fax ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        @if(!isset($lastPengajuan) || !$lastPengajuan || $lastPengajuan->status !== 'Sudah Diajukan')
            <form action="{{ route('transfer-kredit.store') }}" method="POST" class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
                @csrf
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Perguruan Tinggi Asal</label>
                    <input
                        type="text"
                        name="perguruan_tinggi_asal"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan nama perguruan tinggi"
                        value="{{ old('perguruan_tinggi_asal', $lastPengajuan->perguruan_tinggi_asal ?? '') }}"
                        required
                    >
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Jenjang Pendidikan Asal</label>
                    <select
                        name="jenjang_pendidikan"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required
                    >
                        <option value="">Pilih Jenjang Pendidikan</option>
                        @php
                            $selectedJenjang = old('jenjang_pendidikan', $lastPengajuan->jenjang_pendidikan ?? '');
                        @endphp
                        @foreach ($jenjangOptions as $value => $label)
                            <option value="{{ $value }}" @if($selectedJenjang === $value) selected @endif>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Program Studi Asal</label>
                    <input
                        type="text"
                        name="program_studi_asal"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan program studi asal"
                        value="{{ old('program_studi_asal', $lastPengajuan->program_studi_asal ?? '') }}"
                        required
                    >
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">NIM Asal</label>
                    <input
                        type="text"
                        name="nim_asal"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan NIM asal"
                        value="{{ old('nim_asal', $lastPengajuan->nim_asal ?? '') }}"
                        required
                    >
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-gray-700">Program Studi Tertuju</label>
                    <select
                        name="program_studi_tertuju"
                        class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        required
                    >
                        <option value="">Pilih Program Studi</option>
                        @php
                            $selectedProdi = old('program_studi_tertuju', $lastPengajuan->program_studi_tertuju ?? '');
                        @endphp
                        @foreach ($programStudi as $prodi)
                            <option value="{{ $prodi->nama_prodi }}" @if($selectedProdi === $prodi->nama_prodi) selected @endif>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="mt-6 flex justify-end">
                <button type="submit" class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    {{ isset($lastPengajuan) && $lastPengajuan ? 'Ubah' : 'Simpan' }}
                </button>
            </div>
            </form>
        @elseif(isset($lastPengajuan) && $lastPengajuan)
            {{-- Tampilkan informasi pengajuan jika sudah diajukan --}}
            <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
                <div class="mb-4 rounded-lg bg-green-50 px-4 py-3">
                    <p class="text-sm font-semibold text-green-800">Status: {{ $lastPengajuan->status }}</p>
                    <p class="mt-1 text-xs text-green-600">Data penyetaraan yang sudah diajukan tidak dapat diubah.</p>
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Perguruan Tinggi Asal</label>
                        <p class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-sm text-gray-900">{{ $lastPengajuan->perguruan_tinggi_asal }}</p>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Jenjang Pendidikan Asal</label>
                        <p class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-sm text-gray-900">{{ $lastPengajuan->jenjang_pendidikan }}</p>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Program Studi Asal</label>
                        <p class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-sm text-gray-900">{{ $lastPengajuan->program_studi_asal }}</p>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">NIM Asal</label>
                        <p class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-sm text-gray-900">{{ $lastPengajuan->nim_asal }}</p>
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-gray-700">Program Studi Tertuju</label>
                        <p class="rounded-lg border border-gray-300 bg-gray-50 px-4 py-2 text-sm text-gray-900">{{ $lastPengajuan->program_studi_tertuju }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if(isset($lastPengajuan) && $lastPengajuan)
            <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
                <h2 class="mb-4 text-lg font-semibold text-[#1b1b18]">Matakuliah Transfer Kredit</h2>

                {{-- Form input matkul --}}
                @if(isset($lastPengajuan) && $lastPengajuan->status !== 'Sudah Diajukan')
                    <form action="{{ route('transfer-kredit.matkul.store') }}" method="POST" class="mb-6 overflow-x-auto">
                    @csrf
                    <table class="w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="w-[15%] border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Kode Matkul Asal</th>
                                <th class="w-[35%] border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Nama Matakuliah Asal</th>
                                <th class="w-[12%] border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">SKS Asal</th>
                                <th class="w-[18%] border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Nilai Asal</th>
                                <th class="w-[20%] border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">
                                    <input type="text" name="kode_matkul" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <input type="text" name="nama_matakuliah" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <input type="number" name="sks" min="1" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                </td>
                                <td class="border border-gray-300 px-4 py-2">
                                    <select name="nilai" class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500">
                                        <option value="">[--Pilih--]</option>
                                        <option value="A">A</option>
                                        <option value="B">B</option>
                                        <option value="C">C</option>
                                        <option value="D">D</option>
                                        <option value="E">E</option>
                                    </select>
                                </td>
                                <td class="border border-gray-300 px-4 py-2 text-right">
                                    <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        Simpan
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </form>
                @endif

                {{-- Tabel daftar matkul --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">No</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Kode Matkul Asal</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Matakuliah Asal</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Sks Asal</th>
                                <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Nilai Asal</th>
                                @if($lastPengajuan->status !== 'Sudah Diajukan')
                                    <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($matkuls) && $matkuls->count() > 0)
                                @php $no = 1; @endphp
                                @foreach ($matkuls as $matkul)
                                    <tr>
                                        <td class="border border-gray-300 px-4 py-2 text-sm text-gray-900">{{ $no++ }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-sm text-gray-900">{{ $matkul->kode_matkul_asal }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-sm text-gray-900">{{ $matkul->nama_matkul_asal }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-sm text-gray-900">{{ $matkul->sks_asal }}</td>
                                        <td class="border border-gray-300 px-4 py-2 text-sm text-gray-900">{{ $matkul->nilai_asal ?? '-' }}</td>
                                        <td class="border border-gray-300 px-4 py-2">
                                            @if($lastPengajuan->status !== 'Sudah Diajukan')
                                                <form action="{{ route('transfer-kredit.matkul.destroy', $matkul->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-1.5 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-sm text-gray-500">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="{{ $lastPengajuan->status === 'Sudah Diajukan' ? '5' : '6' }}" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                                        Belum ada data matakuliah
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                {{-- Tombol Ajukan Penyetaraan --}}
                @if(isset($matkuls) && $matkuls->count() > 0 && $lastPengajuan->status !== 'Sudah Diajukan')
                    <div class="mt-6 flex justify-center">
                        <button
                            type="button"
                            onclick="openModal()"
                            class="rounded-lg bg-green-600 px-4 py-2 text-xs font-medium text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                        >
                            Ajukan Penyetaraan
                        </button>
                    </div>
                @endif
            </div>
        @endif
    </div>

    {{-- Modal Konfirmasi --}}
    @if(isset($lastPengajuan) && $lastPengajuan)
        <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center">
            <div class="rounded-lg bg-white p-6 shadow-xl max-w-md w-full mx-4 border border-gray-300">
                <h3 class="mb-4 text-lg font-semibold text-gray-900">Konfirmasi Pengajuan</h3>
                <p class="mb-6 text-sm text-gray-700">
                    Apakah anda yakin akan diajukan, Data penyetaraan yang sudah diajukan tidak dapat diubah...?
                </p>
                <div class="flex justify-end gap-3">
                    <button
                        type="button"
                        onclick="closeModal()"
                        class="rounded-lg bg-gray-300 px-4 py-2 text-sm font-medium text-gray-800 transition hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400"
                    >
                        Batal
                    </button>
                    <form action="{{ route('transfer-kredit.submit', $lastPengajuan->id) }}" method="POST" class="inline">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                        >
                            Ya, Ajukan
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <script>
        function openModal() {
            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmModal').classList.add('flex');
        }

        function closeModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            document.getElementById('confirmModal').classList.remove('flex');
        }

        // Tutup modal saat klik di luar modal
        document.getElementById('confirmModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });
    </script>
@endsection

