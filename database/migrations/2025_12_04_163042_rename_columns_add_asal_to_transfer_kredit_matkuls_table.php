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
        // Rename columns untuk SQLite compatibility
        // SQLite tidak mendukung RENAME COLUMN langsung, jadi kita perlu recreate kolom
        
        // Backup data ke temporary columns
        Schema::table('transfer_kredit_matkuls', function (Blueprint $table) {
            $table->string('kode_matkul_asal')->nullable()->after('kode_matkul');
            $table->string('nama_matkul_asal')->nullable()->after('nama_matkul');
            $table->unsignedTinyInteger('sks_asal')->nullable()->after('sks');
            $table->string('nilai_asal', 5)->nullable()->after('nilai');
        });

        // Copy data dari kolom lama ke kolom baru
        \DB::statement('UPDATE transfer_kredit_matkuls SET kode_matkul_asal = kode_matkul');
        \DB::statement('UPDATE transfer_kredit_matkuls SET nama_matkul_asal = nama_matkul');
        \DB::statement('UPDATE transfer_kredit_matkuls SET sks_asal = sks');
        \DB::statement('UPDATE transfer_kredit_matkuls SET nilai_asal = nilai');

        // Drop kolom lama dan rename kolom baru
        Schema::table('transfer_kredit_matkuls', function (Blueprint $table) {
            $table->dropColumn(['kode_matkul', 'nama_matkul', 'sks', 'nilai']);
        });

        // Rename kolom baru menjadi nama final (dengan recreate karena SQLite limitation)
        Schema::table('transfer_kredit_matkuls', function (Blueprint $table) {
            $table->string('kode_matkul_asal')->nullable(false)->change();
            $table->string('nama_matkul_asal')->nullable(false)->change();
            $table->unsignedTinyInteger('sks_asal')->nullable(false)->change();
            $table->string('nilai_asal', 5)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert: create old columns
        Schema::table('transfer_kredit_matkuls', function (Blueprint $table) {
            $table->string('kode_matkul')->nullable()->after('kode_matkul_asal');
            $table->string('nama_matkul')->nullable()->after('nama_matkul_asal');
            $table->unsignedTinyInteger('sks')->nullable()->after('sks_asal');
            $table->string('nilai', 5)->nullable()->after('nilai_asal');
        });

        // Copy data back
        \DB::statement('UPDATE transfer_kredit_matkuls SET kode_matkul = kode_matkul_asal');
        \DB::statement('UPDATE transfer_kredit_matkuls SET nama_matkul = nama_matkul_asal');
        \DB::statement('UPDATE transfer_kredit_matkuls SET sks = sks_asal');
        \DB::statement('UPDATE transfer_kredit_matkuls SET nilai = nilai_asal');

        // Drop new columns
        Schema::table('transfer_kredit_matkuls', function (Blueprint $table) {
            $table->dropColumn(['kode_matkul_asal', 'nama_matkul_asal', 'sks_asal', 'nilai_asal']);
        });
    }
};
