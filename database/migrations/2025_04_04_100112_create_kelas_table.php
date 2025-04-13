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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas'); // Contoh: Iqro' 1 Pagi, Tahfidz Sore A
            // Relasi ke Ustadz (sebagai Wali Kelas) - Opsional
            // nullOnDelete() berarti jika Ustadz dihapus, kolom ini jadi NULL
            $table->foreignId('ustadz_id')->nullable()->constrained('ustadzs')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};