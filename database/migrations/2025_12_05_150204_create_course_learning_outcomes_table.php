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
        Schema::create('course_learning_outcomes', function (Blueprint $table) {
            $table->id();
            $table->string('id_matkul', 255);
            $table->text('capaian_pembelajaran')->comment('Kemampuan Akhir Yang Diharapkan/ Capaian Pembelajaran Mata Kuliah');
            $table->integer('urutan')->default(1)->comment('Urutan point pertanyaan');
            $table->timestamps();

            // Foreign key
            $table->foreign('id_matkul')
                ->references('id_matkul')
                ->on('courses')
                ->onDelete('cascade');
            
            // Index untuk performa query
            $table->index('id_matkul');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_learning_outcomes');
    }
};
