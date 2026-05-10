<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tes', function (Blueprint $table) {
            $table->foreignId('penyakit_id')
                ->nullable()
                ->after('buta_warna')
                ->constrained('penyakit')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('tes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('penyakit_id');
        });
    }
};
