<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KegiatanIlmiah extends Model
{
    use HasFactory;

    protected $table = 'kegiatan_ilmiah';

    protected $fillable = [
        'mahasiswa_id',
        'tahun',
        'judul_kegiatan',
        'penyelenggara',
        'peran',
        'file_path',
        'keterangan_file',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }
}
