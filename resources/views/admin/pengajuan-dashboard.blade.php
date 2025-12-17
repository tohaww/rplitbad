@extends('layouts.admin')

@section('title', 'Dashboard Pengajuan')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-900">Dashboard Pengajuan</h1>

        <!-- Statistik Cards -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
            <!-- Perolehan Kredit Total -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Perolehan Kredit</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $perolehanKreditTotal }}</p>
                    </div>
                    <div class="rounded-full bg-blue-100 p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex gap-4 text-xs text-gray-500">
                    <span>Hari ini: {{ $perolehanKreditHariIni }}</span>
                    <span>Minggu ini: {{ $perolehanKreditMingguIni }}</span>
                    <span>Bulan ini: {{ $perolehanKreditBulanIni }}</span>
                </div>
            </div>

            <!-- Transfer Kredit Total -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Transfer Kredit</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">{{ $transferKreditTotal }}</p>
                    </div>
                    <div class="rounded-full bg-green-100 p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex gap-4 text-xs text-gray-500">
                    <span>Hari ini: {{ $transferKreditHariIni }}</span>
                    <span>Minggu ini: {{ $transferKreditMingguIni }}</span>
                    <span>Bulan ini: {{ $transferKreditBulanIni }}</span>
                </div>
            </div>

            <!-- Perolehan Kredit Pending -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Perolehan Kredit Pending</p>
                        <p class="mt-2 text-3xl font-bold text-yellow-600">{{ $perolehanKreditPending->count() }}</p>
                    </div>
                    <div class="rounded-full bg-yellow-100 p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <a href="{{ route('admin.pengajuan.semua', ['status' => 'Sudah Diajukan']) }}" class="mt-4 text-xs text-blue-600 hover:underline">Lihat semua →</a>
            </div>

            <!-- Transfer Kredit Pending -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Transfer Kredit Pending</p>
                        <p class="mt-2 text-3xl font-bold text-yellow-600">{{ $transferKreditPending->count() }}</p>
                    </div>
                    <div class="rounded-full bg-yellow-100 p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <a href="{{ route('admin.pengajuan.semua', ['status' => 'Sudah Diajukan']) }}" class="mt-4 text-xs text-blue-600 hover:underline">Lihat semua →</a>
            </div>
        </div>

        <!-- Statistik per Status -->
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <!-- Perolehan Kredit by Status -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Perolehan Kredit per Status</h2>
                <div class="space-y-3">
                    @forelse($perolehanKreditByStatus as $status => $total)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $status }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $total }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Tidak ada data</p>
                    @endforelse
                </div>
            </div>

            <!-- Transfer Kredit by Status -->
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="mb-4 text-lg font-semibold text-gray-900">Transfer Kredit per Status</h2>
                <div class="space-y-3">
                    @forelse($transferKreditByStatus as $status => $total)
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">{{ $status }}</span>
                            <span class="text-sm font-semibold text-gray-900">{{ $total }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Tidak ada data</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Pengajuan Terbaru -->
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">PENGAJUAN TERBARU</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Kode Referensi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Program Studi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($pengajuanTerbaru as $pengajuan)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $pengajuan['nama'] }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $pengajuan['kode_referensi'] ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $pengajuan['program_studi'] }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    @if ($pengajuan['status'] === 'Diterima' || $pengajuan['status'] === 'Disetujui')
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                            {{ $pengajuan['status'] }}
                                        </span>
                                    @elseif ($pengajuan['status'] === 'Ditolak')
                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">
                                            {{ $pengajuan['status'] }}
                                        </span>
                                    @elseif ($pengajuan['status'] === 'Sudah Diajukan')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">
                                            {{ $pengajuan['status'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800">
                                            {{ $pengajuan['status'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $pengajuan['tanggal']->format('d/m/Y H:i') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada pengajuan terbaru
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

