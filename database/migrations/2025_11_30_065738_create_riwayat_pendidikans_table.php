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
        Schema::create('riwayat_pendidikans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')
                ->references('id_mahasiswa')
                ->on('mahasiswas')
                ->onDelete('cascade');
            $table->string('nama_sekolah');
            $table->string('tahun_lulus');
            $table->string('jurusan_program_studi');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pendidikans');
    }
};
