<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KodeReferensi;
use Illuminate\Http\Request;

class KodeReferensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kodeReferensi = KodeReferensi::orderBy('nama_referensi', 'asc')->get();
        
        return view('admin.kode-referensi', [
            'kodeReferensi' => $kodeReferensi,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_referensi' => ['required', 'string', 'max:255', 'unique:kode_referensi,nama_referensi'],
            'kode_referensi' => ['required', 'string', 'max:255', 'unique:kode_referensi,kode_referensi'],
        ]);

        KodeReferensi::create($validated);

        return redirect()->route('admin.kode-referensi')
            ->with('success', 'Kode referensi berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kodeReferensi = KodeReferensi::findOrFail($id);

        $validated = $request->validate([
            'nama_referensi' => ['required', 'string', 'max:255', 'unique:kode_referensi,nama_referensi,' . $id],
            'kode_referensi' => ['required', 'string', 'max:255', 'unique:kode_referensi,kode_referensi,' . $id],
        ]);

        $kodeReferensi->update($validated);

        return redirect()->route('admin.kode-referensi')
            ->with('success', 'Kode referensi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kodeReferensi = KodeReferensi::findOrFail($id);
        $kodeReferensi->delete();

        return redirect()->route('admin.kode-referensi')
            ->with('success', 'Kode referensi berhasil dihapus.');
    }
}
