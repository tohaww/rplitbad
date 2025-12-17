@extends('layouts.admin')

@section('title', 'Kode Referensi')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Kode Referensi</h1>
            <button
                onclick="openAddModal()"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                + Tambah Kode Referensi
            </button>
        </div>

        @if (session('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 p-4 text-sm text-green-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama Referensi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Kode Referensi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($kodeReferensi as $index => $kode)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $kode->nama_referensi }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $kode->kode_referensi }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <button
                                            onclick="openEditModal({{ $kode->id }}, '{{ $kode->nama_referensi }}', '{{ $kode->kode_referensi }}')"
                                            class="rounded-lg bg-yellow-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-yellow-700"
                                        >
                                            Edit
                                        </button>
                                        <form
                                            action="{{ route('admin.kode-referensi.destroy', $kode->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus kode referensi ini?');"
                                            class="inline"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="rounded-lg bg-red-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-red-700"
                                            >
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-sm text-gray-500">
                                    Tidak ada data kode referensi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Tambah Kode Referensi</h2>
            <form action="{{ route('admin.kode-referensi.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="nama_referensi" class="mb-1 block text-sm font-medium text-gray-700">Nama Referensi</label>
                        <input
                            type="text"
                            id="nama_referensi"
                            name="nama_referensi"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                        @error('nama_referensi')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="kode_referensi" class="mb-1 block text-sm font-medium text-gray-700">Kode Referensi</label>
                        <input
                            type="text"
                            id="kode_referensi"
                            name="kode_referensi"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                        @error('kode_referensi')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button
                        type="button"
                        onclick="closeAddModal()"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-lg">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Edit Kode Referensi</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_nama_referensi" class="mb-1 block text-sm font-medium text-gray-700">Nama Referensi</label>
                        <input
                            type="text"
                            id="edit_nama_referensi"
                            name="nama_referensi"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label for="edit_kode_referensi" class="mb-1 block text-sm font-medium text-gray-700">Kode Referensi</label>
                        <input
                            type="text"
                            id="edit_kode_referensi"
                            name="kode_referensi"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button
                        type="button"
                        onclick="closeEditModal()"
                        class="rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700"
                    >
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openAddModal() {
            document.getElementById('addModal').classList.remove('hidden');
            document.getElementById('addModal').classList.add('flex');
        }

        function closeAddModal() {
            document.getElementById('addModal').classList.add('hidden');
            document.getElementById('addModal').classList.remove('flex');
        }

        function openEditModal(id, namaReferensi, kodeReferensi) {
            document.getElementById('editForm').action = '{{ route('admin.kode-referensi.update', ':id') }}'.replace(':id', id);
            document.getElementById('edit_nama_referensi').value = namaReferensi;
            document.getElementById('edit_kode_referensi').value = kodeReferensi;
            document.getElementById('editModal').classList.remove('hidden');
            document.getElementById('editModal').classList.add('flex');
        }

        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
            document.getElementById('editModal').classList.remove('flex');
        }

        // Close modal when clicking outside
        document.getElementById('addModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddModal();
            }
        });

        document.getElementById('editModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeEditModal();
            }
        });
    </script>
@endsection

