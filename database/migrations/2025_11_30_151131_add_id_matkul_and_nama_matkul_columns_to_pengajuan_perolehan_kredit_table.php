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
        Schema::table('pengajuan_perolehan_kredit', function (Blueprint $table) {
            if (!Schema::hasColumn('pengajuan_perolehan_kredit', 'id_matkul')) {
                $table->string('id_matkul')->nullable()->after('mata_kuliah');
            }
            if (!Schema::hasColumn('pengajuan_perolehan_kredit', 'nama_matkul')) {
                $table->string('nama_matkul')->nullable()->after('id_matkul');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_perolehan_kredit', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_perolehan_kredit', 'nama_matkul')) {
                $table->dropColumn('nama_matkul');
            }
            if (Schema::hasColumn('pengajuan_perolehan_kredit', 'id_matkul')) {
                $table->dropColumn('id_matkul');
            }
        });
    }
};
