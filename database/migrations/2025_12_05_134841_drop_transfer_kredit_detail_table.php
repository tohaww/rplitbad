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
        Schema::dropIfExists('transfer_kredit_detail');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('transfer_kredit_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mahasiswa_id');
            $table->string('id_matkul', 255);
            $table->text('capaian_pembelajaran')->nullable();
            $table->text('profisiensi')->nullable();
            $table->string('jenis_dokumen')->nullable();
            $table->text('bukti')->nullable();
            $table->timestamps();

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
};
