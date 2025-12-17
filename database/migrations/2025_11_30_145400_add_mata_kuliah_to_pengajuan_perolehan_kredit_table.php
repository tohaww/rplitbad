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
            if (!Schema::hasColumn('pengajuan_perolehan_kredit', 'mata_kuliah')) {
                $table->json('mata_kuliah')->nullable()->after('total_sks');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_perolehan_kredit', function (Blueprint $table) {
            if (Schema::hasColumn('pengajuan_perolehan_kredit', 'mata_kuliah')) {
                $table->dropColumn('mata_kuliah');
            }
        });
    }
};
