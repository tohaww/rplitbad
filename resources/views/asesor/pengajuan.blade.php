@extends('layouts.app')

@section('title', 'Daftar Pengajuan')

@section('content')
    <div class="space-y-6">
        <h1 class="text-3xl font-bold text-gray-900">Daftar Pengajuan</h1>

        <!-- Filter Section -->
        <div class="rounded-lg border border-[#1914001a] bg-white/80 p-6 shadow-[0px_25px_50px_-12px_rgba(0,0,0,0.08)]">
            <form method="GET" action="{{ route('asesor.pengajuan') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-5">
                    <!-- Search -->
                    <div class="lg:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700">Cari (Nama/Email/No. Bukti)</label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $filters['search'] ?? '' }}"
                            placeholder="Cari nama, email atau no. bukti..."
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
                                @if($status !== 'Draft')
                                    <option value="{{ $status }}" {{ ($filters['status'] ?? '') === $status ? 'selected' : '' }}>
                                        {{ $status }}
                                    </option>
                                @endif
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
                </div>

                <div class="flex items-end gap-2">
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Filter
                    </button>
                    <a
                        href="{{ route('asesor.pengajuan') }}"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
                    >
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="rounded-lg border border-[#1914001a] bg-white/80 shadow-[0px_25px_50px_-12px_rgba(0,0,0,0.08)]">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama Mahasiswa</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Program Studi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($pengajuan as $index => $item)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    {{ $index + 1 }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $item['nama'] }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $item['email'] }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ $item['program_studi'] }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    @if ($item['status'] === 'Diterima' || $item['status'] === 'Disetujui')
                                        <span class="inline-flex rounded-full bg-green-100 px-2 py-1 text-xs font-semibold text-green-800">
                                            {{ $item['status'] }}
                                        </span>
                                    @elseif ($item['status'] === 'Ditolak')
                                        <span class="inline-flex rounded-full bg-red-100 px-2 py-1 text-xs font-semibold text-red-800">
                                            {{ $item['status'] }}
                                        </span>
                                    @elseif ($item['status'] === 'Sudah Diajukan')
                                        <span class="inline-flex rounded-full bg-yellow-100 px-2 py-1 text-xs font-semibold text-yellow-800">
                                            {{ $item['status'] }}
                                        </span>
                                    @else
                                        <span class="inline-flex rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-800">
                                            {{ $item['status'] }}
                                        </span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $item['tanggal']->format('d/m/Y H:i') }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <a
                                            href="{{ route('asesor.pengajuan.detail', ['jenis' => strtolower(str_replace(' ', '-', $item['jenis'])), 'id' => $item['id']]) }}"
                                            class="text-blue-600 hover:text-blue-900 hover:underline"
                                        >
                                            Lihat Detail
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-8 text-center text-sm text-gray-500">
                                    Tidak ada data pengajuan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
