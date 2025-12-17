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
        Schema::create('mahasiswas', function (Blueprint $table) {
            $table->id('id_mahasiswa');
            $table->string('nama');
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan'])->nullable();
            $table->enum('status_pernikahan', ['Belum Menikah', 'Menikah', 'Cerai'])->nullable();
            $table->string('agama')->nullable();
            $table->string('pekerjaan')->nullable();
            $table->text('alamat_kantor')->nullable();
            $table->string('telepon_fax')->nullable();
            $table->text('alamat_rumah')->nullable();
            $table->string('telp_hp')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswas');
    }
};
