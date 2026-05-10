<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class JurusanKriteria extends Model
{
    protected $table = 'jurusan_kriteria';

    protected $fillable = [
        'jurusan_id',
        'kriteria_id',
        'bobot',
        'wajib_lolos',  
        'nilai_min',   
        'nilai_max', 
    ];
    protected $casts = [
        'wajib_lolos' => 'boolean',
        'nilai_min'   => 'decimal:2',
        'nilai_max'   => 'decimal:2',
    ];
    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class);
    }

    public function kriteria()
    {
        return $this->belongsTo(Kriteria::class);
    }
}