@extends('layouts.app')

@section('title', 'Evaluasi Mahasiswa')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#1b1b18]">Evaluasi Mahasiswa</h1>
            </div>
            <div class="text-sm text-gray-600">
                <span class="text-blue-600">Menu</span> / <span class="text-[#1b1b18]">Evaluasi Mahasiswa</span>
            </div>
        </div>

        <!-- Table Section -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <h2 class="mb-4 text-lg font-semibold text-[#1b1b18]">Evaluasi Mahasiswa</h2>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">No</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">ID</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Tgl</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Prodi</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Nama</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Kode Matkul</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Nama Matakuliah</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">SKS</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if(count($evaluasiData) > 0)
                            @foreach ($evaluasiData as $item)
                                @php
                                    $rowspan = count($item['courses'] ?? []);
                                @endphp

                                @foreach ($item['courses'] as $index => $course)
                                    <tr class="hover:bg-gray-50">
                                        @if ($index === 0)
                                            <td rowspan="{{ $rowspan }}" class="border border-gray-300 px-4 py-3 text-sm text-gray-900 align-top">
                                                {{ $item['no'] }}
                                            </td>
                                            <td rowspan="{{ $rowspan }}" class="border border-gray-300 px-4 py-3 text-sm text-gray-900 align-top">
                                                {{ $item['id'] }}
                                            </td>
                                            <td rowspan="{{ $rowspan }}" class="border border-gray-300 px-4 py-3 text-sm text-gray-900 align-top">
                                                {{ $item['tgl']->format('Y-m-d H:i:s') }}
                                            </td>
                                            <td rowspan="{{ $rowspan }}" class="border border-gray-300 px-4 py-3 text-sm text-gray-900 align-top">
                                                {{ $item['prodi'] }}
                                            </td>
                                            <td rowspan="{{ $rowspan }}" class="border border-gray-300 px-4 py-3 text-sm text-gray-900 align-top">
                                                {{ $item['nama'] }}
                                            </td>
                                        @endif

                                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">
                                            {{ $course['notab'] }}
                                        </td>
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">
                                            {{ $course['nama_matakuliah'] }}
                                        </td>
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">
                                            {{ $course['sks'] }}
                                        </td>
                                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">
                                            @if($course['status'] === 'Sudah')
                                                <span class="inline-block rounded-lg bg-green-500 px-3 py-1 text-sm font-medium text-white">
                                                    {{ $course['status'] }}
                                                </span>
                                            @else
                                                <span class="inline-block rounded-lg bg-yellow-400 px-3 py-1 text-sm font-medium text-gray-900">
                                                    {{ $course['status'] }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="border border-gray-300 px-4 py-3">
                                            @if(!empty($course['id_matkul']) && !empty($course['pengajuan_id']))
                                                <a
                                                    href="{{ route('self-evaluation.form', ['pengajuan' => $course['pengajuan_id'], 'matkul' => $course['id_matkul']]) }}"
                                                    class="inline-flex items-center justify-center rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                                                >
                                                    Form Evaluasi
                                                </a>
                                            @else
                                                <span class="text-xs text-gray-400">Data matkul tidak lengkap</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada data evaluasi
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

