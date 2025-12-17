@extends('layouts.app')

@section('title', 'Form Evaluasi Diri')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#1b1b18]">Form Evaluasi Diri</h1>
            </div>
            <div class="text-sm text-gray-600">
                <span class="text-blue-600">Menu</span> / <span class="text-[#1b1b18]">Form Evaluasi Diri</span>
            </div>
        </div>

        <!-- Info Mata Kuliah -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <table class="w-full text-sm">
                <tbody>
                    <tr>
                        <td class="w-40 border border-gray-300 px-4 py-2 font-semibold">Notab</td>
                        <td class="w-4 border border-gray-300 px-2 py-2 text-center">:</td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $selectedMatkul['kode_matkul'] ?? $course?->kode_matkul ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Matakuliah</td>
                        <td class="border border-gray-300 px-2 py-2 text-center">:</td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $selectedMatkul['nama_matkul'] ?? $course?->nama_matkul ?? '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">SKS</td>
                        <td class="border border-gray-300 px-2 py-2 text-center">:</td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $course?->sks ? $course->sks . ' SKS' : '-' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2 font-semibold">Deskripsi Matakuliah</td>
                        <td class="border border-gray-300 px-2 py-2 text-center">:</td>
                        <td class="border border-gray-300 px-4 py-2">
                            {{ $course?->deskripsi ?? '-' }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Form Evaluasi -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <form action="{{ route('self-evaluation.store') }}" method="POST">
                @csrf
                <input type="hidden" name="pengajuan_id" value="{{ $pengajuan->id }}">
                <input type="hidden" name="id_matkul" value="{{ $course->id_matkul ?? $selectedMatkul['id_matkul'] ?? '' }}">
                <table class="min-w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="w-10 border border-gray-300 px-2 py-3 text-center">No</th>
                            <th class="border border-gray-300 px-4 py-3 text-center">
                                Kemampuan Akhir Yang Diharapkan/<br>
                                Capaian Pembelajaran Mata Kuliah
                            </th>
                            <th class="w-72 border border-gray-300 px-4 py-3 text-center">
                                Profisiensi pengetahuan dan<br>
                                keterampilan saat ini*
                            </th>
                            <th class="w-40 border border-gray-300 px-4 py-3 text-center">Jenis Dokumen</th>
                            <th class="w-48 border border-gray-300 px-4 py-3 text-center">
                                Bukti yang<br>disampaikan*
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($capaianPembelajaran ?? [] as $index => $text)
                            @php
                                $existingData = $existingEvaluasi[$text] ?? null;
                                $profisiensiValue = $existingData->profisiensi ?? null;
                                $jenisDokumenValue = $existingData->jenis_dokumen ?? null;
                                $buktiValue = $existingData->bukti ?? null;
                            @endphp
                            <tr class="align-top">
                                <td class="border border-gray-300 px-2 py-3 text-center text-sm">
                                    {{ $index + 1 }}
                                </td>
                                <td class="border border-gray-300 px-4 py-3 text-sm">
                                    <input type="hidden" name="evaluasi[{{ $index }}][capaian_pembelajaran]" value="{{ $text }}">
                                    {{ $text }}
                                </td>
                                <td class="border border-gray-300 px-4 py-3 text-sm">
                                    <div class="space-y-1">
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="evaluasi[{{ $index }}][profisiensi]" value="sangat_baik" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500" {{ $profisiensiValue === 'sangat_baik' ? 'checked' : '' }}>
                                            <span>Sangat baik</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="evaluasi[{{ $index }}][profisiensi]" value="baik" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500" {{ $profisiensiValue === 'baik' ? 'checked' : '' }}>
                                            <span>Baik</span>
                                        </label>
                                        <label class="inline-flex items-center gap-2">
                                            <input type="radio" name="evaluasi[{{ $index }}][profisiensi]" value="tidak_pernah" class="h-4 w-4 border-gray-300 text-blue-600 focus:ring-blue-500" {{ $profisiensiValue === 'tidak_pernah' ? 'checked' : '' }}>
                                            <span>Tidak pernah</span>
                                        </label>
                                    </div>
                                </td>
                                <td class="border border-gray-300 px-4 py-3 text-sm">
                                    <input
                                        type="text"
                                        name="evaluasi[{{ $index }}][jenis_dokumen]"
                                        value="{{ old("evaluasi.$index.jenis_dokumen", $jenisDokumenValue) }}"
                                        class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                        placeholder="Masukkan jenis dokumen"
                                    >
                                </td>
                                <td class="border border-gray-300 px-4 py-3 text-sm">
                                    <textarea
                                        name="evaluasi[{{ $index }}][bukti]"
                                        rows="3"
                                        class="block w-full rounded-md border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                                        placeholder="Masukkan bukti"
                                    >{{ old("evaluasi.$index.bukti", $buktiValue) }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="mt-6 flex justify-center gap-3">
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Simpan
                    </button>
                    <a
                        href="{{ route('evaluasi-mahasiswa') }}"
                        class="rounded-lg bg-gray-300 px-6 py-2.5 text-sm font-medium text-gray-800 transition hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2"
                    >
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection


