<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penyakit extends Model
{
    protected $table = 'penyakit';

    protected $fillable = [
        'nama_penyakit',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function jurusans()
    {
        return $this->belongsToMany(Jurusan::class, 'jurusan_penyakit')
            ->withTimestamps();
    }

    public function tes()
    {
        return $this->hasMany(Tes::class);
    }
}
