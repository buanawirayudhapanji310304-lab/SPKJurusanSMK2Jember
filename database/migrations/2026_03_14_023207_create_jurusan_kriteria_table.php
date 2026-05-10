<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurusan_kriteria', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jurusan_id')
                ->constrained('jurusan')
                ->cascadeOnDelete();

            $table->foreignId('kriteria_id')
                ->constrained('kriteria')
                ->cascadeOnDelete();

            $table->decimal('bobot', 5, 4);

            $table->boolean('wajib_lolos')->default(false);
            $table->decimal('nilai_min', 8, 2)->nullable();
            $table->decimal('nilai_max', 8, 2)->nullable();

            $table->timestamps();

            $table->unique(['jurusan_id', 'kriteria_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jurusan_kriteria');
    }
};