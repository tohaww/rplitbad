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
        Schema::create('pengajuan_transfer_kredits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mahasiswa_id')
                ->references('id_mahasiswa')
                ->on('mahasiswas')
                ->onDelete('cascade');
            $table->string('perguruan_tinggi_asal');
            $table->string('jenjang_pendidikan', 50);
            $table->string('program_studi_asal');
            $table->string('nim_asal', 50);
            $table->string('program_studi_tertuju');
            $table->string('lokasi_kuliah_tertuju')->nullable();
            $table->string('status')->default('Draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengajuan_transfer_kredits');
    }
};
