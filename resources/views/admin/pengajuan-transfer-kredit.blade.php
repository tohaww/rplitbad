@extends('layouts.admin')

@section('title', 'Pengajuan Transfer Kredit')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-900">Pengajuan Transfer Kredit</h1>

        <!-- Filter Section -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <form method="GET" action="{{ route('admin.pengajuan.transfer-kredit') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700">Cari (Nama)</label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="Cari nama..."
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>

                    <!-- Status Filter -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                        <select
                            id="status"
                            name="status"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >
                            <option value="">Semua Status</option>
                            @foreach($statuses as $status)
                                <option value="{{ $status }}" {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>
                                    {{ $status }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Program Studi Tertuju Filter -->
                    <div>
                        <label for="program_studi_tertuju" class="block text-sm font-medium text-gray-700">Program Studi Tertuju</label>
                        <select
                            id="program_studi_tertuju"
                            name="program_studi_tertuju"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >
                            <option value="">Semua Prodi</option>
                            @foreach($programStudiTertuju as $prodi)
                                <option value="{{ $prodi }}" {{ ($filters['program_studi_tertuju'] ?? '') === $prodi ? 'selected' : '' }}>
                                    {{ $prodi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Tanggal Dari -->
                    <div>
                        <label for="tanggal_dari" class="block text-sm font-medium text-gray-700">Tanggal Dari</label>
                        <input
                            type="date"
                            id="tanggal_dari"
                            name="tanggal_dari"
                            value="{{ $filters['tanggal_dari'] ?? '' }}"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                    <!-- Tanggal Sampai -->
                    <div>
                        <label for="tanggal_sampai" class="block text-sm font-medium text-gray-700">Tanggal Sampai</label>
                        <input
                            type="date"
                            id="tanggal_sampai"
                            name="tanggal_sampai"
                            value="{{ $filters['tanggal_sampai'] ?? '' }}"
                            class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-end gap-2 lg:col-span-4">
                        <button
                            type="submit"
                            class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Filter
                        </button>
                        <a
                            href="{{ route('admin.pengajuan.transfer-kredit') }}"
                            class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                        >
                            Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Perguruan Tinggi Asal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Program Studi Tertuju</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($pengajuan as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $item->mahasiswa->user->name ?? $item->mahasiswa->nama ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $item->perguruan_tinggi_asal ?? '-' }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $item->program_studi_tertuju ?? '-' }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    @if ($item->status === 'Diterima' || $item->status === 'Disetujui')
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                            {{ $item->status }}
                                        </span>
                                    @elseif ($item->status === 'Ditolak')
                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">
                                            {{ $item->status }}
                                        </span>
                                    @elseif ($item->status === 'Sudah Diajukan')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">
                                            {{ $item->status }}
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800">
                                            {{ $item->status }}
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $item->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <a
                                        href="{{ route('admin.pengajuan.transfer-kredit.show', $item->id) }}"
                                        class="text-blue-600 hover:text-blue-900 hover:underline"
                                    >
                                        Lihat Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Tidak ada data pengajuan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($pengajuan->hasPages())
                <div class="border-t border-gray-200 px-6 py-4">
                    {{ $pengajuan->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

