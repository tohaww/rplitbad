<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Assesor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class AssesorController extends Controller
{
    public function index()
    {
        $assesor = Assesor::orderBy('created_at', 'desc')->get();

        return view('admin.data-assesor', [
            'assesor' => $assesor,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_assesor' => ['required', 'string', 'max:255', 'unique:assesor,id_assesor'],
            'nama' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        $data = $validated;
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        Assesor::create($data);

        return redirect()->route('admin.data-assesor')->with('success', 'Data assesor berhasil ditambahkan.');
    }

    public function update(Request $request, string $id)
    {
        $assesor = Assesor::findOrFail($id);

        $validated = $request->validate([
            'id_assesor' => [
                'required',
                'string',
                'max:255',
                Rule::unique('assesor', 'id_assesor')->ignore($assesor->id_assesor, 'id_assesor'),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'password' => ['nullable', 'string', 'min:6'],
        ]);

        // Jika id berubah, set manual karena primary key string
        if ($assesor->id_assesor !== $validated['id_assesor']) {
            $assesor->id_assesor = $validated['id_assesor'];
        }

        $assesor->nama = $validated['nama'];
        if (!empty($validated['password'])) {
            $assesor->password = Hash::make($validated['password']);
        }
        $assesor->save();

        return redirect()->route('admin.data-assesor')->with('success', 'Data assesor berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $assesor = Assesor::findOrFail($id);
        $assesor->delete();

        return redirect()->route('admin.data-assesor')->with('success', 'Data assesor berhasil dihapus.');
    }
}

