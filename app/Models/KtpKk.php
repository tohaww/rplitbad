<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KtpKk extends Model
{
    use HasFactory;

    protected $table = 'ktp_kk';

    protected $primaryKey = 'id_mahasiswa';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'id_mahasiswa',
        'nama_mahasiswa',
        'foto',
        'ktp',
        'kk',
        'akta',
    ];

    public function getIdAttribute(): mixed
    {
        return $this->attributes['id_mahasiswa'] ?? null;
    }

    public function setIdAttribute($value): void
    {
        $this->attributes['id_mahasiswa'] = $value;
    }

    /**
     * Relasi ke Mahasiswa.
     */
    public function mahasiswa()
    {
        return $this->belongsTo(\App\Models\Mahasiswa::class, 'id_mahasiswa', 'id_mahasiswa');
    }
}
