<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add JSON column to store mata kuliah data
        Schema::table('pengajuan_perolehan_kredit', function (Blueprint $table) {
            $table->json('mata_kuliah')->nullable()->after('total_sks');
        });

        // Migrate data from detail_pengajuan_perolehan_kredit to pengajuan_perolehan_kredit
        $details = DB::table('detail_pengajuan_perolehan_kredit')
            ->select('pengajuan_id', 'course_id', 'kode_matkul')
            ->get()
            ->groupBy('pengajuan_id');

        foreach ($details as $pengajuanId => $detailItems) {
            $mataKuliah = $detailItems->map(function ($item) {
                return [
                    'course_id' => $item->course_id,
                    'kode_matkul' => $item->kode_matkul,
                ];
            })->values()->toArray();

            DB::table('pengajuan_perolehan_kredit')
                ->where('id', $pengajuanId)
                ->update(['mata_kuliah' => json_encode($mataKuliah)]);
        }

        // Drop detail table
        Schema::dropIfExists('detail_pengajuan_perolehan_kredit');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate detail table
        Schema::create('detail_pengajuan_perolehan_kredit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pengajuan_id')->constrained('pengajuan_perolehan_kredit')->onDelete('cascade');
            $table->string('course_id');
            $table->string('kode_matkul');
            $table->timestamps();
        });

        // Migrate data back from JSON to detail table
        $pengajuans = DB::table('pengajuan_perolehan_kredit')
            ->whereNotNull('mata_kuliah')
            ->get();

        foreach ($pengajuans as $pengajuan) {
            $mataKuliah = json_decode($pengajuan->mata_kuliah, true);
            if (is_array($mataKuliah)) {
                foreach ($mataKuliah as $mk) {
                    DB::table('detail_pengajuan_perolehan_kredit')->insert([
                        'pengajuan_id' => $pengajuan->id,
                        'course_id' => $mk['course_id'] ?? $mk['id_matkul'] ?? '',
                        'kode_matkul' => $mk['kode_matkul'] ?? '',
                        'created_at' => $pengajuan->created_at,
                        'updated_at' => $pengajuan->updated_at,
                    ]);
                }
            }
        }

        // Remove JSON column
        Schema::table('pengajuan_perolehan_kredit', function (Blueprint $table) {
            $table->dropColumn('mata_kuliah');
        });
    }
};
