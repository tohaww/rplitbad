@extends('layouts.app')

@section('title', 'Pengajuan Perolehan Kredit')

@section('content')
    @php
        $user = Auth::user();
        $mahasiswa = \App\Models\Mahasiswa::where('user_id', $user->id)->first();
        $programStudi = \App\Models\ProgramStudi::all();
        
        // Hitung progress step
        $step1Completed = false; // Entri RPL(Mhs)
        $step2Completed = false; // Ajukan RPL(Mhs)
        $step3Completed = false; // Form Evaluasi (Mhs)
        $step4Completed = false; // Form Assesment (Asesor)
        $step5Completed = false; // Verifikasi Nilai (Prodi)
        $step6Completed = false; // Finish
        
        if ($draftPengajuan) {
            // Step 1: Entri RPL - jika ada draft pengajuan
            $step1Completed = true;
            
            // Step 2: Ajukan RPL - jika status bukan Draft
            if ($draftPengajuan->status && $draftPengajuan->status !== 'Draft') {
                $step2Completed = true;
                
                // Step 3: Form Evaluasi - cek apakah semua mata kuliah sudah diisi evaluasi
                $mataKuliah = $draftPengajuan->mata_kuliah ?? [];
                if (!empty($mataKuliah) && $mahasiswa) {
                    $allEvaluated = true;
                    foreach ($mataKuliah as $mk) {
                        $idMatkul = $mk['id_matkul'] ?? null;
                        if ($idMatkul) {
                            $hasEvaluasi = \App\Models\FormEvaluasi::where('mahasiswa_id', $mahasiswa->id_mahasiswa)
                                ->where('id_matkul', $idMatkul)
                                ->exists();
                            if (!$hasEvaluasi) {
                                $allEvaluated = false;
                                break;
                            }
                        }
                    }
                    $step3Completed = $allEvaluated;
                }
                
                // Step 4: Form Assesment - jika status sudah lebih dari "Sudah Diajukan"
                // Untuk sekarang, kita asumsikan jika sudah ada evaluasi, berarti bisa lanjut ke assessment
                // Atau bisa juga berdasarkan status tertentu
                if ($step3Completed && in_array($draftPengajuan->status, ['Diterima', 'Disetujui', 'Ditolak'])) {
                    $step4Completed = true;
                }
                
                // Step 5: Verifikasi Nilai - jika status "Diterima" atau "Disetujui"
                if (in_array($draftPengajuan->status, ['Diterima', 'Disetujui'])) {
                    $step5Completed = true;
                }
                
                // Step 6: Finish - jika status "Diterima" atau "Disetujui"
                if (in_array($draftPengajuan->status, ['Diterima', 'Disetujui'])) {
                    $step6Completed = true;
                }
            }
        }
        
        // Hitung progress percentage
        $totalSteps = 6;
        $completedSteps = ($step1Completed ? 1 : 0) + ($step2Completed ? 1 : 0) + ($step3Completed ? 1 : 0) + 
                          ($step4Completed ? 1 : 0) + ($step5Completed ? 1 : 0) + ($step6Completed ? 1 : 0);
        $progressPercentage = round(($completedSteps / $totalSteps) * 100);
    @endphp

    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#1b1b18]">Pengajuan Perolehan Kredit</h1>
                <div class="mt-2 flex items-center gap-2 text-sm text-gray-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Perolehan Kredit</span>
                </div>
            </div>
            <div class="text-sm text-gray-600">
                <span class="text-blue-600">Menu</span> / <span class="text-[#1b1b18]">Perolehan Kredit</span>
            </div>
        </div>

        <!-- Progress Tracker -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <h2 class="mb-4 text-lg font-semibold text-[#1b1b18]">Progress Pengajuan</h2>
            <div class="mb-4 flex flex-wrap gap-2 text-xs">
                <div class="rounded-lg px-3 py-1.5 {{ $step1Completed ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Entri RPL(Mhs)</div>
                <div class="rounded-lg px-3 py-1.5 {{ $step2Completed ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Ajukan RPL(Mhs)</div>
                <div class="rounded-lg px-3 py-1.5 {{ $step3Completed ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Form Evaluasi (Mhs)</div>
                <div class="rounded-lg px-3 py-1.5 {{ $step4Completed ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Form Assesment (Asesor)</div>
                <div class="rounded-lg px-3 py-1.5 {{ $step5Completed ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Verifikasi Nilai (Prodi)</div>
                <div class="rounded-lg px-3 py-1.5 {{ $step6Completed ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700' }}">Finish</div>
            </div>
            <div class="h-2 w-full rounded-full bg-gray-200">
                <div class="h-2 rounded-full bg-green-500" style="width: {{ $progressPercentage }}%"></div>
            </div>
        </div>

        <!-- Personal Information -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Nama</p>
                    <p class="text-sm font-medium text-[#1b1b18]">{{ $mahasiswa?->nama ?? $user->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Tempat, Tgl Lahir</p>
                    <p class="text-sm font-medium text-[#1b1b18]">
                        {{ $mahasiswa?->tempat_lahir ?? '-' }}, 
                        {{ $mahasiswa?->tanggal_lahir ? \Carbon\Carbon::parse($mahasiswa->tanggal_lahir)->format('d-m-Y') : '-' }}
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Alamat</p>
                    <p class="text-sm font-medium text-[#1b1b18]">
                        @if($mahasiswa)
                            {{ $mahasiswa->alamat_rumah ?? '-' }}
                            @if($mahasiswa->rt) RT {{ $mahasiswa->rt }} @endif
                            @if($mahasiswa->rw) RW {{ $mahasiswa->rw }} @endif
                            @if($mahasiswa->kelurahan_desa) {{ $mahasiswa->kelurahan_desa }} @endif
                            @if($mahasiswa->kecamatan) {{ $mahasiswa->kecamatan }} @endif
                            @if($mahasiswa->kab_kota) {{ $mahasiswa->kab_kota }} @endif
                            @if($mahasiswa->provinsi) {{ $mahasiswa->provinsi }} @endif
                        @else
                            -
                        @endif
                    </p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">No HP/No Telp</p>
                    <p class="text-sm font-medium text-[#1b1b18]">
                        {{ $mahasiswa?->telp_hp ?? '-' }}, {{ $mahasiswa?->telepon_fax ?? '-' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Warning Message -->
        <div id="warningMessage" class="rounded-lg bg-red-600 px-4 py-3 text-white">
            <p class="text-sm font-medium">Peringatan! Program Studi Masih Kosong, Silahkan Pilih Terlebih Dahulu...</p>
        </div>

        <!-- Instructions and Selection -->
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <p class="mb-4 text-sm text-gray-700">
                Untuk mengajukan mata kuliah pada RPL Perolehan kredit silakan memilih program studi terlebih dahulu, kemudian klik tombol <strong>Set Program Studi</strong>
            </p>

            <div id="programStudiSection" class="grid grid-cols-2 gap-4">
                <div>
                    <label for="program_studi" class="block text-sm font-medium text-gray-700 mb-1">
                        Program Studi
                    </label>
                    <select
                        id="program_studi"
                        name="program_studi"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 {{ (isset($draftPengajuan) && $draftPengajuan && $draftPengajuan->status !== 'Draft') ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                        {{ (isset($draftPengajuan) && $draftPengajuan && $draftPengajuan->status !== 'Draft') ? 'disabled' : '' }}
                    >
                        <option value="">Pilih Program Studi</option>
                        @foreach($programStudi as $prodi)
                            <option value="{{ $prodi->id }}" {{ isset($draftPengajuan) && $draftPengajuan->program_studi_id == $prodi->id ? 'selected' : '' }}>{{ $prodi->nama_prodi }}</option>
                        @endforeach
                    </select>
                </div>

                @if((!isset($draftPengajuan) || !$draftPengajuan) || (isset($draftPengajuan) && $draftPengajuan->status === 'Draft'))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Action
                    </label>
                    <button
                        type="button"
                        onclick="setProgramStudi()"
                        class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Set Program Studi
                    </button>
                </div>
                @endif
            </div>
        </div>

        <!-- Course Selection Section (Hidden by default) -->
        <div id="courseSelectionSection" class="hidden rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <p class="mb-4 text-sm text-gray-700">
                Pilih mata kuliah kemudian klik tombol <strong>Tambah Mata Kuliah</strong> lakukan hingga semua mata kuliah yang menurut Anda layak direkognisi dari pengalaman pekerjaan/pendidikan non formal atau informal telah ditambahkan. Jika sudah lengkap, silakan klik tombol <strong>Ajukan Pengakuan Mata Kuliah</strong>
            </p>

            <div class="mb-4 grid grid-cols-2 gap-4">
                <div>
                    <label for="mata_kuliah" class="block text-sm font-medium text-gray-700 mb-1">
                        Mata Kuliah
                    </label>
                    <select
                        id="mata_kuliah"
                        name="mata_kuliah"
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 {{ (isset($draftPengajuan) && $draftPengajuan && $draftPengajuan->status !== 'Draft') ? 'bg-gray-100 cursor-not-allowed' : '' }}"
                        {{ (isset($draftPengajuan) && $draftPengajuan && $draftPengajuan->status !== 'Draft') ? 'disabled' : '' }}
                    >
                        <option value="">-- Pilih Mata Kuliah --</option>
                    </select>
                </div>

                @if(!isset($draftPengajuan) || !$draftPengajuan || (isset($draftPengajuan) && $draftPengajuan->status === 'Draft'))
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Action
                    </label>
                    <button
                        type="button"
                        onclick="tambahMatkul()"
                        class="w-full rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                    >
                        Tambah Matkul
                    </button>
                </div>
                @endif
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-200">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">No</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Kode Matkul</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Matakuliah</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Sks</th>
                            <th class="border border-gray-300 px-4 py-3 text-left text-sm font-medium text-gray-700">Action</th>
                        </tr>
                    </thead>
                    <tbody id="matkulTableBody" class="divide-y divide-gray-200">
                        <tr>
                            <td colspan="5" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                                Belum ada data matakuliah
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="border border-gray-300 px-4 py-3 text-right text-sm font-medium text-gray-700">
                                Total SKS
                            </td>
                            <td id="totalSks" class="border border-gray-300 px-4 py-3 text-sm font-medium text-gray-900">0</td>
                            <td class="border border-gray-300 px-4 py-3"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            @if(!isset($draftPengajuan) || !$draftPengajuan || (isset($draftPengajuan) && $draftPengajuan->status === 'Draft'))
            <div class="mt-6 flex justify-center">
                <button
                    type="button"
                    onclick="ajukanPengakuan()"
                    class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Ajukan Pengakuan Matakuliah
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Konfirmasi Pengajuan -->
    <div id="confirmModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-transparent" onclick="if(event.target === this) closeConfirmModal()">
        <div class="mx-4 w-full max-w-md rounded-lg bg-white p-6 shadow-xl" onclick="event.stopPropagation()">
            <!-- Icon Exclamation -->
            <div class="mb-4 flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-orange-100">
                    <svg class="h-10 w-10 text-orange-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
            </div>

            <!-- Title and Message -->
            <div class="mb-6 text-center">
                <h3 class="mb-2 text-xl font-bold text-gray-900">Pesan !</h3>
                <p class="text-sm text-gray-700">Apakah benar Anda akan mengajukan semua mata kuliah ini?</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-3">
                <button
                    type="button"
                    onclick="submitPengajuan()"
                    class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Ya
                </button>
                <button
                    type="button"
                    onclick="closeConfirmModal()"
                    class="rounded-lg bg-red-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    Batal
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Konfirmasi Hapus Mata Kuliah -->
    <div id="deleteModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-transparent" onclick="if(event.target === this) closeDeleteModal()">
        <div class="mx-4 w-full max-w-md rounded-lg bg-white p-6 shadow-xl" onclick="event.stopPropagation()">
            <!-- Icon Exclamation -->
            <div class="mb-4 flex justify-center">
                <div class="flex h-16 w-16 items-center justify-center rounded-full border-2 border-orange-300 bg-orange-50">
                    <span class="text-4xl font-bold text-orange-600">!</span>
                </div>
            </div>

            <!-- Message -->
            <div class="mb-6 text-center">
                <p class="text-base font-semibold text-gray-900">Apakah Anda Yakin Hapus Data?</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-3">
                <button
                    type="button"
                    onclick="confirmDeleteMatkul()"
                    class="rounded-lg bg-blue-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Ya
                </button>
                <button
                    type="button"
                    onclick="closeDeleteModal()"
                    class="rounded-lg bg-red-600 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
                >
                    Tidak
                </button>
            </div>
        </div>
    </div>

    <!-- Modal Sukses Pengajuan -->
    <div id="successModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-transparent" onclick="if(event.target === this) closeSuccessModal()">
        <div class="mx-4 w-full max-w-md rounded-lg bg-white p-6 shadow-xl" onclick="event.stopPropagation()">
            <!-- Message -->
            <div class="mb-6 text-center">
                <p class="text-base font-medium text-gray-900" id="successMessage">Pengajuan berhasil dikirim!</p>
            </div>

            <!-- Action Button -->
            <div class="flex justify-end">
                <button
                    type="button"
                    onclick="closeSuccessModal()"
                    class="rounded-lg border-2 border-purple-600 bg-purple-500 px-6 py-2.5 text-sm font-medium text-white transition hover:bg-purple-600 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2"
                >
                    OK
                </button>
            </div>
        </div>
    </div>

    <script>
        let selectedCourses = [];
        let currentPengajuanId = @json($draftPengajuan->id ?? null);
        let draftMataKuliah = @json($draftPengajuan->mata_kuliah ?? []);
        let pengajuanStatus = @json($draftPengajuan->status ?? null);
        let isReadOnly = pengajuanStatus && pengajuanStatus !== 'Draft';
        
        // Debug: Log initial data
        console.log('Initial currentPengajuanId:', currentPengajuanId);
        console.log('Initial draftMataKuliah:', draftMataKuliah);
        console.log('Pengajuan Status:', pengajuanStatus);
        console.log('Is Read Only:', isReadOnly);

        function setProgramStudi() {
            const programStudi = document.getElementById('program_studi').value;
            const warningMessage = document.getElementById('warningMessage');
            const courseSelectionSection = document.getElementById('courseSelectionSection');

            if (!programStudi) {
                warningMessage.classList.remove('hidden');
                courseSelectionSection.classList.add('hidden');
                alert('Silakan pilih Program Studi terlebih dahulu!');
                return;
            }

            // Save to database
            fetch('{{ route("perolehan-kredit.store-draft") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    program_studi_id: programStudi
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    currentPengajuanId = data.pengajuan_id;
                    
                    // Hide warning if program studi is selected
                    warningMessage.classList.add('hidden');
                    
                    // Langsung kunci program studi setelah berhasil diset
                    lockProgramStudi();

                    // Load courses for selected program studi
                    loadCourses(programStudi);
                } else {
                    alert('Gagal menyimpan program studi: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat menyimpan program studi. Silakan coba lagi.');
            });
        }

        function loadCourses(prodiId) {
            return fetch(`{{ route('perolehan-kredit.get-courses') }}?prodi_id=${prodiId}`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                const mataKuliahSelect = document.getElementById('mata_kuliah');
                mataKuliahSelect.innerHTML = '<option value="">-- Pilih Mata Kuliah --</option>';
                
                data.courses.forEach(course => {
                    const option = document.createElement('option');
                    option.value = course.id_matkul;
                    option.textContent = `${course.kode_matkul} - ${course.nama_matkul} (${course.sks} SKS)`;
                    option.setAttribute('data-sks', course.sks);
                    option.setAttribute('data-kode', course.kode_matkul);
                    option.setAttribute('data-nama', course.nama_matkul);
                    mataKuliahSelect.appendChild(option);
                });

                // Show course selection section
                document.getElementById('courseSelectionSection').classList.remove('hidden');
                
                return data;
            })
            .catch(error => {
                console.error('Error loading courses:', error);
                alert('Gagal memuat mata kuliah. Silakan coba lagi.');
                throw error;
            });
        }

        function lockProgramStudi() {
            const programStudiSelect = document.getElementById('program_studi');
            const programStudiSection = document.getElementById('programStudiSection');

            if (programStudiSelect) {
                programStudiSelect.disabled = true;
                programStudiSelect.classList.add('bg-gray-100', 'cursor-not-allowed');
            }

            if (programStudiSection) {
                const actionDiv = programStudiSection.querySelector('div:last-child');
                if (actionDiv && actionDiv.querySelector('button[onclick="setProgramStudi()"]')) {
                    actionDiv.style.display = 'none';
                }
                programStudiSection.classList.remove('grid-cols-2');
                programStudiSection.classList.add('grid-cols-1');
            }
        }

        function tambahMatkul() {
            const mataKuliahSelect = document.getElementById('mata_kuliah');
            const selectedOption = mataKuliahSelect.options[mataKuliahSelect.selectedIndex];
            
            if (!mataKuliahSelect.value) {
                alert('Silakan pilih mata kuliah terlebih dahulu!');
                return;
            }

            const courseId = mataKuliahSelect.value;
            const courseKode = selectedOption.getAttribute('data-kode');
            const courseNama = selectedOption.getAttribute('data-nama');
            const courseSks = selectedOption.getAttribute('data-sks');

            // Check if course already added
            if (selectedCourses.find(c => c.id === courseId)) {
                alert('Mata kuliah ini sudah ditambahkan!');
                return;
            }

            // Add to selected courses
            const newCourse = {
                id: courseId,
                kode: courseKode,
                nama: courseNama,
                sks: parseInt(courseSks),
                kode_matkul: courseKode
            };
            
            selectedCourses.push(newCourse);

            // Update table
            updateTable();
            
            // Save to database if pengajuan_id exists
            if (currentPengajuanId) {
                fetch('{{ route("perolehan-kredit.add-matkul") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        pengajuan_id: currentPengajuanId,
                        id_matkul: courseId,
                        kode_matkul: courseKode,
                        nama_matkul: courseNama
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update total SKS if returned
                        if (data.total_sks !== undefined) {
                            document.getElementById('totalSks').textContent = data.total_sks;
                        }

                        // Kunci program studi setelah berhasil menambah matkul
                        lockProgramStudi();
                    } else {
                        // Remove from selectedCourses if save failed
                        const index = selectedCourses.findIndex(c => c.id === courseId);
                        if (index > -1) {
                            selectedCourses.splice(index, 1);
                            updateTable();
                        }
                        alert('Gagal menyimpan: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Remove from selectedCourses if save failed
                    const index = selectedCourses.findIndex(c => c.id === courseId);
                    if (index > -1) {
                        selectedCourses.splice(index, 1);
                        updateTable();
                    }
                    alert('Terjadi kesalahan saat menyimpan mata kuliah. Silakan coba lagi.');
                });
            }
            
            // Reset select
            mataKuliahSelect.value = '';
        }

        function updateTable() {
            const tbody = document.getElementById('matkulTableBody');
            const totalSksElement = document.getElementById('totalSks');
            
            if (selectedCourses.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="5" class="border border-gray-300 px-4 py-8 text-center text-sm text-gray-500">
                            Belum ada data matakuliah
                        </td>
                    </tr>
                `;
                totalSksElement.textContent = '0';
                return;
            }

            let totalSks = 0;
            const showDeleteButton = !isReadOnly;
            tbody.innerHTML = selectedCourses.map((course, index) => {
                totalSks += course.sks;
                return `
                    <tr>
                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">${index + 1}</td>
                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">${course.kode_matkul}</td>
                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">${course.nama}</td>
                        <td class="border border-gray-300 px-4 py-3 text-sm text-gray-900">${course.sks}</td>
                        <td class="border border-gray-300 px-4 py-3">
                            ${showDeleteButton ? `
                            <button
                                onclick="hapusMatkul('${course.id}')"
                                class="rounded-lg bg-red-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-red-700"
                            >
                                Hapus
                            </button>
                            ` : '<span class="text-sm text-gray-500">-</span>'}
                        </td>
                    </tr>
                `;
            }).join('');

            totalSksElement.textContent = totalSks;
        }

        let courseToDelete = null;

        function hapusMatkul(courseId) {
            courseToDelete = courseId;
            openDeleteModal();
        }

        function openDeleteModal() {
            document.getElementById('deleteModal').classList.remove('hidden');
            document.getElementById('deleteModal').classList.add('flex');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.add('hidden');
            document.getElementById('deleteModal').classList.remove('flex');
            courseToDelete = null;
        }

        function openSuccessModal() {
            document.getElementById('successModal').classList.remove('hidden');
            document.getElementById('successModal').classList.add('flex');
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.add('hidden');
            document.getElementById('successModal').classList.remove('flex');
            // Reset form
            selectedCourses = [];
            updateTable();
            document.getElementById('program_studi').value = '';
            document.getElementById('courseSelectionSection').classList.add('hidden');
            document.getElementById('warningMessage').classList.remove('hidden');
            // Redirect to course submission page
            window.location.href = '{{ route("course-submission") }}';
        }

        function confirmDeleteMatkul() {
            if (!courseToDelete) {
                closeDeleteModal();
                return;
            }

            const courseId = courseToDelete;
            closeDeleteModal();

            // Remove from selected courses
            selectedCourses = selectedCourses.filter(c => c.id !== courseId);
            updateTable();

            // Save to database if pengajuan_id exists
            if (currentPengajuanId) {
                fetch('{{ route("perolehan-kredit.remove-matkul") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        pengajuan_id: currentPengajuanId,
                        id_matkul: courseId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update total SKS if returned
                        if (data.total_sks !== undefined) {
                            document.getElementById('totalSks').textContent = data.total_sks;
                        }
                    } else {
                        alert('Gagal menghapus: ' + data.message);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus mata kuliah. Silakan coba lagi.');
                });
            }
        }

        function ajukanPengakuan() {
            if (selectedCourses.length === 0) {
                alert('Silakan tambahkan mata kuliah terlebih dahulu!');
                return;
            }

            const programStudi = document.getElementById('program_studi').value;
            if (!programStudi) {
                alert('Silakan pilih Program Studi terlebih dahulu!');
                return;
            }

            // Show modal
            openConfirmModal();
        }

        function openConfirmModal() {
            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmModal').classList.add('flex');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            document.getElementById('confirmModal').classList.remove('flex');
        }

        function submitPengajuan() {
            const programStudi = document.getElementById('program_studi').value;
            
            // Prepare data
            const courses = selectedCourses.map(course => ({
                id_matkul: course.id,
                kode_matkul: course.kode_matkul
            }));

            const data = {
                program_studi_id: programStudi,
                courses: courses
            };

            // Add pengajuan_id if exists (from draft)
            if (currentPengajuanId) {
                data.pengajuan_id = currentPengajuanId;
            }

            // Close modal first
            closeConfirmModal();

            // Send to backend
            fetch('{{ route("perolehan-kredit.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success modal
                    document.getElementById('successMessage').textContent = data.message || 'Pengajuan berhasil dikirim!';
                    openSuccessModal();
                } else {
                    alert('Gagal: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat mengirim pengajuan. Silakan coba lagi.');
            });
        }

        // Hide warning if program studi is selected
        document.getElementById('program_studi').addEventListener('change', function() {
            const programStudi = this.value;
            const warningMessage = document.getElementById('warningMessage');
            const courseSelectionSection = document.getElementById('courseSelectionSection');
            
            if (programStudi) {
                warningMessage.classList.add('hidden');
            } else {
                warningMessage.classList.remove('hidden');
                courseSelectionSection.classList.add('hidden');
                selectedCourses = [];
                updateTable();
            }
        });

        // Load draft data on page load
        document.addEventListener('DOMContentLoaded', function() {
            @if(isset($draftPengajuan) && $draftPengajuan)
                console.log('Draft pengajuan found:', @json($draftPengajuan));
                console.log('Draft mata kuliah:', draftMataKuliah);
                
                const programStudi = document.getElementById('program_studi').value;
                
                if (programStudi) {
                    // Hide warning
                    document.getElementById('warningMessage').classList.add('hidden');

                    // Selalu kunci program studi jika sudah ada draft (status Draft maupun sudah diajukan)
                    lockProgramStudi();
                    
                    // Load courses first
                    loadCourses(programStudi).then((data) => {
                        console.log('Courses loaded:', data);
                        console.log('Draft mata kuliah to load:', draftMataKuliah);
                        
                        // Load mata kuliah from draft
                        if (draftMataKuliah && Array.isArray(draftMataKuliah) && draftMataKuliah.length > 0) {
                            selectedCourses = draftMataKuliah.map(mk => {
                                console.log('Processing mata kuliah:', mk);
                                
                                // Use data from draft if available (new structure with id_matkul and nama_matkul)
                                if (mk.id_matkul) {
                                    // Find course to get SKS
                                    const course = data.courses.find(c => c.id_matkul === mk.id_matkul);
                                    if (course) {
                                        return {
                                            id: mk.id_matkul,
                                            kode: mk.kode_matkul || course.kode_matkul,
                                            nama: mk.nama_matkul || course.nama_matkul,
                                            sks: parseInt(course.sks),
                                            kode_matkul: mk.kode_matkul || course.kode_matkul
                                        };
                                    } else {
                                        console.warn('Course not found for id_matkul:', mk.id_matkul);
                                    }
                                }
                                return null;
                            }).filter(c => c !== null);
                            
                            console.log('Selected courses after loading:', selectedCourses);
                            updateTable();
                        } else {
                            console.log('No mata kuliah to load or empty array');
                        }
                    }).catch(error => {
                        console.error('Error loading draft data:', error);
                    });
                } else {
                    console.log('No program studi selected');
                }
            @else
                console.log('No draft pengajuan found');
            @endif
        });
    </script>
@endsection

