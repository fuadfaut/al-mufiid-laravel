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
        Schema::create('ustadzs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nip')->unique()->nullable(); // Nomor Induk Pegawai/Kode Unik
            $table->string('no_hp')->nullable();
            $table->text('alamat')->nullable();
            // Relasi ke tabel users (jika Ustadz bisa login)
            // unique() memastikan 1 user hanya terhubung ke 1 ustadz
            // nullable() & onDelete('set null') jika user dihapus, data ustadz tetap ada
            $table->foreignId('user_id')->nullable()->unique()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ustadzs');
    }
};