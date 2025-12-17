<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Assesor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    /**
     * Display the login form.
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an authentication attempt.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        // Try default user login (email/password)
        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            $request->session()->regenerate();
            return $this->redirectByRole(Auth::user());
        }

        // Fallback: login as Assesor using id_assesor + password
        $assesor = Assesor::where('id_assesor', $credentials['email'])->first();
        if ($assesor && $assesor->password && Hash::check($credentials['password'], $assesor->password)) {
            // Sinkronisasi ke tabel users agar tetap memakai guard web & middleware auth
            $user = User::updateOrCreate(
                ['email' => $assesor->id_assesor . '@asesor.local'],
                [
                    'name' => $assesor->nama,
                    'password' => $assesor->password, // sudah ter-hash
                    'role' => 'asesor',
                ]
            );

            Auth::login($user, $remember);
            $request->session()->regenerate();
            return redirect()->route('asesor.dashboard');
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    /**
     * Log the user out of the application.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole($user)
    {
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->isAsesor()) {
            return redirect()->route('asesor.dashboard');
        }

        // Default untuk mahasiswa
        return redirect()->route('dashboard');
    }
}
