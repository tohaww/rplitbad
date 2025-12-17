@extends('layouts.app')

@section('title', 'Pengakuan Matkul')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold text-[#1b1b18]">Pengakuan Matkul</h1>
                <div class="mt-2 flex items-center gap-2 text-sm text-gray-600">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    <span>Pengakuan Matkul</span>
                </div>
            </div>
            <div class="text-sm text-gray-600">
                <span class="text-blue-600">Menu</span> / <span class="text-[#1b1b18]">Pengakuan Matkul</span>
            </div>
        </div>
    </div>
@endsection
