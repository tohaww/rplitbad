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
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->string('rt')->nullable()->after('alamat_rumah');
            $table->string('rw')->nullable()->after('rt');
            $table->string('kelurahan_desa')->nullable()->after('rw');
            $table->string('kecamatan')->nullable()->after('kelurahan_desa');
            $table->string('kab_kota')->nullable()->after('kecamatan');
            $table->string('provinsi')->nullable()->after('kab_kota');
            $table->string('kode_pos')->nullable()->after('provinsi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mahasiswas', function (Blueprint $table) {
            $table->dropColumn(['rt', 'rw', 'kelurahan_desa', 'kecamatan', 'kab_kota', 'provinsi', 'kode_pos']);
        });
    }
};
