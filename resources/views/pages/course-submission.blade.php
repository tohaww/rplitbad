@extends('layouts.app')

@section('title', 'Pengajuan Matkul')

@section('content')
    @if(session('success'))
        <div class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mb-4 rounded-lg bg-red-100 border border-red-400 text-red-700 px-4 py-3">
            {{ session('error') }}
        </div>
    @endif

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#1b1b18]">Pengajuan Pengakuan Matakuliah</h1>
                <div class="mt-2 flex items-center gap-2 text-sm text-gray-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Pengajuan Pengakuan Matakuliah</span>
                </div>
            </div>
            <div class="text-sm text-gray-600">
                <span class="text-blue-600">Menu</span> / <span class="text-[#1b1b18]">Pengajuan Pengakuan Matakuliah</span>
            </div>
        </div>

        <!-- Section 1: Perolehan Kredit -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <div class="mb-4 rounded-lg bg-teal-600 px-4 py-3">
                <h2 class="text-lg font-semibold text-white">Perolehan Kredit</h2>
            </div>
            
            <p class="mb-4 text-sm text-gray-700">
                Perolehan Kredit yaitu pengakuan Capaian Pembelajaran (CP) secara parsial yang dilakukan melalui pengakuan hasil belajar yang diperoleh dari Pendidikan non formal atau informal, dan/atau pengalaman kerja setelah lulus jenjang Pendidikan menengah atau bentuk lain yang sederajat. @if($perolehanKredit->count() == 0)Silakan klik tombol <strong>Tambah Pengajuan</strong> untuk menambahkan pengajuan baru.@endif
            </p>

            @if($perolehanKredit->count() == 0)
            <div class="mb-4">
                <a
                    href="{{ route('perolehan-kredit.create') }}"
                    onclick="return handleTambahPengajuan(event)"
                    class="inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Tambah Pengajuan
                </a>
            </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">No</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">No Bukti</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Tanggal</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Program Studi</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if($perolehanKredit->count() > 0)
                            @foreach ($perolehanKredit as $index => $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $index + 1 }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->no_bukti }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->tanggal->format('d/m/Y') }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->programStudi->nama_prodi ?? '-' }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-blue-600">{{ $item->status }}</td>
                                    <td class="border border-gray-300 px-4 py-3">
                                        <div class="flex gap-2">
                                            <a href="{{ route('perolehan-kredit.create', ['id' => $item->id]) }}" class="inline-block rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700">
                                                Matkul
                                            </a>
                                            @if($item->status === 'Draft')
                                            <form action="{{ route('perolehan-kredit.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini?');" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada data pengajuan
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Section 2: Transfer Kredit -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <div class="mb-4 rounded-lg bg-teal-600 px-4 py-3">
                <h2 class="text-lg font-semibold text-white">Transfer Kredit</h2>
            </div>
            
            <p class="mb-4 text-sm text-gray-700">
                Transfer Kredit yaitu pengakuan Capaian Pembelajaran (CP) secara parsial yang dilakukan melalui pengakuan hasil belajar yang diperoleh dari program studi pada perguruan tinggi sebelumnya. @php $hasSubmitted = isset($transferKredit) && $transferKredit->contains('status', 'Sudah Diajukan'); @endphp @if(!$hasSubmitted)Silakan klik tombol <strong>Tambah Pengajuan</strong> untuk menambahkan pengajuan baru.@endif
            </p>

            @php
                $hasSubmitted = isset($transferKredit) && $transferKredit->contains('status', 'Sudah Diajukan');
            @endphp

            @if(!$hasSubmitted)
                <div class="mb-4">
                    <a
                        href="{{ route('transfer-kredit.create') }}"
                        onclick="return handleTambahPengajuan(event)"
                        class="inline-block rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Tambah Pengajuan
                    </a>
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">No</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">No Bukti</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Tanggal</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">PT Asal</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Jenjang PT Asal</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Prodi PT Asal</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">NIM Asal</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Prodi Tertuju</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Status</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @if(isset($transferKredit) && $transferKredit->count() > 0)
                            @php $no = 1; @endphp
                            @foreach ($transferKredit as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $no++ }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">-</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->created_at?->format('d-m-Y') ?? '-' }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->perguruan_tinggi_asal }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->jenjang_pendidikan }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->program_studi_asal }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->nim_asal }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">{{ $item->program_studi_tertuju }}</td>
                                    <td class="border border-gray-300 px-4 py-3 text-sm text-blue-600">{{ $item->status ?? 'Draft' }}</td>
                                    <td class="border border-gray-300 px-4 py-3">
                                        <div class="flex gap-2">
                                            <a href="{{ route('transfer-kredit.create') }}" class="inline-block rounded-lg bg-green-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-green-700">
                                                Matkul
                                            </a>
                                            @if($item->status !== 'Sudah Diajukan')
                                                <form action="{{ route('transfer-kredit.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan transfer kredit ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="10" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                                    Belum ada data pengajuan
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Modal: Wajib Lengkapi Identitas Diri --}}
    <div
        id="identityModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40"
        aria-hidden="true"
    >
        <div class="mx-4 w-full max-w-md rounded-2xl bg-white p-6 shadow-xl">
            <div class="flex items-center gap-3">
                <div class="flex h-12 w-12 items-center justify-center rounded-full bg-orange-100">
                    <svg class="h-7 w-7 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M4.93 19.07a10 10 0 1114.14 0A10 10 0 014.93 19.07z" />
                    </svg>
                </div>
                <div>
                    <h3 id="identityModalTitle" class="text-base font-semibold text-[#1b1b18]">Lengkapi Identitas Diri</h3>
                    <p id="identityModalBody" class="mt-1 text-sm text-gray-600">
                        Sebelum mengajukan mata kuliah, Anda wajib melengkapi data identitas diri terlebih dahulu.
                    </p>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <button
                    type="button"
                    onclick="closeIdentityModal()"
                    class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-300"
                >
                    Nanti Saja
                </button>
                <button
                    type="button"
                    onclick="goToIdentity()"
                    class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    Lengkapi Sekarang
                </button>
            </div>
        </div>
    </div>

    <script>
        const needsIdentity = @json($needsIdentity ?? false);
        const needsDocuments = @json($needsDocuments ?? false);
        const identityUrl = @json(route('identity.create'));
        const uploadDocumentsUrl = @json(route('profile'));

        function handleTambahPengajuan(event) {
            if (!needsIdentity && !needsDocuments) {
                return true; // identitas sudah lengkap, lanjut ke link
            }

            event.preventDefault();
            openIdentityModal();
            return false;
        }

        function openIdentityModal() {
            const modal = document.getElementById('identityModal');
            const title = document.getElementById('identityModalTitle');
            const body = document.getElementById('identityModalBody');

            if (needsIdentity) {
                if (title) title.textContent = 'Lengkapi Identitas Diri';
                if (body) body.textContent = 'Sebelum mengajukan mata kuliah, Anda wajib melengkapi data identitas diri terlebih dahulu.';
            } else if (needsDocuments) {
                if (title) title.textContent = 'Lengkapi Upload Dokumen';
                if (body) body.textContent = 'Identitas diri Anda sudah lengkap. Sebelum mengajukan mata kuliah, silakan upload dokumen (foto, KTP, KK, akta) terlebih dahulu di menu Profil.';
            }

            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeIdentityModal() {
            const modal = document.getElementById('identityModal');
            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function goToIdentity() {
            if (needsIdentity) {
                window.location.href = identityUrl;
                return;
            }

            if (needsDocuments) {
                window.location.href = uploadDocumentsUrl;
                return;
            }
        }
    </script>
@endsection
