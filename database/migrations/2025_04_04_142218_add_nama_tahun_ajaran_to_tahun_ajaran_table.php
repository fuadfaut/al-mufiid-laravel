<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Import DB

return new class extends Migration {
    public function up(): void {
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            // Pilihan A: Virtual Column (MySQL 5.7.6+ / MariaDB 10.2.5+) - Lebih baik
            // $table->string('nama_tahun_ajaran')->virtualAs("CONCAT(tahun, ' - ', semester)")->after('semester');

            // Pilihan B: Stored Column (perlu diisi manual/otomatis)
             $table->string('nama_tahun_ajaran')->after('semester')->nullable();
        });

        // Jika menggunakan Pilihan B, isi data yang sudah ada (jika perlu)
        // DB::statement("UPDATE tahun_ajaran SET nama_tahun_ajaran = CONCAT(tahun, ' - ', semester)");
    }

    public function down(): void {
        Schema::table('tahun_ajaran', function (Blueprint $table) {
            $table->dropColumn('nama_tahun_ajaran');
        });
    }
};
