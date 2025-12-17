<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Data dummy untuk pengajuan rekognisi
        $pengajuanRekognisi = [
            [
                'nama' => 'John Doe',
                'status' => 'Menunggu',
                'tanggal' => '2023-10-01',
            ],
            [
                'nama' => 'Jane Doe',
                'status' => 'Diterima',
                'tanggal' => '2023-10-03',
            ],
            [
                'nama' => 'Bob Smith',
                'status' => 'Ditolak',
                'tanggal' => '2023-10-05',
            ],
            [
                'nama' => 'Alice Johnson',
                'status' => 'Menunggu',
                'tanggal' => '2023-10-06',
            ],
        ];

        // Data pengguna
        $dataPengguna = User::select('name', 'email', 'role')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'nama' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role === 'admin' ? 'Admin' : 'User',
                ];
            });

        return view('admin.dashboard', [
            'pengajuanRekognisi' => $pengajuanRekognisi,
            'dataPengguna' => $dataPengguna,
        ]);
    }
}
