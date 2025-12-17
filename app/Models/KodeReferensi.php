<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KodeReferensi extends Model
{
    protected $table = 'kode_referensi';

    protected $fillable = [
        'nama_referensi',
        'kode_referensi',
    ];
}
