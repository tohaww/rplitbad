@extends('layouts.app')

@section('title', 'Daftar Matakuliah Prodi')

@section('content')
    <div class="space-y-6">
        <!-- Header dengan breadcrumb -->
        <div class="flex items-center justify-between">
            <h1 class="text-2xl font-semibold text-gray-900">Daftar Matakuliah Prodi</h1>
            <div class="text-sm text-gray-600">
                <a href="{{ route('dashboard') }}" class="text-blue-600 hover:text-blue-800">Menu</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">Daftar Matakuliah Prodi</span>
            </div>
        </div>

        <!-- Main Content Card -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <!-- Section Header -->
            <div class="mb-4 flex items-center gap-3">
                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <h2 class="text-lg font-semibold uppercase text-gray-900">Daftar Matakuliah Prodi</h2>
            </div>

            <!-- Instructional Text -->
            <div class="mb-6 text-sm leading-relaxed text-gray-700">
                <p>
                    Daftar mata kuliah program studi dikhususkan hanya bagi calon mahasiswa yang akan mengambil RPL jalur Perolehan Kredit. Daftar ini memuat nama-nama mata kuliah dan bobot SKS nya yang dapat direkognisikan pada masing-masing program studi. Berdasarkan daftar mata kuliah ini calon mahasiswa dapat mengajukan mata kuliah yang direkognisikan pada menu Pengajuan Mata Kuliah >> Pilih menu Perolehan Kredit. Silakan Klik Pilih Program Studi kemudian klik tombol Tampilkan Mata Kuliah
                </p>
            </div>

            <!-- Form untuk memilih program studi -->
            <form method="GET" action="{{ route('courses') }}" class="mb-6">
                <div class="flex items-end gap-4">
                    <div class="flex-1">
                        <label for="program_studi" class="mb-2 block text-sm font-medium text-gray-700">
                            Pilih Program Studi
                        </label>
                        <select
                            id="program_studi"
                            name="program_studi"
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >
                            <option value="">[ Pilih Program Studi ]</option>
                            @foreach($programStudis ?? [] as $prodi)
                                <option value="{{ $prodi->kode_prodi }}" {{ request('program_studi') == $prodi->kode_prodi ? 'selected' : '' }}>
                                    {{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Tampilkan Matkul
                    </button>
                </div>
            </form>

            <!-- Table -->
            @if(request('program_studi') && isset($courses) && $courses->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border-r border-gray-200 px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                    No
                                </th>
                                <th class="border-r border-gray-200 px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                    Kode Matkul
                                </th>
                                <th class="border-r border-gray-200 px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                    Matakuliah
                                </th>
                                <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700">
                                    SKS
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            @foreach($courses as $index => $course)
                                <tr class="hover:bg-gray-50">
                                    <td class="border-r border-gray-200 whitespace-nowrap px-4 py-3 text-sm text-gray-900">
                                        {{ $index + 1 }}
                                    </td>
                                    <td class="border-r border-gray-200 whitespace-nowrap px-4 py-3 text-sm text-gray-900">
                                        {{ $course->kode_matkul ?? '-' }}
                                    </td>
                                    <td class="border-r border-gray-200 px-4 py-3 text-sm text-gray-900">
                                        {{ $course->nama_matkul ?? '-' }}
                                    </td>
                                    <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900">
                                        {{ $course->sks ?? '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif(request('program_studi'))
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center">
                    <p class="text-sm text-gray-600">Tidak ada data mata kuliah untuk program studi yang dipilih.</p>
                </div>
            @else
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center">
                    <p class="text-sm text-gray-600">Silakan pilih program studi terlebih dahulu untuk menampilkan daftar mata kuliah.</p>
                </div>
            @endif
        </div>
    </div>
@endsection
