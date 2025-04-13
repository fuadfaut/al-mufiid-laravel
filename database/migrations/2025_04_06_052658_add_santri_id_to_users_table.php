<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSantriIdToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Tambahkan kolom santri_id
            $table->foreignId('santri_id')
                ->nullable()
                ->constrained('santri')
                ->onDelete('set null');

            // Opsional: Membuat unique constraint
            $table->unique('santri_id');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['santri_id']);
            $table->dropForeign(['santri_id']);
            $table->dropColumn('santri_id');
        });
    }
}