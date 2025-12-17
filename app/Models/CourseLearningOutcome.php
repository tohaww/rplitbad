<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLearningOutcome extends Model
{
    protected $fillable = [
        'id_matkul',
        'capaian_pembelajaran',
        'urutan',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class, 'id_matkul', 'id_matkul');
    }
}
