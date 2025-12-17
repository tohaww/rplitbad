<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPengajuanPerolehanKredit extends Model
{
    use HasFactory;

    protected $table = 'detail_pengajuan_perolehan_kredit';

    protected $fillable = [
        'pengajuan_id',
        'course_id',
        'kode_matkul',
    ];

    /**
     * Get the pengajuan that owns the detail.
     */
    public function pengajuan()
    {
        return $this->belongsTo(\App\Models\PengajuanPerolehanKredit::class, 'pengajuan_id');
    }

    /**
     * Get the course.
     */
    public function course()
    {
        return $this->belongsTo(\App\Models\Course::class, 'course_id', 'id_matkul');
    }
}
