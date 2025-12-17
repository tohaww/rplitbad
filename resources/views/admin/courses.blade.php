@extends('layouts.admin')

@section('title', 'Data Matkul')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Data Matkul</h1>
            <a
                href="{{ route('admin.courses.create') }}"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                + Tambah Matkul
            </a>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter Section -->
        <div class="rounded-lg border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.courses') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <!-- Search Filter -->
                    <div>
                        <label for="search" class="mb-1 block text-sm font-medium text-gray-700">
                            Cari
                        </label>
                        <input
                            type="text"
                            id="search"
                            name="search"
                            value="{{ $filters['search'] }}"
                            placeholder="Kode atau Nama Matkul"
                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>

                    <!-- Prodi Filter -->
                    <div>
                        <label for="prodi" class="mb-1 block text-sm font-medium text-gray-700">
                            Prodi
                        </label>
                        <select
                            id="prodi"
                            name="prodi"
                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >
                            <option value="">Semua Prodi</option>
                            @foreach ($prodiList as $prodi)
                                <option value="{{ $prodi }}" {{ $filters['prodi'] == $prodi ? 'selected' : '' }}>
                                    {{ $prodi }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- SKS Filter -->
                    <div>
                        <label for="sks" class="mb-1 block text-sm font-medium text-gray-700">
                            SKS
                        </label>
                        <select
                            id="sks"
                            name="sks"
                            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >
                            <option value="">Semua SKS</option>
                            @foreach ($sksList as $sks)
                                <option value="{{ $sks }}" {{ $filters['sks'] == $sks ? 'selected' : '' }}>
                                    {{ $sks }} SKS
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Filter Buttons -->
                    <div class="flex items-end gap-2">
                        <button
                            type="submit"
                            class="w-full rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Filter
                        </button>
                        @if ($filters['search'] || $filters['prodi'] || $filters['sks'])
                            <a
                                href="{{ route('admin.courses') }}"
                                class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                            >
                                Reset
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Kode Matkul
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Nama Matkul
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Prodi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                SKS
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Deskripsi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($courses as $course)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $course->kode_matkul }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    {{ $course->nama_matkul }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $course->prodi }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $course->sks ?? 0 }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    @if($course->deskripsi)
                                        @php
                                            $words = explode(' ', $course->deskripsi);
                                            $firstThreeWords = implode(' ', array_slice($words, 0, 3));
                                        @endphp
                                        {{ $firstThreeWords }}{{ count($words) > 3 ? '...' : '' }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <a
                                            href="{{ route('admin.courses.evaluasi', $course->id_matkul) }}"
                                            class="rounded-lg bg-green-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-green-700"
                                        >
                                            Form Evaluasi
                                        </a>
                                        <button
                                            type="button"
                                            onclick="openEditModal('{{ $course->id_matkul }}', '{{ $course->kode_matkul }}', '{{ addslashes($course->nama_matkul) }}', '{{ addslashes($course->prodi) }}', {{ $course->sks ?? 0 }}, '{{ addslashes($course->deskripsi ?? '') }}')"
                                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-blue-700"
                                            data-prodi-nama="{{ addslashes($course->prodi) }}"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            type="button"
                                            onclick="openDeleteModal('{{ $course->id_matkul }}', '{{ addslashes($course->nama_matkul) }}')"
                                            class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-red-700"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    @if ($filters['search'] || $filters['prodi'] || $filters['sks'])
                                        Tidak ada data matkul yang sesuai dengan filter yang dipilih.
                                    @else
                                        Belum ada data matkul.
                                    @endif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if ($courses->count() > 0)
                <div class="border-t border-gray-200 px-6 py-3">
                    <p class="text-sm text-gray-600">
                        Menampilkan <span class="font-medium">{{ $courses->count() }}</span> data mata kuliah
                        @if ($filters['search'] || $filters['prodi'] || $filters['sks'])
                            (difilter)
                        @endif
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">Konfirmasi Hapus</h2>
                <button
                    onclick="closeDeleteModal()"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-600">
                    Apakah Anda yakin ingin menghapus mata kuliah <span id="deleteCourseName" class="font-semibold text-gray-900"></span>?
                </p>
                <p class="mt-2 text-xs text-red-600">
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <form id="deleteForm" method="POST" class="flex justify-end gap-3">
                @csrf
                @method('DELETE')
                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    Hapus
                </button>
            </form>
        </div>
    </div>

    <!-- Modal Form Edit Matkul -->
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">Edit Mata Kuliah</h2>
                <button
                    onclick="closeEditModal()"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="editForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit_id" class="block text-sm font-medium text-gray-700">
                        ID
                    </label>
                    <input
                        type="text"
                        id="edit_id"
                        name="id"
                        readonly
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-500"
                    />
                </div>

                <div>
                    <label for="edit_kode_matkul" class="block text-sm font-medium text-gray-700">
                        Kode Matkul
                    </label>
                    <input
                        type="text"
                        id="edit_kode_matkul"
                        name="kode_matkul"
                        readonly
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-500"
                    />
                </div>

                <div>
                    <label for="edit_nama_matkul" class="block text-sm font-medium text-gray-700">
                        Nama Matkul <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="edit_nama_matkul"
                        name="nama_matkul"
                        required
                        maxlength="255"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Contoh: Pemrograman Web"
                    />
                    @error('nama_matkul')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="edit_prodi" class="block text-sm font-medium text-gray-700">
                        Kode Prodi <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="edit_prodi"
                        name="prodi"
                        required
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >
                        <option value="">-- Pilih Kode Prodi --</option>
                        @foreach ($programStudis as $prodi)
                            <option value="{{ $prodi->kode_prodi }}" data-nama="{{ $prodi->nama_prodi }}">
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
                    <label for="edit_sks" class="block text-sm font-medium text-gray-700">
                        SKS <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="edit_sks"
                        name="sks"
                        required
                        min="0"
                        max="10"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
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
                    <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700">
                        Deskripsi
                    </label>
                    <textarea
                        id="edit_deskripsi"
                        name="deskripsi"
                        rows="3"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan deskripsi mata kuliah (opsional)"
                    ></textarea>
                    @error('deskripsi')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeEditModal()"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Data program studi untuk lookup (nama prodi -> kode prodi)
        const programStudis = @json($programStudis->mapWithKeys(function($prodi) {
            return [$prodi->nama_prodi => $prodi->kode_prodi];
        }));

        function openEditModal(id, kodeMatkul, namaMatkul, prodiNama, sks, deskripsi) {
            try {
                console.log('Opening edit modal for:', id);
                
                const editId = document.getElementById('edit_id');
                const editKodeMatkul = document.getElementById('edit_kode_matkul');
                const editNamaMatkul = document.getElementById('edit_nama_matkul');
                const editProdi = document.getElementById('edit_prodi');
                const editSks = document.getElementById('edit_sks');
                const editDeskripsi = document.getElementById('edit_deskripsi');
                const editForm = document.getElementById('editForm');
                const editModal = document.getElementById('editModal');
                
                if (!editId || !editKodeMatkul || !editNamaMatkul || !editProdi || !editSks || !editDeskripsi || !editForm || !editModal) {
                    console.error('Modal elements not found!');
                    alert('Error: Modal elements not found. Please refresh the page.');
                    return;
                }
                
                editId.value = id || '';
                editKodeMatkul.value = kodeMatkul || '';
                editNamaMatkul.value = namaMatkul || '';
                editSks.value = sks || 0;
                editDeskripsi.value = deskripsi || '';
                
                // Cari kode prodi dari nama prodi yang tersimpan
                const kodeProdi = programStudis[prodiNama] || '';
                if (kodeProdi) {
                    editProdi.value = kodeProdi;
                } else {
                    console.warn('Kode prodi tidak ditemukan untuk:', prodiNama);
                    editProdi.value = '';
                }
                
                const updateUrl = '{{ route('admin.courses.update', ':id') }}'.replace(':id', id);
                editForm.action = updateUrl;
                
                editModal.classList.remove('hidden');
                editModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
                
                console.log('Modal opened successfully');
            } catch (error) {
                console.error('Error opening edit modal:', error);
                alert('Terjadi kesalahan saat membuka form edit. Silakan refresh halaman.');
            }
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('editModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditModal();
                closeDeleteModal();
            }
        });

        function openDeleteModal(id, namaMatkul) {
            try {
                const deleteModal = document.getElementById('deleteModal');
                const deleteForm = document.getElementById('deleteForm');
                const deleteCourseName = document.getElementById('deleteCourseName');
                
                if (!deleteModal || !deleteForm || !deleteCourseName) {
                    console.error('Delete modal elements not found!');
                    alert('Error: Modal elements not found. Please refresh the page.');
                    return;
                }
                
                deleteCourseName.textContent = namaMatkul || '';
                
                const deleteUrl = '{{ route('admin.courses.destroy', ':id') }}'.replace(':id', id);
                deleteForm.action = deleteUrl;
                
                deleteModal.classList.remove('hidden');
                deleteModal.classList.add('flex');
                document.body.style.overflow = 'hidden';
            } catch (error) {
                console.error('Error opening delete modal:', error);
                alert('Terjadi kesalahan saat membuka konfirmasi hapus. Silakan refresh halaman.');
            }
        }

        function closeDeleteModal() {
            const deleteModal = document.getElementById('deleteModal');
            if (deleteModal) {
                deleteModal.classList.add('hidden');
                deleteModal.classList.remove('flex');
                document.body.style.overflow = 'auto';
            }
        }

        document.getElementById('deleteModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteModal();
            }
        });
    </script>
@endsection

