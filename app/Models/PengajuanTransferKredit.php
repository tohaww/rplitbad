<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanTransferKredit extends Model
{
    protected $fillable = [
        'mahasiswa_id',
        'perguruan_tinggi_asal',
        'jenjang_pendidikan',
        'program_studi_asal',
        'nim_asal',
        'program_studi_tertuju',
        'lokasi_kuliah_tertuju',
        'status',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }

    public function matkuls()
    {
        return $this->hasMany(\App\Models\TransferKreditMatkul::class, 'pengajuan_id');
    }
}
