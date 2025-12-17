<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Masuk</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icon.png') }}?v={{ time() }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icon.png') }}?v={{ time() }}">
        <link rel="shortcut icon" href="{{ asset('icon.png') }}?v={{ time() }}">
        <link rel="apple-touch-icon" href="{{ asset('icon.png') }}?v={{ time() }}">

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
                        <h1 class="text-2xl font-semibold text-gray-400">Sistem Rekognisi ITBAD</h1>
                    </div>

                    <form method="POST" action="{{ route('login.store') }}" class="space-y-6">
                        @csrf

                        <div class="space-y-2">
                            <div class="flex items-center justify-between text-sm">
                                <label for="email" class="font-medium text-gray-400">Email atau ID Assesor</label>
                                @error('email')
                                    <span class="text-xs text-[#f53003]">{{ $message }}</span>
                                @enderror
                            </div>
                            <input
                                id="email"
                                name="email"
                                type="text"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-3 text-sm text-gray-900 outline-none transition focus:border-[#1b1b18] focus:ring-2 focus:ring-[#f53003]/30"
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

                        <div class="flex items-center justify-between text-sm">
                            <label class="inline-flex items-center gap-2 text-xs text-gray-400">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    class="size-4 rounded border border-gray-300 text-[#f53003] focus:ring-[#f53003]/30"
                                    {{ old('remember') ? 'checked' : '' }}
                                />
                                Ingat saya
                            </label>
                            @if (session('status'))
                                <span class="text-xs text-[#1b1b18]">{{ session('status') }}</span>
                            @endif
                        </div>

                        @if ($errors->has('email') && ! $errors->has('password'))
                            <p class="rounded-lg border border-[#f53003]/30 bg-[#fff2f2] px-4 py-3 text-sm text-[#f53003]">
                                {{ __('auth.failed') }}
                            </p>
                        @endif

                        <button
                            type="submit"
                            class="w-full rounded-lg bg-[#1b1b18] px-4 py-3 text-sm font-semibold uppercase tracking-wide text-white transition hover:bg-[#11110f]"
                        >
                            Masuk
                        </button>
                    </form>

                    <div class="mt-8 text-center text-sm text-gray-400">
                        <p>
                            Belum punya akun? Silakan 
                            <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 hover:underline">
                                Registrasi
                            </a>
                            untuk membuat akun baru.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
