<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id_matkul';
    
    protected $fillable = [
        'id_matkul',
        'kode_matkul',
        'nama_matkul',
        'prodi',
        'sks',
        'deskripsi',
    ];
}
