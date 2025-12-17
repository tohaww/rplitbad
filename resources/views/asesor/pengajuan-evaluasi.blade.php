@extends('layouts.app')

@section('title', 'Evaluasi Mahasiswa - ' . ($course->nama_matkul ?? ''))

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Evaluasi Mahasiswa</h1>
                <p class="mt-1 text-sm text-gray-600">
                    {{ $course->nama_matkul ?? '-' }} ({{ $course->kode_matkul ?? '-' }})
                </p>
            </div>
            <a
                href="{{ route('asesor.pengajuan.detail', ['jenis' => 'perolehan-kredit', 'id' => $pengajuan->id]) }}"
                class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                ← Kembali
            </a>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-xs font-medium text-gray-500">Nama Mahasiswa</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->mahasiswa->user->name ?? $pengajuan->mahasiswa->nama ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Program Studi</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->programStudi->nama_prodi ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">No. Bukti</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->no_bukti ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Tanggal Pengajuan</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $pengajuan->created_at?->format('d/m/Y H:i') ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <p class="text-xs font-medium text-gray-500">Kode Matkul</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $course->kode_matkul ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">Nama Matkul</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $course->nama_matkul ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium text-gray-500">SKS</p>
                    <p class="mt-1 text-sm text-gray-900">{{ $course->sks ?? '-' }}</p>
                </div>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Form Evaluasi Diri Mahasiswa</h2>
            </div>
            <div class="overflow-x-auto">
                @if($evaluasi->count() > 0)
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">No</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Kemampuan Akhir Yang Diharapkan/<br>
                                    Capaian Pembelajaran Mata Kuliah
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Profisiensi pengetahuan dan<br>
                                    keterampilan saat ini
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Jenis Dokumen</th>
                                <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                    Bukti yang<br>disampaikan
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($evaluasi as $index => $item)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900">{{ $item->capaian_pembelajaran ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->profisiensi ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->jenis_dokumen ?? '-' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $item->bukti ?? '-' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-6 py-8 text-center text-sm text-gray-500">
                        Mahasiswa belum mengisi form evaluasi untuk mata kuliah ini.
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection


