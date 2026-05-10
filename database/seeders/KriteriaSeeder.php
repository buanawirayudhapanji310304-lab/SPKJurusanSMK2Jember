<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // Hapus data lama agar ID dimulai dari 1 secara berurutan
        DB::table('kriteria')->delete();
        DB::statement('ALTER TABLE kriteria AUTO_INCREMENT = 1');

        // Insert berurutan sehingga id 1=C1, id2=C2, ..., id8=C8
        DB::table('kriteria')->insert([
            [
                'kode_kriteria' => 'C1',
                'nama_kriteria' => 'Matematika',
                'is_active'     => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'kode_kriteria' => 'C2',
                'nama_kriteria' => 'Bahasa Indonesia',
                'is_active'     => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'kode_kriteria' => 'C3',
                'nama_kriteria' => 'IPA',
                'is_active'     => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'kode_kriteria' => 'C4',
                'nama_kriteria' => 'Bahasa Inggris',
                'is_active'     => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'kode_kriteria' => 'C5',
                'nama_kriteria' => 'Fisik',
                'is_active'     => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'kode_kriteria' => 'C6',
                'nama_kriteria' => 'Buta Warna',
                'is_active'     => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'kode_kriteria' => 'C7',
                'nama_kriteria' => 'Skor Minat Bakat',
                'is_active'     => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
            [
                'kode_kriteria' => 'C8',
                'nama_kriteria' => 'Penyakit',
                'is_active'     => true,
                'created_at'    => $now,
                'updated_at'    => $now,
            ],
        ]);
    }
}
