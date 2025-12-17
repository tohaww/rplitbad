<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\KodeReferensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Display the registration form.
     */
    public function create()
    {
        return view('auth.register');
    }

    /**
     * Check if kode referensi is valid.
     */
    public function checkKodeReferensi(Request $request)
    {
        $kodeReferensi = $request->input('kode_referensi');
        
        if (empty($kodeReferensi)) {
            return response()->json(['valid' => true]); // Empty is allowed (optional)
        }

        $exists = KodeReferensi::where('kode_referensi', $kodeReferensi)->exists();
        
        return response()->json(['valid' => $exists]);
    }

    /**
     * Handle a registration request.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'confirmed', Password::defaults()],
                'kode_referensi' => [
                    'nullable',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) {
                        if ($value && !KodeReferensi::where('kode_referensi', $value)->exists()) {
                            $fail('Kode referensi yang Anda masukkan tidak valid.');
                        }
                    },
                ],
            ]);

            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'kode_referensi' => $validated['kode_referensi'] ?? null,
                'role' => 'mahasiswa', // Default role untuk user baru
            ]);

            Auth::login($user);

            $request->session()->regenerate();

            return redirect()->intended(route('dashboard'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }
}

