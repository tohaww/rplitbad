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
        // Di SQLite, migrasi ini mungkin tidak diperlukan setelah migrate:fresh,
        // jadi biarkan kosong untuk menghindari error index.
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada rollback khusus karena up() kosong.
    }
};
