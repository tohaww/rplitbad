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
        Schema::create('transfer_kredit_matkuls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')
                ->references('id')
                ->on('pengajuan_transfer_kredits')
                ->onDelete('cascade');
            $table->string('notab')->nullable();
            $table->string('kode_matkul');
            $table->string('nama_matkul');
            $table->unsignedTinyInteger('sks')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_kredit_matkuls');
    }
};
