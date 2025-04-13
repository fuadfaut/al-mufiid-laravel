<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('tahun'); // Contoh: "2023/2024"
            $table->enum('semester', ['Ganjil', 'Genap']);
            $table->boolean('aktif')->default(false); // Menandakan periode aktif
            // Pastikan kombinasi tahun dan semester unik
            $table->unique(['tahun', 'semester']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};