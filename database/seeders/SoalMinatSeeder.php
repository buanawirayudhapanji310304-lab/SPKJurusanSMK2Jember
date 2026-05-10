<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class SoalMinatSeeder extends Seeder
{
    public function run(): void
    {
        $table = 'soal_minat';

        // Pastikan tabel ada
        if (!Schema::hasTable($table)) {
            $this->command?->error("❌ Tabel {$table} tidak ditemukan. Seeder dibatalkan.");
            return;
        }

        // Tentukan nama kolom untuk teks soal (yang benar-benar ada di DB)
        $textColumn = null;
        foreach (['pertanyaan', 'soal', 'teks'] as $col) {
            if (Schema::hasColumn($table, $col)) {
                $textColumn = $col;
                break;
            }
        }

        if (!$textColumn) {
            $this->command?->error("❌ Tidak ditemukan kolom teks soal (pertanyaan/soal/teks) di tabel {$table}.");
            return;
        }

        // Kolom status aktif (kalau ada)
        $hasIsActive = Schema::hasColumn($table, 'is_active');

        // 10 soal yang kamu tulis di blade
        $soalList = [
            'Saya senang memperbaiki atau merakit benda/alat.',
            'Saya tertarik menggunakan komputer dan teknologi jaringan.',
            'Saya suka menggambar atau membuat desain visual.',
            'Saya lebih nyaman belajar praktik dibanding teori.',
            'Saya tertarik pada mesin kendaraan atau otomotif.',
            'Saya tertarik membantu orang lain memecahkan masalah mereka.',
            'Saya tertarik dengan instalasi listrik atau elektronika.',
            'Saya senang bekerja secara teliti dan detail.',
            'Saya tertarik membuat karya kreatif digital seperti poster atau video.',
            'Saya merasa memiliki minat yang kuat terhadap jurusan yang berhubungan dengan teknik',
        ];

        $inserted = 0;
        $updated  = 0;

        DB::beginTransaction();
        try {
            foreach ($soalList as $txt) {
                // updateOrInsert tanpa model (lebih aman kalau fillable/guarded belum rapi)
                $where = [$textColumn => $txt];

                $data = [];
                if ($hasIsActive) $data['is_active'] = true;

                // kalau record sudah ada -> update is_active; kalau belum -> insert
                $exists = DB::table($table)->where($where)->exists();

                DB::table($table)->updateOrInsert($where, $data);

                if ($exists) $updated++; else $inserted++;
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $this->command?->error("❌ Seeder gagal: " . $e->getMessage());
            return;
        }

        $this->command?->info("✅ SoalMinatSeeder selesai. Inserted: {$inserted}, Updated: {$updated}. Kolom teks: {$textColumn}");
    }
}
