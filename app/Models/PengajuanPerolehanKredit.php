<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPerolehanKredit extends Model
{
    use HasFactory;

    protected $table = 'pengajuan_perolehan_kredit';

    protected $fillable = [
        'mahasiswa_id',
        'program_studi_id',
        'no_bukti',
        'tanggal',
        'status',
        'total_sks',
        'mata_kuliah',
        'id_matkul',
        'nama_matkul',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'mata_kuliah' => 'array',
    ];

    /**
     * Get the mahasiswa that owns the pengajuan.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }

    /**
     * Get the program studi.
     */
    public function programStudi()
    {
        return $this->belongsTo(\App\Models\ProgramStudi::class, 'program_studi_id', 'id');
    }

}
