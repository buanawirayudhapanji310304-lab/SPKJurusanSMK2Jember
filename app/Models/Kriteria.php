<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    protected $table = 'kriteria';

    protected $fillable = [
        'kode_kriteria',
        'nama_kriteria',
        'is_active',
    ];

    protected $casts = [
    'is_active' => 'boolean',
];
    public function jurusanKriteria()
    {
        return $this->hasMany(JurusanKriteria::class);
    }
}