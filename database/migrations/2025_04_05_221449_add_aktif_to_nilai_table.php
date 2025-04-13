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
        Schema::table('nilai', function (Blueprint $table) {
            $table->boolean('aktif')->default(true)->after('catatan');
        });
    }
    
    public function down(): void
    {
        Schema::table('nilai', function (Blueprint $table) {
            $table->dropColumn('aktif');
        });
    }
};
