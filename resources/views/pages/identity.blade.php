@extends('layouts.app')

@section('title', 'Lengkapi Identitas Diri')

@section('content')
    <div class="space-y-6">
        <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-lg">
            <div class="mb-6 flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-[#1b1b18]">Lengkapi Identitas Diri</h1>
                <div class="flex items-center gap-3">
                    @if($mahasiswa)
                        <button
                            onclick="editDataRiri()"
                            class="rounded-lg bg-yellow-600 px-4 py-2 text-sm font-medium text-white transition hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 focus:ring-offset-2"
                        >
                            Edit Data Riri
                        </button>
                    @endif
                    <a
                        href="{{ route('profile') }}"
                        class="rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Kembali
                    </a>
                </div>
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

            <form action="{{ route('identity.store') }}" method="POST" class="space-y-6">
                @csrf

                <div>
                    <label for="asal_perguruan_tinggi" class="block text-sm font-medium text-gray-700 mb-1">
                        Asal Perguruan Tinggi <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="asal_perguruan_tinggi"
                        name="asal_perguruan_tinggi"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                    >
                        <option value="">-- Pilih Asal Perguruan Tinggi --</option>
                        @foreach($asalPerguruanTinggi as $apt)
                            <option value="{{ $apt->nama }}" {{ old('asal_perguruan_tinggi', $mahasiswa->asal_perguruan_tinggi ?? '') == $apt->nama ? 'selected' : '' }}>
                                {{ $apt->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">
                        Nama <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="nama"
                        name="nama"
                        value="{{ old('nama', $mahasiswa->nama ?? Auth::user()->name ?? '') }}"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan nama lengkap"
                    />
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="tempat_lahir" class="block text-sm font-medium text-gray-700 mb-1">
                            Tempat Lahir <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="tempat_lahir"
                            name="tempat_lahir"
                            value="{{ old('tempat_lahir', $mahasiswa->tempat_lahir ?? '') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Contoh: Jakarta"
                        />
                    </div>

                    <div>
                        <label for="tanggal_lahir" class="block text-sm font-medium text-gray-700 mb-1">
                            Tanggal Lahir <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="date"
                            id="tanggal_lahir"
                            name="tanggal_lahir"
                            value="{{ old('tanggal_lahir', $mahasiswa->tanggal_lahir ?? '') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="jenis_kelamin" class="block text-sm font-medium text-gray-700 mb-1">
                            Jenis Kelamin <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="jenis_kelamin"
                            name="jenis_kelamin"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('jenis_kelamin', $mahasiswa->jenis_kelamin ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>

                    <div>
                        <label for="status_pernikahan" class="block text-sm font-medium text-gray-700 mb-1">
                            Status Pernikahan <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="status_pernikahan"
                            name="status_pernikahan"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >
                            <option value="">-- Pilih Status Pernikahan --</option>
                            <option value="Belum Menikah" {{ old('status_pernikahan', $mahasiswa->status_pernikahan ?? '') == 'Belum Menikah' ? 'selected' : '' }}>Belum Menikah</option>
                            <option value="Menikah" {{ old('status_pernikahan', $mahasiswa->status_pernikahan ?? '') == 'Menikah' ? 'selected' : '' }}>Menikah</option>
                            <option value="Cerai" {{ old('status_pernikahan', $mahasiswa->status_pernikahan ?? '') == 'Cerai' ? 'selected' : '' }}>Cerai</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="agama" class="block text-sm font-medium text-gray-700 mb-1">
                            Agama <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="agama"
                            name="agama"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >
                            <option value="">-- Pilih Agama --</option>
                            <option value="Islam" {{ old('agama', $mahasiswa->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Kristen" {{ old('agama', $mahasiswa->agama ?? '') == 'Kristen' ? 'selected' : '' }}>Kristen</option>
                            <option value="Katolik" {{ old('agama', $mahasiswa->agama ?? '') == 'Katolik' ? 'selected' : '' }}>Katolik</option>
                            <option value="Hindu" {{ old('agama', $mahasiswa->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                            <option value="Buddha" {{ old('agama', $mahasiswa->agama ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                            <option value="Konghucu" {{ old('agama', $mahasiswa->agama ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                        </select>
                    </div>

                    <div>
                        <label for="pekerjaan" class="block text-sm font-medium text-gray-700 mb-1">
                            Pekerjaan <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="pekerjaan"
                            name="pekerjaan"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >
                            <option value="">-- Pilih Pekerjaan --</option>
                            <option value="Mahasiswa" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Mahasiswa' ? 'selected' : '' }}>Mahasiswa</option>
                            <option value="Karyawan Swasta" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Karyawan Swasta' ? 'selected' : '' }}>Karyawan Swasta</option>
                            <option value="Pegawai Negeri Sipil" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Pegawai Negeri Sipil' ? 'selected' : '' }}>Pegawai Negeri Sipil</option>
                            <option value="Wiraswasta" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Wiraswasta' ? 'selected' : '' }}>Wiraswasta</option>
                            <option value="Pengusaha" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Pengusaha' ? 'selected' : '' }}>Pengusaha</option>
                            <option value="Guru" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Guru' ? 'selected' : '' }}>Guru</option>
                            <option value="Dosen" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Dosen' ? 'selected' : '' }}>Dosen</option>
                            <option value="Dokter" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Dokter' ? 'selected' : '' }}>Dokter</option>
                            <option value="Pengacara" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Pengacara' ? 'selected' : '' }}>Pengacara</option>
                            <option value="Arsitek" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Arsitek' ? 'selected' : '' }}>Arsitek</option>
                            <option value="Insinyur" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Insinyur' ? 'selected' : '' }}>Insinyur</option>
                            <option value="Pensiunan" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Pensiunan' ? 'selected' : '' }}>Pensiunan</option>
                            <option value="Tidak Bekerja" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Tidak Bekerja' ? 'selected' : '' }}>Tidak Bekerja</option>
                            <option value="Lainnya" {{ old('pekerjaan', $mahasiswa->pekerjaan ?? '') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label for="alamat_kantor" class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Kantor <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="alamat_kantor"
                        name="alamat_kantor"
                        rows="3"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan alamat kantor lengkap"
                    >{{ old('alamat_kantor', $mahasiswa->alamat_kantor ?? '') }}</textarea>
                </div>

                <div>
                    <label for="telepon_fax" class="block text-sm font-medium text-gray-700 mb-1">
                        Telepon/Fax <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="telepon_fax"
                        name="telepon_fax"
                        value="{{ old('telepon_fax', $mahasiswa->telepon_fax ?? '') }}"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Contoh: 021-12345678"
                    />
                </div>

                <div>
                    <label for="alamat_rumah" class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Rumah <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="alamat_rumah"
                        name="alamat_rumah"
                        rows="3"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Masukkan nama jalan"
                    >{{ old('alamat_rumah', $mahasiswa->alamat_rumah ?? '') }}</textarea>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="rt" class="block text-sm font-medium text-gray-700 mb-1">
                            RT <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="rt"
                            name="rt"
                            value="{{ old('rt', $mahasiswa->rt ?? '') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Contoh: 001"
                        />
                    </div>

                    <div>
                        <label for="rw" class="block text-sm font-medium text-gray-700 mb-1">
                            RW <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="rw"
                            name="rw"
                            value="{{ old('rw', $mahasiswa->rw ?? '') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Contoh: 002"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="kelurahan_desa" class="block text-sm font-medium text-gray-700 mb-1">
                            Kelurahan/Desa <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="kelurahan_desa"
                            name="kelurahan_desa"
                            value="{{ old('kelurahan_desa', $mahasiswa->kelurahan_desa ?? '') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Contoh: Kelurahan Menteng"
                        />
                    </div>

                    <div>
                        <label for="kecamatan" class="block text-sm font-medium text-gray-700 mb-1">
                            Kecamatan <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="kecamatan"
                            name="kecamatan"
                            value="{{ old('kecamatan', $mahasiswa->kecamatan ?? '') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Contoh: Kecamatan Menteng"
                        />
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label for="kab_kota" class="block text-sm font-medium text-gray-700 mb-1">
                            Kabupaten/Kota <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="kab_kota"
                            name="kab_kota"
                            value="{{ old('kab_kota', $mahasiswa->kab_kota ?? '') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Contoh: Jakarta Pusat"
                        />
                    </div>

                    <div>
                        <label for="provinsi" class="block text-sm font-medium text-gray-700 mb-1">
                            Provinsi <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="provinsi"
                            name="provinsi"
                            value="{{ old('provinsi', $mahasiswa->provinsi ?? '') }}"
                            required
                            class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                            placeholder="Contoh: DKI Jakarta"
                        />
                    </div>
                </div>

                <div>
                    <label for="kode_pos" class="block text-sm font-medium text-gray-700 mb-1">
                        Kode Pos <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="kode_pos"
                        name="kode_pos"
                        value="{{ old('kode_pos', $mahasiswa->kode_pos ?? '') }}"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Contoh: 10310"
                    />
                </div>

                <div>
                    <label for="telp_hp" class="block text-sm font-medium text-gray-700 mb-1">
                        Telp/HP <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="telp_hp"
                        name="telp_hp"
                        value="{{ old('telp_hp', $mahasiswa->telp_hp ?? '') }}"
                        required
                        class="block w-full rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-sm text-gray-900 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500"
                        placeholder="Contoh: 081234567890"
                    />
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <a
                        href="{{ route('profile') }}"
                        class="rounded-lg border border-gray-300 px-6 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                    >
                        Batal
                    </a>
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

    <script>
        function editDataRiri() {
            // Scroll to top of form
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Focus on first input field
            document.getElementById('nama')?.focus();
            
            // Optional: Highlight the form or show a message
            const form = document.querySelector('form');
            if (form) {
                form.style.border = '2px solid #fbbf24';
                form.style.borderRadius = '0.5rem';
                setTimeout(() => {
                    form.style.border = '';
                }, 2000);
            }
        }
    </script>
@endsection

