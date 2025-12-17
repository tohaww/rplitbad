<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenghargaanPiagam extends Model
{
    use HasFactory;

    protected $table = 'penghargaan_piagam';

    protected $fillable = [
        'mahasiswa_id',
        'tahun',
        'bentuk_penghargaan',
        'pemberi',
        'file_path',
        'keterangan_file',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }
}
