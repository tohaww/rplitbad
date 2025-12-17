@extends('layouts.admin')

@section('title', 'Form Evaluasi - ' . $course->nama_matkul)

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Form Evaluasi</h1>
                <p class="mt-1 text-sm text-gray-600">{{ $course->nama_matkul }} ({{ $course->kode_matkul }})</p>
            </div>
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

        <!-- Info Mata Kuliah -->
        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Informasi Mata Kuliah</h2>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <p class="text-sm font-medium text-gray-700">ID Matkul</p>
                    <p class="text-sm text-gray-900">{{ $course->id_matkul }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Kode Matkul</p>
                    <p class="text-sm text-gray-900">{{ $course->kode_matkul }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Nama Matkul</p>
                    <p class="text-sm text-gray-900">{{ $course->nama_matkul }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Program Studi</p>
                    <p class="text-sm text-gray-900">{{ $course->prodi }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">SKS</p>
                    <p class="text-sm text-gray-900">{{ $course->sks ?? 0 }} SKS</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-700">Deskripsi</p>
                    <p class="text-sm text-gray-900">{{ $course->deskripsi ?? '-' }}</p>
                </div>
            </div>
        </div>

        <!-- Capaian Pembelajaran -->
        @if($capaianPembelajaran->count() > 0)
            <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-lg font-semibold text-gray-900">Capaian Pembelajaran</h2>
                </div>
                <div class="space-y-2">
                    @foreach($capaianPembelajaran as $index => $capaian)
                        <div class="flex items-start justify-between rounded-lg border border-gray-200 bg-gray-50 p-4">
                            <p class="flex-1 text-sm font-medium text-gray-700">{{ $index + 1 }}. {{ $capaian->capaian_pembelajaran }}</p>
                            <div class="ml-4 flex items-center gap-2">
                                <button
                                    type="button"
                                    onclick="openEditCapaianModal({{ $capaian->id }}, '{{ addslashes($capaian->capaian_pembelajaran) }}', {{ $capaian->urutan }})"
                                    class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-blue-700"
                                >
                                    Edit
                                </button>
                                <button
                                    type="button"
                                    onclick="openDeleteCapaianModal({{ $capaian->id }}, '{{ addslashes($capaian->capaian_pembelajaran) }}')"
                                    class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-red-700"
                                >
                                    Hapus
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Modal Edit Capaian Pembelajaran -->
    <div id="editCapaianModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="w-full max-w-2xl rounded-lg bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">Edit Capaian Pembelajaran</h2>
                <button
                    onclick="closeEditCapaianModal()"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <form id="editCapaianForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit_capaian_pembelajaran" class="block text-sm font-medium text-gray-700">
                        Capaian Pembelajaran <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="edit_capaian_pembelajaran"
                        name="capaian_pembelajaran"
                        rows="4"
                        required
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan capaian pembelajaran"
                    ></textarea>
                </div>

                <div>
                    <label for="edit_urutan" class="block text-sm font-medium text-gray-700">
                        Urutan <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="edit_urutan"
                        name="urutan"
                        required
                        min="1"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    />
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button
                        type="button"
                        onclick="closeEditCapaianModal()"
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

    <!-- Modal Konfirmasi Hapus Capaian Pembelajaran -->
    <div id="deleteCapaianModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">Konfirmasi Hapus</h2>
                <button
                    onclick="closeDeleteCapaianModal()"
                    class="text-gray-400 hover:text-gray-600"
                >
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mb-6">
                <p class="text-sm text-gray-600">
                    Apakah Anda yakin ingin menghapus capaian pembelajaran berikut?
                </p>
                <p class="mt-2 text-sm font-semibold text-gray-900" id="deleteCapaianText"></p>
                <p class="mt-2 text-xs text-red-600">
                    Tindakan ini tidak dapat dibatalkan.
                </p>
            </div>

            <form id="deleteCapaianForm" method="POST" class="flex justify-end gap-3">
                @csrf
                @method('DELETE')
                <button
                    type="button"
                    onclick="closeDeleteCapaianModal()"
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

    <script>
        function openEditCapaianModal(id, capaianPembelajaran, urutan) {
            document.getElementById('edit_capaian_pembelajaran').value = capaianPembelajaran;
            document.getElementById('edit_urutan').value = urutan;
            document.getElementById('editCapaianForm').action = '{{ route('admin.courses.learning-outcomes.update', ':id') }}'.replace(':id', id);
            document.getElementById('editCapaianModal').classList.remove('hidden');
            document.getElementById('editCapaianModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeEditCapaianModal() {
            document.getElementById('editCapaianModal').classList.add('hidden');
            document.getElementById('editCapaianModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        function openDeleteCapaianModal(id, capaianPembelajaran) {
            document.getElementById('deleteCapaianText').textContent = capaianPembelajaran;
            document.getElementById('deleteCapaianForm').action = '{{ route('admin.courses.learning-outcomes.destroy', ':id') }}'.replace(':id', id);
            document.getElementById('deleteCapaianModal').classList.remove('hidden');
            document.getElementById('deleteCapaianModal').classList.add('flex');
            document.body.style.overflow = 'hidden';
        }

        function closeDeleteCapaianModal() {
            document.getElementById('deleteCapaianModal').classList.add('hidden');
            document.getElementById('deleteCapaianModal').classList.remove('flex');
            document.body.style.overflow = 'auto';
        }

        document.getElementById('editCapaianModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditCapaianModal();
            }
        });

        document.getElementById('deleteCapaianModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDeleteCapaianModal();
            }
        });

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeEditCapaianModal();
                closeDeleteCapaianModal();
            }
        });
    </script>
@endsection

