<?php

use Illuminate\Database\Migrations\Migration;

// C8 (Penyakit) sekarang ditangani sepenuhnya oleh KriteriaSeeder.
// Migration ini dijadikan no-op agar tidak konflik dengan seeder.
return new class extends Migration
{
    public function up(): void
    {
        // Tidak ada operasi — KriteriaSeeder menangani insert C8.
    }

    public function down(): void
    {
        // Tidak ada operasi.
    }
};
