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
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Biaya Pendaftaran</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Notab</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Nama Matakuliah</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">SKS</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if(count($evaluasiData) > 0)
                            @foreach ($evaluasiData as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item['no'] }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item['id'] }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item['tgl']->format('Y-m-d H:i:s') }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item['prodi'] }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item['nama'] }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-red-600">{{ $item['biaya_pendaftaran'] }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item['notab'] }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item['nama_matakuliah'] }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item['sks'] }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">
                                        <span class="inline-block rounded-lg bg-yellow-400 px-3 py-1 text-sm font-medium text-gray-900">
                                            {{ $item['status'] }}
                                        </span>
                                    </td>
                                    <td class="border border-gray-300 px-4 py-3">
                                        <button class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700">
                                            Form Evaluasi
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="11" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
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

