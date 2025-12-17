<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransferKreditMatkul extends Model
{
    protected $fillable = [
        'pengajuan_id',
        'mahasiswa_id',
        'notab',
        'kode_matkul_asal',
        'nama_matkul_asal',
        'sks_asal',
        'nilai_asal',
    ];

    public function pengajuan()
    {
        return $this->belongsTo(\App\Models\PengajuanTransferKredit::class, 'pengajuan_id');
    }

    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'mahasiswa_id', 'id_mahasiswa');
    }
}
