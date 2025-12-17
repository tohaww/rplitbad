@extends('layouts.app')

@section('title', 'Isi Riwayat Pendidikan')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-[#1b1b18]">Isi Riwayat Pendidikan</h1>
                <a
                    href="{{ route('profile') }}"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Kembali
                </a>
            </div>

            @if (session('success'))
                <div class="mb-6 rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form Tambah Riwayat Pendidikan -->
            <div class="mb-8 rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h2 class="mb-4 text-lg font-semibold text-[#1b1b18]">Tambah Riwayat Pendidikan</h2>
                
                <form action="{{ route('riwayat-pendidikan.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="nama_sekolah" class="block text-sm font-medium text-gray-700 mb-1">
                            Nama Sekolah <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="nama_sekolah"
                            name="nama_sekolah"
                            value="{{ old('nama_sekolah') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan nama sekolah"
                        />
                    </div>

                    <div>
                        <label for="tahun_lulus" class="block text-sm font-medium text-gray-700 mb-1">
                            Tahun Lulus <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="tahun_lulus"
                            name="tahun_lulus"
                            value="{{ old('tahun_lulus') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan tahun lulus"
                        />
                    </div>

                    <div>
                        <label for="jurusan_program_studi" class="block text-sm font-medium text-gray-700 mb-1">
                            Jurusan/Program Studi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="jurusan_program_studi"
                            name="jurusan_program_studi"
                            value="{{ old('jurusan_program_studi') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan jurusan/program studi"
                        />
                    </div>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

