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
        Schema::create('kegiatan_ilmiah', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')
                ->references('id_mahasiswa')
                ->on('mahasiswas')
                ->onDelete('cascade');
            $table->string('tahun');
            $table->string('judul_kegiatan');
            $table->string('penyelenggara');
            $table->enum('peran', ['Panitia', 'Peserta', 'Pembicara']);
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
        Schema::dropIfExists('kegiatan_ilmiah');
    }
};
