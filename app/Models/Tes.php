<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tes extends Model
{
    protected $table = 'tes';

    protected $fillable = [
        'siswa_id',

        'nilai_matematika',
        'nilai_bahasa_indonesia',
        'nilai_bahasa_inggris',
        'nilai_ipa',

        'skor_minat_bakat',
        'tinggi_badan',
        'berat_badan',
        'buta_warna',
        'penyakit_id',
        'minat_jurusan_1_id',
        'minat_jurusan_2_id',
    ];

    protected $casts = [
        'buta_warna'             => 'boolean',
        'nilai_matematika'       => 'decimal:2',
        'nilai_bahasa_indonesia' => 'decimal:2',
        'nilai_bahasa_inggris'   => 'decimal:2',
        'nilai_ipa'              => 'decimal:2',
        'tinggi_badan'           => 'decimal:2',
        'berat_badan'            => 'decimal:2',
        'skor_minat_bakat'       => 'integer',
        'penyakit_id'            => 'integer',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function jawabanMinat()
    {
        return $this->hasMany(JawabanMinat::class);
    }

    public function hasilSaw()
    {
        return $this->hasMany(HasilSaw::class);
    }

    public function penyakit()
    {
        return $this->belongsTo(Penyakit::class);
    }

    public function tesPDF()
    {
        return $this->hasOne(TesPdf::class);
    }

    public function rekomendasiTeratas()
    {
        return $this->hasOne(HasilSaw::class, 'tes_id')
            ->where('peringkat', 1);
    }

    public function minatJurusan1()
    {
        return $this->belongsTo(Jurusan::class, 'minat_jurusan_1_id');
    }

    public function minatJurusan2()
    {
        return $this->belongsTo(Jurusan::class, 'minat_jurusan_2_id');
    }
}
