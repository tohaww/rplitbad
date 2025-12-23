<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Daftar</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icon.png') }}?v={{ time() }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icon.png') }}?v={{ time() }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('icon.png') }}?v={{ time() }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('icon.png') }}?v={{ time() }}">
        <link rel="icon" type="image/png" href="{{ asset('icon.png') }}?v={{ time() }}">

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-100 text-[#1b1b18]">
        <div class="flex min-h-screen items-center justify-center px-4 py-10 sm:px-8">
            <div class="w-full max-w-md rounded-2xl border border-gray-200 bg-white shadow-lg">
                <div class="px-8 py-10">
                    <div class="mb-8 text-center">
                        <div class="mb-4 flex justify-center">
                            <div class="relative">
                                <img 
                                    src="{{ asset('images/logo.png') }}" 
                                    alt="Logo Sistem Rekognisi ITBAD" 
                                    class="h-16 w-16 rounded-full object-cover"
                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                                >
                                <div class="hidden h-16 w-16 items-center justify-center rounded-full bg-yellow-400">
                                    <span class="text-2xl font-bold text-gray-800">S</span>
                                </div>
                            </div>
                        </div>
                        <h1 class="text-2xl font-semibold text-gray-400">Buat Akun Baru</h1>
                    </div>

                    <form method="POST" action="{{ route('register.store') }}" class="space-y-6">
                        @csrf

                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <label for="name" class="font-medium text-gray-400">Full Nama</label>
                                @error('name')
                                    <span class="text-xs text-[#f53003]">{{ $message }}</span>
                                @enderror
                            </div>
                            <input
                                id="name"
                                name="name"
                                type="text"
                                value="{{ old('name') }}"
                                required
                                autofocus
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-[#1b1b18] focus:ring-2 focus:ring-[#f53003]/30"
                            />
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <label for="email" class="font-medium text-gray-400">Email</label>
                                @error('email')
                                    <span class="text-xs text-[#f53003]">{{ $message }}</span>
                                @enderror
                            </div>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-[#1b1b18] focus:ring-2 focus:ring-[#f53003]/30"
                            />
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <label for="kode_referensi" class="font-medium text-gray-400">Kode Referensi</label>
                                <span id="kode_referensi_status" class="text-xs"></span>
                                @error('kode_referensi')
                                    <span class="text-xs text-[#f53003]">{{ $message }}</span>
                                @enderror
                            </div>
                            <input
                                id="kode_referensi"
                                name="kode_referensi"
                                type="text"
                                value="{{ old('kode_referensi') }}"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-[#1b1b18] focus:ring-2 focus:ring-[#f53003]/30"
                                placeholder="Masukkan kode referensi"
                                oninput="validateKodeReferensi(this.value)"
                            />
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <label for="password" class="font-medium text-gray-400">Password</label>
                                @error('password')
                                    <span class="text-xs text-[#f53003]">{{ $message }}</span>
                                @enderror
                            </div>
                            <input
                                id="password"
                                name="password"
                                type="password"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-[#1b1b18] focus:ring-2 focus:ring-[#f53003]/30"
                            />
                        </div>

                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <label for="password_confirmation" class="font-medium text-gray-400">Re-password</label>
                            </div>
                            <input
                                id="password_confirmation"
                                name="password_confirmation"
                                type="password"
                                required
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-[#1b1b18] focus:ring-2 focus:ring-[#f53003]/30"
                            />
                        </div>

                        @if ($errors->any())
                            <div class="rounded-lg border border-[#f53003]/30 bg-[#fff2f2] px-4 py-3 text-sm text-[#f53003]">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <button
                            id="submitBtn"
                            type="submit"
                            class="w-full rounded-lg bg-[#1b1b18] px-4 py-3 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-[#11110f] disabled:cursor-not-allowed disabled:opacity-50 disabled:hover:bg-[#1b1b18]"
                        >
                            Daftar
                        </button>
                    </form>

                    <div class="mt-8 text-center text-sm text-gray-400">
                        <p>Sudah punya akun? 
                            <a href="{{ route('login') }}" class="text-[#f53003] hover:underline">
                                Masuk di sini
                            </a>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            let kodeReferensiValid = true;
            let kodeReferensiValue = '';

            async function validateKodeReferensi(value) {
                const kodeReferensiInput = document.getElementById('kode_referensi');
                const statusSpan = document.getElementById('kode_referensi_status');
                const submitBtn = document.getElementById('submitBtn');
                
                kodeReferensiValue = value.trim();
                
                // Jika kosong, tampilkan error (karena required)
                if (kodeReferensiValue === '') {
                    statusSpan.textContent = 'Kode referensi wajib diisi';
                    statusSpan.className = 'text-xs text-[#f53003]';
                    kodeReferensiInput.classList.remove('border-green-500', 'border-gray-300');
                    kodeReferensiInput.classList.add('border-red-500');
                    kodeReferensiValid = false;
                    submitBtn.disabled = true;
                    return;
                }

                // Check kode referensi via API
                try {
                    const response = await fetch('{{ route('register.check-kode-referensi') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ kode_referensi: kodeReferensiValue })
                    });

                    const data = await response.json();
                    
                    if (data.valid) {
                        statusSpan.textContent = '✓ Kode referensi valid';
                        statusSpan.className = 'text-xs text-green-600';
                        kodeReferensiInput.classList.remove('border-red-500', 'border-gray-300');
                        kodeReferensiInput.classList.add('border-green-500');
                        kodeReferensiValid = true;
                        submitBtn.disabled = false;
                    } else {
                        statusSpan.textContent = '✗ Kode referensi tidak valid';
                        statusSpan.className = 'text-xs text-[#f53003]';
                        kodeReferensiInput.classList.remove('border-green-500', 'border-gray-300');
                        kodeReferensiInput.classList.add('border-red-500');
                        kodeReferensiValid = false;
                        submitBtn.disabled = true;
                    }
                } catch (error) {
                    console.error('Error validating kode referensi:', error);
                    statusSpan.textContent = '';
                    statusSpan.className = 'text-xs';
                    kodeReferensiInput.classList.remove('border-red-500', 'border-green-500');
                    kodeReferensiInput.classList.add('border-gray-300');
                    kodeReferensiValid = true; // Allow submit on error
                    submitBtn.disabled = false;
                }
            }

            // Validate on page load if there's old input
            document.addEventListener('DOMContentLoaded', function() {
                const kodeReferensiInput = document.getElementById('kode_referensi');
                if (kodeReferensiInput.value) {
                    validateKodeReferensi(kodeReferensiInput.value);
                }
            });

            // Prevent form submission if kode referensi is invalid
            document.querySelector('form').addEventListener('submit', function(e) {
                const kodeReferensiInput = document.getElementById('kode_referensi');
                const value = kodeReferensiInput.value.trim();
                
                // If kode referensi is empty or not valid, prevent submission
                if (value === '' || !kodeReferensiValid) {
                    e.preventDefault();
                    alert('Kode referensi wajib diisi dan harus valid.');
                    return false;
                }
            });
        </script>
    </body>
</html>

