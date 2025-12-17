<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormEvaluasi extends Model
{
    protected $table = 'form_evaluasi';

    protected $fillable = [
        'mahasiswa_id',
        'id_matkul',
        'capaian_pembelajaran',
        'profisiensi',
        'jenis_dokumen',
        'bukti',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }

    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class, 'id_matkul', 'id_matkul');
    }
}
