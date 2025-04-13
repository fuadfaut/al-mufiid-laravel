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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            // Foreign keys
            // onDelete('cascade') berarti jika data induk dihapus, nilai terkait juga dihapus
            $table->foreignId('santri_id')->constrained('santri')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            // Ustadz yang menginput nilai
            // onDelete('set null') jika ustadz dihapus, nilai tetap ada tapi penanggung jawab jadi null
            $table->foreignId('ustadz_id')->nullable()->constrained('ustadzs')->nullOnDelete();

            // Kolom nilai
            $table->decimal('nilai_angka', 5, 2)->nullable(); // Opsional, contoh: 85.50 (precision=5, scale=2)
            $table->string('nilai_predikat'); // Wajib, contoh: A, B, C / Istimewa, Baik Sekali, Baik, Cukup
            $table->text('catatan')->nullable(); // Opsional

            // Pastikan Santri tidak punya nilai duplikat untuk mapel & tahun ajaran yang sama
            $table->unique(['santri_id', 'mata_pelajaran_id', 'tahun_ajaran_id']);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};