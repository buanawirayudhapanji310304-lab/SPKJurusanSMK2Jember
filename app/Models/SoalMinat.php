<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SoalMinat extends Model
{
    protected $table = 'soal_minat';

    protected $fillable = ['pertanyaan', 'is_active'];

protected $casts = [
    'is_active' => 'boolean',
    ];
    public function jawaban()
    {
        return $this->hasMany(JawabanMinat::class);
    }
}
