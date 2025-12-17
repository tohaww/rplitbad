@extends('layouts.admin')

@section('title', 'Tambah Mata Kuliah')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Tambah Mata Kuliah</h1>
            <a
                href="{{ route('admin.courses') }}"
                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            >
                Kembali
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                <ul class="list-disc list-inside space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <form action="{{ route('admin.courses.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="id_matkul" class="block text-sm font-medium text-gray-700">
                        ID Matkul <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="id_matkul"
                        name="id_matkul"
                        value="{{ old('id_matkul') }}"
                        required
                        maxlength="255"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Contoh: MK001, MAT101, TI-2024"
                    />
                    @error('id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="kode_matkul" class="block text-sm font-medium text-gray-700">
                        Kode Matkul <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="kode_matkul"
                        name="kode_matkul"
                        value="{{ old('kode_matkul') }}"
                        required
                        maxlength="255"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Contoh: MK001, TI101, MAT101"
                    />
                    @error('kode_matkul')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nama_matkul" class="block text-sm font-medium text-gray-700">
                        Nama Matkul <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="nama_matkul"
                        name="nama_matkul"
                        value="{{ old('nama_matkul') }}"
                        required
                        maxlength="255"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Contoh: Pemrograman Web"
                    />
                    @error('nama_matkul')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="prodi" class="block text-sm font-medium text-gray-700">
                        Kode Prodi <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="prodi"
                        name="prodi"
                        required
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >
                        <option value="">-- Pilih Kode Prodi --</option>
                        @foreach ($programStudis as $prodi)
                            <option value="{{ $prodi->kode_prodi }}" {{ old('prodi') == $prodi->kode_prodi ? 'selected' : '' }}>
                                {{ $prodi->kode_prodi }} - {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">
                        Pilih kode prodi. Nama prodi akan otomatis terisi.
                    </p>
                    @error('prodi')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sks" class="block text-sm font-medium text-gray-700">
                        SKS <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="sks"
                        name="sks"
                        value="{{ old('sks', 0) }}"
                        required
                        min="0"
                        max="10"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Contoh: 3"
                    />
                    <p class="mt-1 text-xs text-gray-500">
                        Masukkan jumlah SKS (0-10)
                    </p>
                    @error('sks')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="deskripsi" class="block text-sm font-medium text-gray-700">
                        Deskripsi
                    </label>
                    <textarea
                        id="deskripsi"
                        name="deskripsi"
                        rows="4"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan deskripsi mata kuliah (opsional)"
                    >{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a
                        href="{{ route('admin.courses') }}"
                        class="rounded-lg border border-gray-300 px-6 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Batal
                    </a>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-6 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        </div>

        <!-- Section Import -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-xl font-semibold text-gray-900">Import Mata Kuliah</h2>
            <p class="mb-4 text-sm text-gray-600">
                Upload file Excel atau CSV untuk mengimpor data mata kuliah secara massal.
            </p>
            
            <form action="{{ route('admin.courses.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label for="delimiter" class="block text-sm font-medium text-gray-700 mb-2">
                        Pemisah Kolom <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                name="delimiter"
                                value=","
                                checked
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                            />
                            <span class="text-sm text-gray-700">
                                Koma <span class="font-mono bg-gray-100 px-1 rounded">,</span>
                            </span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                name="delimiter"
                                value=";"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                            />
                            <span class="text-sm text-gray-700">
                                Titik Koma <span class="font-mono bg-gray-100 px-1 rounded">;</span>
                            </span>
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Pilih pemisah kolom yang digunakan dalam file CSV Anda
                    </p>
                </div>

                <div>
                    <label for="import_file" class="block text-sm font-medium text-gray-700">
                        Pilih File <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="file"
                        id="import_file"
                        name="import_file"
                        accept=".csv,text/csv"
                        required
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
                    />
                    <p class="mt-1 text-xs text-gray-500">
                        Format yang didukung: CSV (.csv)
                    </p>
                    @error('import_file')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-2">
                        <a
                            href="{{ route('admin.courses.download-template') }}"
                            class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download Contoh File CSV
                        </a>
                    </div>
                </div>

                <div class="flex items-start gap-2 rounded-lg border border-blue-200 bg-blue-50 p-3">
                    <svg class="h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-xs text-blue-700">
                        <p class="font-semibold mb-1">Format File:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>Baris pertama harus berisi header: <span class="font-mono bg-white/50 px-1 rounded">ID, Kode Matkul, Nama Matkul, Kode Prodi, SKS, Deskripsi</span></li>
                            <li>Kolom pemisah dapat menggunakan tanda koma <span class="font-mono bg-white/50 px-1 rounded">,</span> atau titik koma <span class="font-mono bg-white/50 px-1 rounded">;</span> (pilih sesuai file Anda)</li>
                            <li>ID harus unik (tidak boleh duplikat)</li>
                            <li>Kode Matkul harus unik (tidak boleh duplikat)</li>
                            <li>Nama Matkul wajib diisi</li>
                            <li><strong>Kode Prodi wajib diisi (bukan nama prodi)</strong> - Nama prodi akan otomatis terisi berdasarkan kode prodi</li>
                            <li>SKS wajib diisi (0-10)</li>
                            <li>Deskripsi bersifat opsional</li>
                        </ul>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="rounded-lg bg-green-600 px-6 py-2 text-sm font-medium text-white transition hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                    >
                        Import Data
                    </button>
                </div>
            </form>
        </div>

        <!-- Section Upload CSV Point Pertanyaan -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-xl font-semibold text-gray-900">Upload CSV Point Pertanyaan</h2>
            <p class="mb-4 text-sm text-gray-600">
                Upload file CSV untuk mengimpor point pertanyaan (Capaian Pembelajaran) untuk mata kuliah yang akan dibuat.
            </p>
            
            <form action="{{ route('admin.courses.upload-point-pertanyaan') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                
                <div>
                    <label for="point_delimiter" class="block text-sm font-medium text-gray-700 mb-2">
                        Pemisah Kolom <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-4">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                name="point_delimiter"
                                value=","
                                checked
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                            />
                            <span class="text-sm text-gray-700">
                                Koma <span class="font-mono bg-gray-100 px-1 rounded">,</span>
                            </span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input
                                type="radio"
                                name="point_delimiter"
                                value=";"
                                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                            />
                            <span class="text-sm text-gray-700">
                                Titik Koma <span class="font-mono bg-gray-100 px-1 rounded">;</span>
                            </span>
                        </label>
                    </div>
                    <p class="mt-1 text-xs text-gray-500">
                        Pilih pemisah kolom yang digunakan dalam file CSV Anda
                    </p>
                </div>

                <div>
                    <label for="point_import_file" class="block text-sm font-medium text-gray-700">
                        Pilih File CSV <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="file"
                        id="point_import_file"
                        name="point_import_file"
                        accept=".csv,text/csv"
                        required
                        class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:rounded-lg file:border-0 file:bg-blue-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-blue-700 hover:file:bg-blue-100"
                    />
                    <p class="mt-1 text-xs text-gray-500">
                        Format yang didukung: CSV (.csv)
                    </p>
                    @error('point_import_file')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="mt-2">
                        <a
                            href="{{ route('admin.courses.download-point-template') }}"
                            class="inline-flex items-center gap-2 text-sm text-blue-600 hover:text-blue-700"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            Download Contoh File CSV Point Pertanyaan
                        </a>
                    </div>
                </div>

                <div class="flex items-start gap-2 rounded-lg border border-blue-200 bg-blue-50 p-3">
                    <svg class="h-5 w-5 shrink-0 text-blue-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="text-xs text-blue-700">
                        <p class="font-semibold mb-1">Format File:</p>
                        <ul class="list-disc list-inside space-y-0.5">
                            <li>Baris pertama harus berisi header: <span class="font-mono bg-white/50 px-1 rounded">ID Matkul, Capaian Pembelajaran</span></li>
                            <li>Kolom pemisah dapat menggunakan tanda koma <span class="font-mono bg-white/50 px-1 rounded">,</span> atau titik koma <span class="font-mono bg-white/50 px-1 rounded">;</span> (pilih sesuai file Anda)</li>
                            <li>ID Matkul harus sesuai dengan ID Matkul yang akan dibuat di form di atas</li>
                            <li>Capaian Pembelajaran wajib diisi (teks point pertanyaan)</li>
                            <li>Setiap baris mewakili satu point pertanyaan</li>
                            <li>Untuk satu mata kuliah dapat memiliki beberapa point pertanyaan (beberapa baris dengan ID Matkul yang sama)</li>
                        </ul>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button
                        type="submit"
                        class="rounded-lg bg-purple-600 px-6 py-2 text-sm font-medium text-white transition hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                    >
                        Upload Point Pertanyaan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

