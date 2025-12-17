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
        Schema::table('detail_pengajuan_perolehan_kredit', function (Blueprint $table) {
            $table->dropColumn('notab');
            $table->string('kode_matkul')->after('course_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detail_pengajuan_perolehan_kredit', function (Blueprint $table) {
            $table->dropColumn('kode_matkul');
            $table->integer('notab')->after('course_id');
        });
    }
};
