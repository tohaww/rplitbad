<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Kolom sudah diset menjadi `id_user` di file migrasi awal,
     * jadi migrasi ini tidak perlu melakukan apa-apa. Dibiarkan kosong
     * agar tidak error di SQLite.
     */
    public function up(): void
    {
        //
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
