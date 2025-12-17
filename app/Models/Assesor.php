<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assesor extends Model
{
    use HasFactory;

    protected $table = 'assesor';
    protected $primaryKey = 'id_assesor';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_assesor',
        'nama',
        'password',
    ];
}

