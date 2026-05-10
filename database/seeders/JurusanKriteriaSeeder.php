<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JurusanKriteriaSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $kriteria = DB::table('kriteria')->pluck('id', 'kode_kriteria');
        $jurusan  = DB::table('jurusan')->pluck('id', 'nama_jurusan');

        // Catatan bobot:
        // C8 = Penyakit, konsep sama seperti C6 (Buta Warna) namun pilihan bisa banyak.
        // Bobot C1-C7 di-scale *0.95, C8 tetap 0.05 agar total per jurusan = 1.00
        $bobotPerJurusan = [

            'Teknik Alat Berat' => [
                'C1' => ['bobot' => 0.20, 'min' => 80],
                'C2' => ['bobot' => 0.05,],
                'C3' => ['bobot' => 0.20,],
                'C4' => ['bobot' => 0.10, 'min' => 80],
                'C5' => ['bobot' => 0.20, 'wajib' => true, 'min' => 100],
                'C6' => ['bobot' => 0.15, 'wajib' => true, 'min' => 1.00],
                'C7' => ['bobot' => 0.05,],
                'C8' => ['bobot' => 0.05, 'min' => 1.00],
            ],

            'Teknik Kendaraan Ringan (Otomotif)' => [
                'C1' => ['bobot' => 0.20, 'min' => 80],
                'C2' => ['bobot' => 0.05],
                'C3' => ['bobot' => 0.20,],
                'C4' => ['bobot' => 0.10, 'min' => 80],
                'C5' => ['bobot' => 0.20, 'min' => 70, 'wajib' => true, 'min' => 70],
                'C6' => ['bobot' => 0.15, 'wajib' => true, 'min' => 1.00],
                'C7' => ['bobot' => 0.05,],
                'C8' => ['bobot' => 0.05, 'wajib' => true, 'min' => 1.00],
            ],

            'Teknik Sepeda Motor' => [
                'C1' => ['bobot' => 0.20, 'min' => 80],
                'C2' => ['bobot' => 0.05],
                'C3' => ['bobot' => 0.20,],
                'C4' => ['bobot' => 0.10, 'min' => 80],
                'C5' => ['bobot' => 0.20, 'wajib' => true, 'min' => 70],
                'C6' => ['bobot' => 0.15, 'wajib' => true, 'min' => 1.00],
                'C7' => ['bobot' => 0.05,],
                'C8' => ['bobot' => 0.05, 'wajib' => true, 'min' => 1.00],
            ],

            'Teknik Pemesinan' => [
                'C1' => ['bobot' => 0.25, 'min' => 80,'wajib' => true],
                'C2' => ['bobot' => 0.10],
                'C3' => ['bobot' => 0.25],
                'C4' => ['bobot' => 0.15, 'min' => 80],
                'C5' => ['bobot' => 0.05,],
                'C6' => ['bobot' => 0.05,],
                'C7' => ['bobot' => 0.10],
                'C8' => ['bobot' => 0.05, 'wajib' => true, 'min' => 1.00],
            ],

            'Teknik Mekatronika' => [
                'C1' => ['bobot' => 0.20, 'min' => 80],
                'C2' => ['bobot' => 0.05],
                'C3' => ['bobot' => 0.25,],
                'C4' => ['bobot' => 0.10, 'min' => 80],
                'C5' => ['bobot' => 0.10],
                'C6' => ['bobot' => 0.20, 'wajib' => true, 'min' => 1.00],
                'C7' => ['bobot' => 0.05,],
                'C8' => ['bobot' => 0.05],
            ],

            'Teknik Konstruksi & Perumahan' => [
                'C1' => ['bobot' => 0.20, 'min' => 80],
                'C2' => ['bobot' => 0.10],
                'C3' => ['bobot' => 0.25],
                'C4' => ['bobot' => 0.15, 'min' => 80],
                'C5' => ['bobot' => 0.05,],
                'C6' => ['bobot' => 0.05],
                'C7' => ['bobot' => 0.15,],
                'C8' => ['bobot' => 0.05],
            ],

            'Desain Pemodelan & Informasi Bangunan (DPIB)' => [
                'C1' => ['bobot' => 0.20, 'min' => 80],
                'C2' => ['bobot' => 0.10],
                'C3' => ['bobot' => 0.15],
                'C4' => ['bobot' => 0.10, 'min' => 80],
                'C5' => ['bobot' => 0.05,],
                'C6' => ['bobot' => 0.20, 'wajib' => true, 'min' => 1.00 ],
                'C7' => ['bobot' => 0.15,],
                'C8' => ['bobot' => 0.05],
            ],

            'Teknik Instalasi Listrik' => [
                'C1' => ['bobot' => 0.20, 'min' => 80],
                'C2' => ['bobot' => 0.05],
                'C3' => ['bobot' => 0.20, ],
                'C4' => ['bobot' => 0.15],
                'C5' => ['bobot' => 0.10,'wajib' => true, 'min' => 100 ],
                'C6' => ['bobot' => 0.20, 'wajib' => true, 'min' => 1.00],
                'C7' => ['bobot' => 0.05,],
                'C8' => ['bobot' => 0.05,'wajib' => true, 'min' => 1.00], 
            ],

            'Teknik Pembangkit Tenaga Listrik' => [
                'C1' => ['bobot' => 0.25, 'min' => 80],
                'C2' => ['bobot' => 0.05],
                'C3' => ['bobot' => 0.25],
                'C4' => ['bobot' => 0.15, 'min' => 80],
                'C5' => ['bobot' => 0.05],
                'C6' => ['bobot' => 0.15, 'wajib' => true, 'min' => 1.00],
                'C7' => ['bobot' => 0.05],
                'C8' => ['bobot' => 0.05, 'wajib' => true, 'min' => 1.00],
            ],

            'Teknik Audio Video' => [
                'C1' => ['bobot' => 0.15, 'min' => 80],
                'C2' => ['bobot' => 0.10],
                'C3' => ['bobot' => 0.15],
                'C4' => ['bobot' => 0.20, 'min' => 80],
                'C5' => ['bobot' => 0.05],
                'C6' => ['bobot' => 0.20, 'wajib' => true, 'min' => 1.00],
                'C7' => ['bobot' => 0.10],
                'C8' => ['bobot' => 0.05],
            ],

            'Teknik Komputer & Jaringan (TKJ)' => [
                'C1' => ['bobot' => 0.20, 'min' => 80],
                'C2' => ['bobot' => 0.10],
                'C3' => ['bobot' => 0.15],
                'C4' => ['bobot' => 0.20, 'min' => 80],
                'C5' => ['bobot' => 0.05],
                'C6' => ['bobot' => 0.10],
                'C7' => ['bobot' => 0.15],
                'C8' => ['bobot' => 0.05],
            ],

            'Desain Komunikasi Visual (DKV)' => [
                'C1' => ['bobot' => 0.05],
                'C2' => ['bobot' => 0.10],
                'C3' => ['bobot' => 0.05],
                'C4' => ['bobot' => 0.15],
                'C5' => ['bobot' => 0.20,  'min' => 70],
                'C6' => ['bobot' => 0.20, 'wajib' => true, 'min' => 1.00],
                'C7' => ['bobot' => 0.20],
                'C8' => ['bobot' => 0.05],
            ],
        ];

        $insertData = [];

        foreach ($bobotPerJurusan as $namaJurusan => $bobotKriteria) {
            if (!isset($jurusan[$namaJurusan])) continue;

            foreach ($bobotKriteria as $kodeKriteria => $data) {
                if (!isset($kriteria[$kodeKriteria])) continue;

                $insertData[] = [
                    'jurusan_id'  => $jurusan[$namaJurusan],
                    'kriteria_id' => $kriteria[$kodeKriteria],
                    'bobot'       => $data['bobot'],
                    'wajib_lolos' => $data['wajib'] ?? false,
                    'nilai_min'   => $data['min'] ?? null,
                    'nilai_max'   => $data['max'] ?? null,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ];
            }
        }

        DB::table('jurusan_kriteria')->upsert(
            $insertData,
            ['jurusan_id', 'kriteria_id'],
            ['bobot', 'wajib_lolos', 'nilai_min', 'nilai_max', 'updated_at']
        );
    }
}