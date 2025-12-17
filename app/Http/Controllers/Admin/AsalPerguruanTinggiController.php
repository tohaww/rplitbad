<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AsalPerguruanTinggi;
use Illuminate\Http\Request;

class AsalPerguruanTinggiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $asalPerguruanTinggi = AsalPerguruanTinggi::orderBy('nama', 'asc')->get();
        
        return view('admin.asal-perguruan-tinggi', [
            'asalPerguruanTinggi' => $asalPerguruanTinggi,
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
            'nama' => ['required', 'string', 'max:255', 'unique:asal_perguruan_tinggi,nama'],
        ]);

        AsalPerguruanTinggi::create($validated);

        return redirect()->route('admin.asal-perguruan-tinggi')
            ->with('success', 'Asal perguruan tinggi berhasil ditambahkan.');
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
        $asalPerguruanTinggi = AsalPerguruanTinggi::findOrFail($id);

        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255', 'unique:asal_perguruan_tinggi,nama,' . $id],
        ]);

        $asalPerguruanTinggi->update($validated);

        return redirect()->route('admin.asal-perguruan-tinggi')
            ->with('success', 'Asal perguruan tinggi berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $asalPerguruanTinggi = AsalPerguruanTinggi::findOrFail($id);
        $asalPerguruanTinggi->delete();

        return redirect()->route('admin.asal-perguruan-tinggi')
            ->with('success', 'Asal perguruan tinggi berhasil dihapus.');
    }
}
