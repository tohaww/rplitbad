<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mahasiswa extends Model
{
    use HasFactory;

    /**
     * Primary key column now uses id_mahasiswa.
     */
    protected $primaryKey = 'id_mahasiswa';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'user_id',
        'nama',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'status_pernikahan',
        'agama',
        'pekerjaan',
        'alamat_kantor',
        'telepon_fax',
        'alamat_rumah',
        'rt',
        'rw',
        'kelurahan_desa',
        'kecamatan',
        'kab_kota',
        'provinsi',
        'kode_pos',
        'telp_hp',
        'asal_perguruan_tinggi',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_lahir' => 'date',
        ];
    }

    /**
     * Get the user that owns the mahasiswa.
     */
    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id', 'id_user');
    }

    /**
     * Keep legacy $mahasiswa->id references operational.
     */
    public function getIdAttribute(): mixed
    {
        return $this->attributes['id_mahasiswa'] ?? null;
    }

    public function setIdAttribute($value): void
    {
        $this->attributes['id_mahasiswa'] = $value;
    }
}
