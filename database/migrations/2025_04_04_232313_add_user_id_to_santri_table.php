<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::table('santri', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->onDelete('set null');
            // 'nullable' dan 'onDelete set null' berarti jika user dihapus, kolom user_id di santri jadi null
            // Atau gunakan onDelete('cascade') jika ingin data santri ikut terhapus saat user dihapus
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {
        Schema::table('santri', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};
