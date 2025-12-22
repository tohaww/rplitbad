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

        <style>
            /* Force sidebar to always be 256px wide, including scrollbar */
            aside {
                width: 256px !important;
                min-width: 256px !important;
                max-width: 256px !important;
                scrollbar-gutter: stable !important;
                position: fixed !important;
                left: 0 !important;
                top: 0 !important;
                height: 100vh !important;
                z-index: 10 !important;
            }
            aside > div:first-child {
                width: 100% !important;
                max-width: 100% !important;
                min-width: 0 !important;
                box-sizing: border-box !important;
            }
            aside nav,
            aside nav * {
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            aside button,
            aside a {
                max-width: 100% !important;
                box-sizing: border-box !important;
            }
            /* Main content margin */
            .main-content-wrapper {
                margin-left: 256px !important;
                width: calc(100% - 256px) !important;
            }
            @media (max-width: 1023px) {
                .main-content-wrapper {
                    margin-left: 0 !important;
                    width: 100% !important;
                }
                aside {
                    transform: translateX(-100%) !important;
                    transition: transform 0.3s ease-in-out !important;
                }
                aside.mobile-open {
                    transform: translateX(0) !important;
                }
                .sidebar-overlay {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 9;
                }
                .sidebar-overlay.active {
                    display: block !important;
                }
            }
            /* Custom scrollbar that doesn't take space */
            aside::-webkit-scrollbar {
                width: 8px;
            }
            aside::-webkit-scrollbar-track {
                background: transparent;
            }
            aside::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.2);
                border-radius: 4px;
            }
            aside::-webkit-scrollbar-thumb:hover {
                background: rgba(255, 255, 255, 0.3);
            }
        </style>
        <script>
            function toggleDropdown(dropdownId) {
                const dropdown = document.getElementById(dropdownId);
                const icon = document.getElementById(dropdownId + '-icon');
                
                if (dropdown) {
                    dropdown.classList.toggle('hidden');
                    if (icon) {
                        icon.classList.toggle('rotate-180');
                    }
                }
            }

            function toggleMobileSidebar() {
                const sidebar = document.getElementById('admin-sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                
                if (sidebar) {
                    sidebar.classList.toggle('mobile-open');
                }
                if (overlay) {
                    overlay.classList.toggle('active');
                }
            }

            function closeMobileSidebar() {
                const sidebar = document.getElementById('admin-sidebar');
                const overlay = document.getElementById('sidebar-overlay');
                
                if (sidebar) {
                    sidebar.classList.remove('mobile-open');
                }
                if (overlay) {
                    overlay.classList.remove('active');
                }
            }

            // Close sidebar when clicking overlay
            document.addEventListener('DOMContentLoaded', function() {
                const overlay = document.getElementById('sidebar-overlay');
                if (overlay) {
                    overlay.addEventListener('click', closeMobileSidebar);
                }
            });
        </script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-gray-50">
        @php
            $user = Auth::user();
            $adminNavItems = [
                [
                    'label' => 'Dashboard',
                    'route' => 'admin.dashboard',
                    'icon' => 'dashboard',
                ],
                [
                    'label' => 'Kuliah',
                    'route' => 'admin.settings',
                    'icon' => 'kuliah',
                    'hasDropdown' => true,
                    'submenu' => [
                        [
                            'label' => 'Data Matkul',
                            'route' => 'admin.courses',
                            'icon' => 'courses',
                        ],
                        [
                            'label' => 'Program Studi',
                            'route' => 'admin.program-studi',
                            'icon' => 'program-studi',
                        ],
                    ],
                ],
                [
                    'label' => 'Pengajuan Mahasiswa',
                    'route' => 'admin.pengajuan.dashboard',
                    'icon' => 'recognition',
                    'hasDropdown' => true,
                    'submenu' => [
                        [
                            'label' => 'Dashboard Pengajuan',
                            'route' => 'admin.pengajuan.dashboard',
                            'icon' => 'dashboard-pengajuan',
                        ],
                        [
                            'label' => 'Semua Pengajuan',
                            'route' => 'admin.pengajuan.semua',
                            'icon' => 'semua-pengajuan',
                        ],
                    ],
                ],
                [
                    'label' => 'Laporan',
                    'route' => 'admin.reports',
                    'icon' => 'reports',
                ],
                [
                    'label' => 'Konfigurasi',
                    'route' => 'admin.config',
                    'icon' => 'config',
                    'hasDropdown' => true,
                    'submenu' => [
                        [
                            'label' => 'Data Pengguna',
                            'route' => 'admin.users',
                            'icon' => 'users',
                        ],
                        [
                            'label' => 'Kode Referensi',
                            'route' => 'admin.kode-referensi',
                            'icon' => 'kode-referensi',
                        ],
                        [
                            'label' => 'Asal Perguruan Tinggi',
                            'route' => 'admin.asal-perguruan-tinggi',
                            'icon' => 'asal-perguruan-tinggi',
                        ],
                        [
                            'label' => 'Data Assesor',
                            'route' => 'admin.data-assesor',
                            'icon' => 'users',
                        ],
                    ],
                ],
            ];
            
            // Check if current route is in settings submenu
            $isSettingsActive = request()->routeIs('admin.settings') || request()->routeIs('admin.courses') || request()->routeIs('admin.program-studi');
            
            // Check if current route is in pengajuan submenu
            $isPengajuanActive = request()->routeIs('admin.pengajuan.*');
            
            // Check if current route is in konfigurasi submenu
            $isKonfigurasiActive = request()->routeIs('admin.config')
                || request()->routeIs('admin.users')
                || request()->routeIs('admin.kode-referensi')
                || request()->routeIs('admin.asal-perguruan-tinggi')
                || request()->routeIs('admin.data-assesor');
        @endphp

        <div class="flex min-h-screen">
            <!-- Sidebar Overlay (Mobile) -->
            <div id="sidebar-overlay" class="sidebar-overlay"></div>
            
            <!-- Sidebar -->
            <aside id="admin-sidebar" class="lg:block shrink-0 border-r border-gray-200 bg-gray-800 text-white" style="width: 256px !important; min-width: 256px !important; max-width: 256px !important; box-sizing: border-box !important; overflow-y: auto !important; overflow-x: hidden !important; position: fixed !important; left: 0 !important; top: 0 !important; height: 100vh !important; z-index: 10 !important; flex-shrink: 0 !important; scrollbar-gutter: stable !important;">
                <div class="h-full" style="width: 100% !important; max-width: 100% !important; box-sizing: border-box !important; padding-left: 1.5rem !important; padding-right: 1.5rem !important; padding-top: 2rem !important; padding-bottom: 2rem !important; flex-shrink: 0 !important;">
                <div class="mb-8 flex items-center justify-between" style="width: 100% !important; max-width: 100% !important; box-sizing: border-box !important;">
                    <div class="flex items-center gap-2" style="width: 100% !important; max-width: 100% !important; box-sizing: border-box !important;">
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
                    <button onclick="closeMobileSidebar()" class="lg:hidden text-gray-300 hover:text-white">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <nav class="space-y-1" style="width: 100% !important; max-width: 100% !important; overflow-x: hidden !important; box-sizing: border-box !important;">
                    @foreach ($adminNavItems as $item)
                        @php
                            $isActive = request()->routeIs($item['route']);
                            $hasDropdown = isset($item['hasDropdown']) && $item['hasDropdown'];
                            $hasSubmenuActive = false;
                            if ($hasDropdown && isset($item['submenu'])) {
                                foreach ($item['submenu'] as $submenu) {
                                    if (request()->routeIs($submenu['route'])) {
                                        $hasSubmenuActive = true;
                                        break;
                                    }
                                }
                            }
                            $isParentActive = $isActive || $hasSubmenuActive;
                        @endphp
                        
                        @if ($hasDropdown)
                            @php
                                $dropdownId = strtolower(str_replace(' ', '-', $item['label'])) . '-dropdown';
                                $dropdownIconId = $dropdownId . '-icon';
                            @endphp
                            <div class="space-y-1" style="width: 100% !important; max-width: 100% !important; overflow-x: hidden !important; box-sizing: border-box !important;">
                                <button
                                    type="button"
                                    onclick="toggleDropdown('{{ $dropdownId }}')"
                                    class="flex w-full items-center justify-between rounded-lg px-4 py-3 text-sm font-medium transition
                                        {{ $isParentActive ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                                    style="width: 100% !important; max-width: 100% !important; min-width: 0 !important; box-sizing: border-box !important; overflow: hidden !important;"
                                >
                                    <div class="flex items-center gap-3 flex-1 min-w-0" style="overflow: hidden !important; min-width: 0 !important; max-width: calc(100% - 1.5rem) !important;">
                                        <span class="shrink-0">
                                            @switch($item['icon'])
                                                @case('kuliah')
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443a55.381 55.381 0 015.25 2.882V15" />
                                                    </svg>
                                                    @break
                                                @case('recognition')
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                                    </svg>
                                                    @break
                                                @case('config')
                                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    </svg>
                                                    @break
                                            @endswitch
                                        </span>
                                        <span class="flex-1 min-w-0 text-left truncate" style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $item['label'] }}</span>
                                    </div>
                                    <svg id="{{ $dropdownIconId }}" class="h-4 w-4 shrink-0 ml-2 transition-transform duration-200 flex-shrink-0 {{ $hasSubmenuActive ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink: 0;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="{{ $dropdownId }}" class="{{ $hasSubmenuActive ? '' : 'hidden' }} ml-4 space-y-1 border-l-2 border-gray-700 pl-3" style="width: calc(100% - 1rem) !important; max-width: calc(100% - 1rem) !important; overflow-x: hidden !important; box-sizing: border-box !important;">
                                    @foreach ($item['submenu'] as $submenu)
                                        @php
                                            $isSubmenuActive = request()->routeIs($submenu['route']);
                                        @endphp
                                        <a
                                            href="{{ route($submenu['route']) }}"
                                            onclick="if(window.innerWidth < 1024) closeMobileSidebar();"
                                            class="flex items-center gap-3 rounded-lg px-4 py-2 text-sm font-medium transition
                                                {{ $isSubmenuActive ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                                        >
                                            <span>
                                                @switch($submenu['icon'])
                                                    @case('courses')
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        @break
                                                    @case('program-studi')
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                        </svg>
                                                        @break
                                                    @case('dashboard-pengajuan')
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                                        </svg>
                                                        @break
                                                    @case('perolehan-kredit')
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                        @break
                                                    @case('transfer-kredit')
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                                        </svg>
                                                        @break
                                                    @case('semua-pengajuan')
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                        @break
                                                    @case('users')
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                                        </svg>
                                                        @break
                                                    @case('kode-referensi')
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                                        </svg>
                                                        @break
                                                    @case('asal-perguruan-tinggi')
                                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                                        </svg>
                                                        @break
                                                @endswitch
                                            </span>
                                            <span>{{ $submenu['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <a
                                href="{{ route($item['route']) }}"
                                onclick="if(window.innerWidth < 1024) closeMobileSidebar();"
                                class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition
                                    {{ $isActive ? 'bg-blue-600 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}"
                            >
                                <span>
                                    @switch($item['icon'])
                                        @case('dashboard')
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                            </svg>
                                            @break
                                        @case('users')
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                            </svg>
                                            @break
                                        @case('recognition')
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                            </svg>
                                            @break
                                        @case('reports')
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                            </svg>
                                            @break
                                        @case('config')
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            @break
                                    @endswitch
                                </span>
                                <span>{{ $item['label'] }}</span>
                            </a>
                        @endif
                    @endforeach

                    <form method="POST" action="{{ route('logout') }}" class="mt-4">
                        @csrf
                        <button
                            type="submit"
                            class="flex w-full items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium text-gray-300 transition hover:bg-gray-700 hover:text-white"
                        >
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            <span>Log Out</span>
                        </button>
                    </form>
                </nav>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="main-content-wrapper flex flex-1 flex-col">
                <!-- Header -->
                <header class="sticky top-0 z-10 border-b border-gray-200 bg-white">
                    <div class="flex items-center justify-between px-4 py-4 lg:px-8">
                        <div class="flex items-center gap-4">
                            <button onclick="toggleMobileSidebar()" class="lg:hidden">
                                <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600">Home</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <svg class="h-5 w-5 text-gray-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <span class="text-sm font-medium text-gray-600">Contact</span>
                            </div>
                        </div>

                        <div class="flex items-center gap-4">
                            <button class="text-gray-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                            <button class="text-gray-600">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                            </button>
                                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-500 text-sm font-semibold text-white">
                                {{ strtoupper(substr($user?->name ?? 'A', 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Main Content Area -->
                <main class="flex-1 p-6 lg:p-8">
                    @yield('content')
                </main>

                <footer class="border-t border-gray-200 bg-white">
                    <div class="px-6 py-6 lg:px-8">
                        <div class="flex items-center justify-center">
                            <p class="text-sm text-gray-600 text-center">
                                &copy; 2025 - {{ date('Y') }} Sistem Rekognisi ITB Ahmad Dahlan Jakarta. All rights reserved.
                            </p>
                        </div>
                    </div>
                </footer>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>

