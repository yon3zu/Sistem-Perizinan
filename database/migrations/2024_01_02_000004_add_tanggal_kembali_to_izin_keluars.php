<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('izin_keluars', function (Blueprint $table) {
            // Tambah tanggal_kembali setelah tanggal
            $table->date('tanggal_kembali')->nullable()->after('tanggal');
        });

        // Set default tanggal_kembali = tanggal untuk data existing
        DB::statement('UPDATE izin_keluars SET tanggal_kembali = tanggal WHERE tanggal_kembali IS NULL');
    }

    public function down(): void
    {
        Schema::table('izin_keluars', function (Blueprint $table) {
            $table->dropColumn('tanggal_kembali');
        });
    }
};
