<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>
            @hasSection('title')
                @yield('title')
            @else
                {{ config('app.name', '') }}
            @endif
        </title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

        <!-- Favicon -->
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('icon.png') }}?v={{ time() }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('icon.png') }}?v={{ time() }}">
        <link rel="shortcut icon" href="{{ asset('icon.png') }}?v={{ time() }}">
        <link rel="apple-touch-icon" href="{{ asset('icon.png') }}?v={{ time() }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#FDFDFC] text-[#1b1b18]">
        @php
            $user = Auth::user();
            
            // Menu berbeda berdasarkan role
            if ($user && $user->isAsesor()) {
                // Menu untuk Asesor
                $navItems = [
                    [
                        'label' => 'Dashboard',
                        'route' => 'asesor.dashboard',
                        'icon' => 'dashboard',
                    ],
                    [
                        'label' => 'Daftar Pengajuan',
                        'route' => 'asesor.pengajuan',
                        'icon' => 'submission',
                    ],
                    [
                        'label' => 'Form Assessment',
                        'route' => 'asesor.assessment',
                        'icon' => 'evaluation',
                    ],
                ];
            } else {
                // Menu untuk Mahasiswa
                $navItems = [
                    [
                        'label' => 'Dashboard',
                        'route' => 'dashboard',
                        'icon' => 'dashboard',
                    ],
                    [
                        'label' => 'Profile',
                        'route' => 'profile',
                        'icon' => 'profile',
                    ],
                    [
                        'label' => 'Daftar Matkul Prodi',
                        'route' => 'courses',
                        'icon' => 'courses',
                    ],
                    [
                        'label' => 'Pengajuan Matkul',
                        'route' => 'course-submission',
                        'icon' => 'submission',
                    ],
                    [
                        'label' => 'Form Evaluasi Diri',
                        'route' => 'self-evaluation',
                        'icon' => 'evaluation',
                    ],
                    [
                        'label' => 'Pengakuan Matkul',
                        'route' => 'course-recognition',
                        'icon' => 'recognition',
                    ],
                ];
            }
        @endphp

        <div class="flex min-h-screen">
            <aside
                class="hidden w-64 shrink-0 border-r border-[#1914001a] bg-[#111827] px-5 py-8 text-white box-border lg:fixed lg:inset-y-0 lg:left-0 lg:block lg:h-screen"
                style="width: 256px !important; min-width: 256px !important; max-width: 256px !important; box-sizing: border-box !important; overflow-y: auto !important; overflow-x: hidden !important; flex-shrink: 0 !important; scrollbar-gutter: stable !important;"
            >
                {{-- Logo dan Judul Sistem Rekognisi --}}
                <div class="mb-8">
                    <div class="flex items-center gap-2">
                        <div class="relative">
                            <img 
                                src="{{ asset('images/logo.png') }}" 
                                alt="Logo Sistem Rekognisi" 
                                class="h-8 w-8 rounded-full object-cover"
                                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                            >
                            <div class="hidden h-8 w-8 items-center justify-center rounded-full bg-yellow-400">
                                <span class="text-lg font-bold text-gray-800">S</span>
                            </div>
                        </div>
                        <span class="text-lg font-semibold">Sistem Rekognisi</span>
                    </div>
                </div>

                <div class="text-[11px] font-semibold uppercase tracking-[0.4em] text-white/60 mb-3">Menu</div>

                <nav class="space-y-2 text-sm">
                    @foreach ($navItems as $item)
                        @php
                            $isActive = $item['route'] !== '#' && request()->routeIs($item['route']);
                            $isDisabled = $item['route'] === '#' || ($item['disabled'] ?? false);
                        @endphp
                        <a
                            @if (!$isDisabled)
                                href="{{ route($item['route']) }}"
                            @else
                                href="#"
                            @endif
                            class="flex items-center gap-3 rounded-lg px-4 py-2 transition
                                {{ $isActive ? 'bg-blue-500 text-white shadow-lg shadow-blue-500/40' : 'text-white/80 hover:bg-white/10' }}
                                {{ $isDisabled ? 'cursor-not-allowed opacity-50' : '' }}"
                        >
                            <span class="text-base">
                                @switch($item['icon'])
                                    @case('dashboard')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13h2l1-5 4-2 5 4 3-1 3 3v5H3v-4Z" />
                                        </svg>
                                        @break
                                    @case('profile')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 20a7 7 0 0 1 14 0M12 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8Z" />
                                        </svg>
                                        @break
                                    @case('courses')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4 7 8-4 8 4-8 4-8-4Zm0 6 8 4 8-4M4 7v6" />
                                        </svg>
                                        @break
                                    @case('submission')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 17v-6l3-2 3 2v6M5 21h14M5 3l7 4 7-4" />
                                        </svg>
                                        @break
                                    @case('evaluation')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 7h10M7 12h5m-5 5h8M5 3h14v18H5z" />
                                        </svg>
                                        @break
                                    @case('recognition')
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v6m0 0 3-3m-3 3-3-3m8-5a8 8 0 1 0-10 0" />
                                        </svg>
                                        @break
                                @endswitch
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </aside>

            <div class="flex flex-1 flex-col lg:ml-64 lg:pl-4">
                <header class="sticky top-0 z-10 border-b border-[#1914001a] bg-white/70 backdrop-blur">
                    <div class="flex items-center justify-between px-4 py-4 lg:px-8">
                        <button class="inline-flex items-center gap-2 rounded-lg border border-[#1914001a] px-3 py-2 text-xs font-medium uppercase tracking-widest text-[#706f6c] lg:hidden">
                            Menu
                        </button>
                        <div class="hidden text-sm font-medium text-[#706f6c] lg:block">
                            @yield('title', 'Dashboard')
                        </div>
                        <div class="ml-auto flex items-center gap-4">
                            <div class="text-right">
                                <p class="text-sm font-medium">{{ $user?->name ?? 'User' }}</p>
                                <p class="text-xs text-[#706f6c]">
                                    {{ $user?->email ?? 'Tidak tersedia' }}
                                    @if($user?->role)
                                        <span class="ml-2 rounded-full bg-blue-500/10 px-2 py-0.5 text-[10px] font-medium uppercase text-blue-600">
                                            @if($user->role === 'admin')
                                                Admin
                                            @elseif($user->role === 'asesor')
                                                Asesor
                                            @else
                                                Mahasiswa
                                            @endif
                                        </span>
                                    @endif
                                </p>
                            </div>
                            <span class="flex h-10 w-10 items-center justify-center rounded-full bg-[#f53003]/10 text-sm font-semibold text-[#f53003]">
                                {{ strtoupper(substr($user?->name ?? 'M', 0, 1)) }}
                            </span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button
                                    type="submit"
                                    class="rounded-full border border-[#19140035] px-4 py-2 text-xs font-semibold uppercase tracking-widest text-[#1b1b18] transition hover:border-[#1b1b18]"
                                >
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </header>

                <main class="flex-1 px-4 py-8 lg:px-8">
                    @yield('content')
                </main>

                <footer class="border-t border-[#1914001a] bg-white/70 backdrop-blur">
                    <div class="px-4 py-6 lg:px-8">
                        <div class="flex items-center justify-center">
                            <p class="text-sm text-[#706f6c]">
                                &copy; 2025 - {{ date('Y') }} Sistem Rekognisi ITB Ahmad Dahlan Jakarta. All rights reserved.
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

    </body>
</html>

