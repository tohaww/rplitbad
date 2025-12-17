@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    @php
        $user = Auth::user();
        $mahasiswa = \App\Models\Mahasiswa::where('user_id', $user->id)->first();
        $nama = $mahasiswa?->nama ?? $user->name ?? 'Pengguna';
    @endphp

    <div class="rounded-2xl border border-[#1914001a] bg-white/80 p-6 shadow-[0px_25px_50px_-12px_rgba(0,0,0,0.08)]">
        <p class="text-sm uppercase tracking-[0.3em] text-[#f53003]">Status</p>
        <h2 class="mt-2 text-2xl font-semibold">Selamat datang, {{ $nama }}!</h2>
        <p class="mt-4 text-sm text-[#706f6c]">
            Anda berhasil login sebagai calon mahasiswa. Silakan lanjutkan ke menu profil untuk melengkapi data diri Anda.
        </p>
    </div>

@endsection

