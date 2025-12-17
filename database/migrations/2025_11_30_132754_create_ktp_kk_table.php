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
        Schema::create('ktp_kk', function (Blueprint $table) {
            // id_mahasiswa sekaligus primary key dan foreign key ke tabel mahasiswas
            $table->unsignedBigInteger('id_mahasiswa')->primary();
            $table->string('nama_mahasiswa');
            $table->string('foto')->nullable();
            $table->string('ktp')->nullable();
            $table->string('kk')->nullable();
            $table->string('akta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ktp_kk');
    }
};
