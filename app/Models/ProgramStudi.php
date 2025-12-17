<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramStudi extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'id',
        'kode_prodi',
        'nama_prodi',
    ];
}
