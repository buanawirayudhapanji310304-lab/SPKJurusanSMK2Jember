<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jurusan extends Model
{
    protected $table = 'jurusan';

    protected $fillable = [
        'nama_jurusan',
        'is_active',
    ];

    protected $casts = [
    'is_active' => 'boolean',
    ];
    public function guruBk()
    {
        return $this->hasMany(GuruBk::class);
    }

    public function jurusanKriteria()
    {
        return $this->hasMany(JurusanKriteria::class);
    }

    public function penyakit()
    {
        return $this->belongsToMany(Penyakit::class, 'jurusan_penyakit')
            ->withTimestamps();
    }

    public function informasiJurusan()
    {
        return $this->hasOne(InformasiJurusan::class);
    }

    public function prospekKerja()
    {
        return $this->hasMany(ProspekKerja::class);
    }

    public function artikelJurusan()
    {
        return $this->hasMany(ArtikelJurusan::class);
    }
}
