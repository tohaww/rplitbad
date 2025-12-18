@extends('layouts.admin')

@section('title', 'Data Pengguna - Asesor')

@section('content')
    <div class="space-y-6">
        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-gray-900">Data Pengguna - Asesor</h1>
            <button
                onclick="openAddModal()"
                class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            >
                + Tambah Akun Asesor
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
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse ($users as $index => $user)
                            <tr class="hover:bg-gray-50">
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $index + 1 }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-900">{{ $user->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-500">{{ $user->email }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <span class="inline-flex rounded-full bg-blue-100 px-2 py-1 text-xs font-semibold text-blue-800">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-sm">
                                    <div class="flex items-center gap-2">
                                        <button
                                            onclick="openEditModal({{ $user->id_user }}, '{{ addslashes($user->name) }}', '{{ $user->email }}')"
                                            class="rounded-lg bg-yellow-600 px-3 py-1.5 text-xs font-medium text-white transition hover:bg-yellow-700"
                                        >
                                            Edit
                                        </button>
                                        <form
                                            action="{{ route('admin.users.destroy', $user->id_user) }}"
                                            method="POST"
                                            onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun asesor ini?');"
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
                                <td colspan="5" class="px-6 py-8 text-center text-sm text-gray-500">
                                    Tidak ada data akun asesor
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Modal -->
    <div id="addModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-transparent">
        <div class="w-full max-w-md rounded-lg bg-white bg-opacity-95 backdrop-blur-sm p-6 shadow-lg">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Tambah Akun Asesor</h2>
            <form action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label for="name" class="mb-1 block text-sm font-medium text-gray-700">Nama</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            required
                            value="{{ old('name') }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                        @error('name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            required
                            value="{{ old('email') }}"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="mb-1 block text-sm font-medium text-gray-700">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                        @error('password')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">Konfirmasi Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
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
    <div id="editModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-transparent">
        <div class="w-full max-w-md rounded-lg bg-white bg-opacity-95 backdrop-blur-sm p-6 shadow-lg">
            <h2 class="mb-4 text-lg font-semibold text-gray-900">Edit Akun Asesor</h2>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="space-y-4">
                    <div>
                        <label for="edit_name" class="mb-1 block text-sm font-medium text-gray-700">Nama</label>
                        <input
                            type="text"
                            id="edit_name"
                            name="name"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label for="edit_email" class="mb-1 block text-sm font-medium text-gray-700">Email</label>
                        <input
                            type="email"
                            id="edit_email"
                            name="email"
                            required
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label for="edit_password" class="mb-1 block text-sm font-medium text-gray-700">Password Baru (Kosongkan jika tidak ingin mengubah)</label>
                        <input
                            type="password"
                            id="edit_password"
                            name="password"
                            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>
                    <div>
                        <label for="edit_password_confirmation" class="mb-1 block text-sm font-medium text-gray-700">Konfirmasi Password Baru</label>
                        <input
                            type="password"
                            id="edit_password_confirmation"
                            name="password_confirmation"
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
            // Reset form
            document.querySelector('#addModal form').reset();
        }

        function openEditModal(id, name, email) {
            document.getElementById('editForm').action = '{{ route('admin.users.update', ':id') }}'.replace(':id', id);
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_password').value = '';
            document.getElementById('edit_password_confirmation').value = '';
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
