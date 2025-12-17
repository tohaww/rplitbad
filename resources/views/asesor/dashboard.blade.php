@extends('layouts.app')

@section('title', 'Dashboard Asesor')

@section('content')
    @php
        $user = Auth::user();
    @endphp

    <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-[0px_25px_50px_-12px_rgba(0,0,0,0.08)]">
        <p class="text-sm uppercase tracking-[0.3em] text-[#f53003]">Dashboard</p>
        <h2 class="mt-2 text-2xl font-semibold">Selamat datang, {{ $user->name }}!</h2>
        <p class="mt-4 text-sm text-[#706f6c]">
            Anda berhasil login sebagai Asesor. Silakan akses menu yang tersedia untuk melakukan assessment dan evaluasi.
        </p>
    </div>

@endsection

