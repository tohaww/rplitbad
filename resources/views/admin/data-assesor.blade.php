@extends('layouts.admin')

@section('title', 'Data Assesor')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Data Assesor</h1>
                <p class="text-sm text-gray-600">Kelola data assesor (id manual & nama).</p>
            </div>
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-green-50 px-4 py-3 text-sm text-green-800 border border-green-200">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg bg-red-50 px-4 py-3 text-sm text-red-800 border border-red-200">
                <ul class="list-disc pl-5 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="flex justify-end">
            <button
                type="button"
                id="open-modal-assesor"
                data-mode="create"
                class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                + Tambah
            </button>
        </div>

        <!-- Modal Tambah Assesor -->
        <div
            id="modal-assesor"
            class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 px-4"
            aria-modal="true"
            role="dialog"
        >
            <div class="w-full max-w-2xl rounded-lg bg-white shadow-xl">
                <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                    <h2 class="text-lg font-semibold text-gray-900">Tambah Assesor</h2>
                    <button
                        type="button"
                        id="close-modal-assesor"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none"
                        aria-label="Tutup"
                    >
                        ✕
                    </button>
                </div>
                <form method="POST" action="{{ route('admin.data-assesor.store') }}" class="space-y-4 px-6 py-5" id="form-assesor">
                    @csrf
                    <input type="hidden" name="_method" id="method-assesor" value="POST">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">ID Assesor</label>
                            <input
                                type="text"
                                name="id_assesor"
                                id="input-id-assesor"
                                value="{{ old('id_assesor') }}"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama</label>
                            <input
                                type="text"
                                name="nama"
                                id="input-nama-assesor"
                                value="{{ old('nama') }}"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                required
                            />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Password</label>
                            <input
                                type="password"
                                name="password"
                                id="input-password-assesor"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-1 focus:ring-blue-500"
                                placeholder="Isi untuk set / ubah password"
                            />
                            <p class="mt-1 text-xs text-gray-500">Biarkan kosong untuk tidak mengubah password.</p>
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 pt-2">
                        <button
                            type="button"
                            id="cancel-modal-assesor"
                            class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="inline-flex items-center gap-2 rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        >
                            Simpan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="rounded-lg border border-gray-200 bg-white p-6 shadow-sm">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Daftar Assesor</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">ID Assesor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($assesor as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-3 text-sm font-medium text-gray-900">{{ $item->id_assesor }}</td>
                                <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-700">{{ $item->nama }}</td>
                                <td class="whitespace-nowrap px-6 py-3 text-sm text-gray-700">
                                    <div class="flex items-center gap-3">
                                        <button
                                            type="button"
                                            class="text-blue-600 hover:text-blue-800 font-semibold btn-edit-assesor"
                                            data-mode="edit"
                                            data-id="{{ $item->id_assesor }}"
                                            data-nama="{{ $item->nama }}"
                                            data-update-url="{{ route('admin.data-assesor.update', $item->id_assesor) }}"
                                        >
                                            Edit
                                        </button>
                                        <button
                                            type="button"
                                            class="text-red-600 hover:text-red-800 font-semibold btn-delete-assesor"
                                            data-delete-url="{{ route('admin.data-assesor.destroy', $item->id_assesor) }}"
                                        >
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data assesor.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <!-- Modal Hapus -->
    <div
        id="modal-delete-assesor"
        class="fixed inset-0 z-50 hidden flex items-center justify-center bg-black/50 px-4"
        aria-modal="true"
        role="dialog"
    >
        <div class="w-full max-w-md rounded-lg bg-white shadow-xl">
            <div class="flex items-center justify-between border-b border-gray-200 px-6 py-4">
                <h2 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h2>
                <button
                    type="button"
                    id="close-modal-delete-assesor"
                    class="text-gray-500 hover:text-gray-700 focus:outline-none"
                    aria-label="Tutup"
                >
                    ✕
                </button>
            </div>
            <form method="POST" id="form-delete-assesor" class="space-y-4 px-6 py-5">
                @csrf
                @method('DELETE')
                <p class="text-sm text-gray-700">Yakin ingin menghapus assesor ini?</p>
                <div class="flex justify-end gap-3 pt-2">
                    <button
                        type="button"
                        id="cancel-modal-delete-assesor"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300 focus:ring-offset-2"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                    >
                        Hapus
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('modal-assesor');
        const openBtn = document.getElementById('open-modal-assesor');
        const closeBtn = document.getElementById('close-modal-assesor');
        const cancelBtn = document.getElementById('cancel-modal-assesor');
        const form = document.getElementById('form-assesor');
        const methodInput = document.getElementById('method-assesor');
        const inputId = document.getElementById('input-id-assesor');
        const inputNama = document.getElementById('input-nama-assesor');
        const editButtons = document.querySelectorAll('.btn-edit-assesor');
        const deleteButtons = document.querySelectorAll('.btn-delete-assesor');
        const deleteModal = document.getElementById('modal-delete-assesor');
        const deleteForm = document.getElementById('form-delete-assesor');
        const deleteCloseBtn = document.getElementById('close-modal-delete-assesor');
        const deleteCancelBtn = document.getElementById('cancel-modal-delete-assesor');

        const openModal = () => modal?.classList.remove('hidden');
        const closeModal = () => modal?.classList.add('hidden');
        const openDeleteModal = () => deleteModal?.classList.remove('hidden');
        const closeDeleteModal = () => deleteModal?.classList.add('hidden');

        const setFormCreate = () => {
            form.action = "{{ route('admin.data-assesor.store') }}";
            methodInput.value = "POST";
            inputId.value = "";
            inputNama.value = "";
        };

        openBtn?.addEventListener('click', () => {
            setFormCreate();
            openModal();
        });

        editButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const id = btn.getAttribute('data-id') || '';
                const nama = btn.getAttribute('data-nama') || '';
                const updateUrl = btn.getAttribute('data-update-url') || '';

                form.action = updateUrl;
                methodInput.value = "PUT";
                inputId.value = id;
                inputNama.value = nama;
                openModal();
            });
        });

        closeBtn?.addEventListener('click', closeModal);
        cancelBtn?.addEventListener('click', closeModal);

        // Close when clicking outside content
        modal?.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        // Close on Esc
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !modal?.classList.contains('hidden')) {
                closeModal();
            }
        });

        deleteButtons.forEach((btn) => {
            btn.addEventListener('click', () => {
                const deleteUrl = btn.getAttribute('data-delete-url') || '';
                deleteForm.action = deleteUrl;
                openDeleteModal();
            });
        });

        deleteCloseBtn?.addEventListener('click', closeDeleteModal);
        deleteCancelBtn?.addEventListener('click', closeDeleteModal);

        deleteModal?.addEventListener('click', (e) => {
            if (e.target === deleteModal) {
                closeDeleteModal();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && !deleteModal?.classList.contains('hidden')) {
                closeDeleteModal();
            }
        });
    });
</script>
@endpush

