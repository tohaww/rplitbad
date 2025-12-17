@extends('layouts.app')

@section('title', 'Isi Pelatihan Profesional')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-[#1b1b18]">Isi Pelatihan Profesional</h1>
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

            <!-- Form Tambah Pelatihan Profesional -->
            <div class="mb-8 rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h2 class="mb-4 text-lg font-semibold text-[#1b1b18]">Tambah Pelatihan Profesional</h2>
                
                <form action="{{ route('pelatihan-profesional.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label for="tahun" class="block text-sm font-medium text-gray-700 mb-1">
                            Tahun <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="tahun"
                            name="tahun"
                            value="{{ old('tahun') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan tahun"
                        />
                    </div>

                    <div>
                        <label for="jenis_pelatihan" class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Pelatihan (Dalam/Luar Negeri) <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="jenis_pelatihan"
                            name="jenis_pelatihan"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >
                            <option value="">Pilih Jenis Pelatihan</option>
                            <option value="Dalam Negeri" {{ old('jenis_pelatihan') == 'Dalam Negeri' ? 'selected' : '' }}>Dalam Negeri</option>
                            <option value="Luar Negeri" {{ old('jenis_pelatihan') == 'Luar Negeri' ? 'selected' : '' }}>Luar Negeri</option>
                        </select>
                    </div>

                    <div>
                        <label for="penyelenggara" class="block text-sm font-medium text-gray-700 mb-1">
                            Penyelenggara <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="penyelenggara"
                            name="penyelenggara"
                            value="{{ old('penyelenggara') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan penyelenggara"
                        />
                    </div>

                    <div>
                        <label for="jangka_waktu" class="block text-sm font-medium text-gray-700 mb-1">
                            Jangka Waktu <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="jangka_waktu"
                            name="jangka_waktu"
                            value="{{ old('jangka_waktu') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan jangka waktu"
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

