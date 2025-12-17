<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('pengajuan_perolehan_kredit')
            ->where('status', 'Menunggu')
            ->update(['status' => 'Sudah Diajukan']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('pengajuan_perolehan_kredit')
            ->where('status', 'Sudah Diajukan')
            ->update(['status' => 'Menunggu']);
    }
};
