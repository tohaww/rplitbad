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
        Schema::create('pelatihan_profesionals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')
                ->references('id_mahasiswa')
                ->on('mahasiswas')
                ->onDelete('cascade');
            $table->string('tahun');
            $table->string('jenis_pelatihan');
            $table->string('penyelenggara');
            $table->string('jangka_waktu');
            $table->string('file_path')->nullable();
            $table->string('keterangan_file')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelatihan_profesionals');
    }
};
