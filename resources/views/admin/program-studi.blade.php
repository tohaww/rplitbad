@extends('layouts.admin')

@section('title', 'Program Studi')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Program Studi</h1>
            <a
                href="{{ route('admin.program-studi.create') }}"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                + Tambah Prodi
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

        <div class="rounded-lg border border-gray-200 bg-white shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                ID
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Kode Prodi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Nama Prodi
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($programStudis as $prodi)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                                    {{ $prodi->id }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">
                                    {{ $prodi->kode_prodi }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">
                                    {{ $prodi->nama_prodi }}
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <button
                                            type="button"
                                            onclick="openEditModal('{{ $prodi->id }}', '{{ $prodi->kode_prodi }}', '{{ addslashes($prodi->nama_prodi) }}')"
                                            class="rounded-lg bg-blue-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-blue-700"
                                        >
                                            Edit
                                        </button>
                                        <form action="{{ route('admin.program-studi.destroy', $prodi->id) }}" method="POST" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus program studi ini?');">
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
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    Belum ada data program studi.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <!-- Modal Form Edit Prodi -->
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 shadow-xl">
            <div class="mb-4 flex items-center justify-between">
                <h2 class="text-xl font-semibold text-gray-900">Edit Program Studi</h2>
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
                    <label for="edit_kode_prodi" class="block text-sm font-medium text-gray-700">
                        Kode Prodi
                    </label>
                    <input
                        type="text"
                        id="edit_kode_prodi"
                        name="kode_prodi"
                        readonly
                        class="mt-1 block w-full rounded-lg border border-gray-300 bg-gray-100 px-3 py-2 text-sm text-gray-500"
                    />
                </div>

                <div>
                    <label for="edit_nama_prodi" class="block text-sm font-medium text-gray-700">
                        Nama Prodi <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="edit_nama_prodi"
                        name="nama_prodi"
                        required
                        maxlength="255"
                        class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Contoh: Teknik Informatika"
                    />
                    @error('nama_prodi')
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
        function openEditModal(id, kodeProdi, namaProdi) {
            try {
                console.log('Opening edit modal for:', id);
                
                const editId = document.getElementById('edit_id');
                const editKodeProdi = document.getElementById('edit_kode_prodi');
                const editNamaProdi = document.getElementById('edit_nama_prodi');
                const editForm = document.getElementById('editForm');
                const editModal = document.getElementById('editModal');
                
                if (!editId || !editKodeProdi || !editNamaProdi || !editForm || !editModal) {
                    console.error('Modal elements not found!');
                    alert('Error: Modal elements not found. Please refresh the page.');
                    return;
                }
                
                editId.value = id || '';
                editKodeProdi.value = kodeProdi || '';
                editNamaProdi.value = namaProdi || '';
                
                const updateUrl = '{{ route('admin.program-studi.update', ':id') }}'.replace(':id', id);
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
            }
        });
    </script>
@endsection

