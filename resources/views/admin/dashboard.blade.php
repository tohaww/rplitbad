@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
    @php
        $user = Auth::user();
        $nama = $user->name ?? 'Admin';
    @endphp

    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-[0px_25px_50px_-12px_rgba(0,0,0,0.08)]">
            <p class="text-sm uppercase tracking-[0.3em] text-[#f53003]">Status</p>
            <h2 class="mt-2 text-2xl font-semibold">Selamat datang, {{ $nama }}!</h2>
            <p class="mt-4 text-sm text-[#706f6c]">
                Anda berhasil login sebagai admin. Silakan gunakan menu di sidebar untuk mengelola sistem.
            </p>
        </div>

        <!-- Pengajuan Rekognisi Terbaru -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-[0px_25px_50px_-12px_rgba(0,0,0,0.08)]">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pengajuan Rekognisi Terbaru</h3>
            @if(count($pengajuanRekognisi) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($pengajuanRekognisi as $pengajuan)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pengajuan['nama'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($pengajuan['status'] === 'Diterima')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                {{ $pengajuan['status'] }}
                                            </span>
                                        @elseif($pengajuan['status'] === 'Ditolak')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                {{ $pengajuan['status'] }}
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                {{ $pengajuan['status'] }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pengajuan['tanggal'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500">Tidak ada pengajuan rekognisi.</p>
            @endif
        </div>

        <!-- Data Pengguna Terbaru -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-[0px_25px_50px_-12px_rgba(0,0,0,0.08)]">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Pengguna Terbaru</h3>
            @if(count($dataPengguna) > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($dataPengguna as $pengguna)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pengguna['nama'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $pengguna['email'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $pengguna['role'] === 'Admin' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ $pengguna['role'] }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-gray-500">Tidak ada data pengguna.</p>
            @endif
        </div>
    </div>

@endsection

