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
        Schema::create('form_evaluasi', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('id_matkul', 255);
            $table->text('capaian_pembelajaran')->nullable()->comment('Kemampuan Akhir Yang Diharapkan/ Capaian Pembelajaran Mata Kuliah');
            $table->text('profisiensi')->nullable()->comment('Profisiensi pengetahuan dan keterampilan saat ini');
            $table->string('jenis_dokumen')->nullable();
            $table->text('bukti')->nullable()->comment('Bukti yang disampaikan');
            $table->timestamps();

            // Foreign keys
            $table->foreign('mahasiswa_id')
                ->references('id_mahasiswa')
                ->on('mahasiswas')
                ->onDelete('cascade');
            
            $table->foreign('id_matkul')
                ->references('id_matkul')
                ->on('courses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_evaluasi');
    }
};
