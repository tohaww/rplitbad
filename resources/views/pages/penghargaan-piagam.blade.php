@extends('layouts.app')

@section('title', 'Isi Penghargaan/Piagam')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-[#1b1b18]">Isi Penghargaan/Piagam</h1>
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

            <!-- Form Tambah Penghargaan/Piagam -->
            <div class="mb-8 rounded-lg border border-gray-200 bg-gray-50 p-6">
                <h2 class="mb-4 text-lg font-semibold text-[#1b1b18]">Tambah Penghargaan/Piagam</h2>
                
                <form action="{{ route('penghargaan-piagam.store') }}" method="POST" class="space-y-4">
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
                        <label for="bentuk_penghargaan" class="block text-sm font-medium text-gray-700 mb-1">
                            Bentuk Penghargaan <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="bentuk_penghargaan"
                            name="bentuk_penghargaan"
                            value="{{ old('bentuk_penghargaan') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan bentuk penghargaan"
                        />
                    </div>

                    <div>
                        <label for="pemberi" class="block text-sm font-medium text-gray-700 mb-1">
                            Pemberi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="pemberi"
                            name="pemberi"
                            value="{{ old('pemberi') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Masukkan pemberi"
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

