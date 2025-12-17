<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PelatihanProfesional extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'tahun',
        'jenis_pelatihan',
        'penyelenggara',
        'jangka_waktu',
        'file_path',
        'keterangan_file',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }
}
