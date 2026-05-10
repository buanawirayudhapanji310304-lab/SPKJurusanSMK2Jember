<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PenyakitSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $data = [
            'Asma',
            'Epilepsi',
        ];

        foreach ($data as $namaPenyakit) {
            DB::table('penyakit')->updateOrInsert(
                ['nama_penyakit' => $namaPenyakit],
                [
                    'is_active' => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]
            );
        }
    }
}
