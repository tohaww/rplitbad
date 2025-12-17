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
        // This migration updates the JSON structure in mata_kuliah column
        // to include id_matkul and nama_matkul fields
        // No schema changes needed as we're updating JSON data structure
        // The controller will handle saving id_matkul and nama_matkul in the JSON
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No rollback needed
    }
};
