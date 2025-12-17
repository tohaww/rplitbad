<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatPendidikan extends Model
{
    use HasFactory;

    protected $fillable = [
        'mahasiswa_id',
        'nama_sekolah',
        'tahun_lulus',
        'jurusan_program_studi',
        'file_path',
        'keterangan_file',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }
}
