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
        Schema::table('transfer_kredit_matkuls', function (Blueprint $table) {
            if (!Schema::hasColumn('transfer_kredit_matkuls', 'mahasiswa_id')) {
                $table->unsignedBigInteger('mahasiswa_id')->nullable()->after('pengajuan_id');
            }
        });

        // Tambahkan foreign key jika belum ada
        if (!Schema::hasColumn('transfer_kredit_matkuls', 'mahasiswa_id')) {
            Schema::table('transfer_kredit_matkuls', function (Blueprint $table) {
                $table->foreign('mahasiswa_id')
                    ->references('id_mahasiswa')
                    ->on('mahasiswas')
                    ->onDelete('cascade');
            });
        }

        // Update data yang sudah ada dengan mahasiswa_id dari pengajuan terkait
        // Menggunakan sintaks SQLite yang kompatibel
        \DB::statement('
            UPDATE transfer_kredit_matkuls 
            SET mahasiswa_id = (
                SELECT mahasiswa_id 
                FROM pengajuan_transfer_kredits 
                WHERE pengajuan_transfer_kredits.id = transfer_kredit_matkuls.pengajuan_id
            )
            WHERE mahasiswa_id IS NULL
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_kredit_matkuls', function (Blueprint $table) {
            $table->dropForeign(['mahasiswa_id']);
            $table->dropColumn('mahasiswa_id');
        });
    }
};
