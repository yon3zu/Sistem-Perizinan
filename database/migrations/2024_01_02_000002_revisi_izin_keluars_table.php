<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('izin_keluars', function (Blueprint $table) {
            // Drop old user_id FK and column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Add new columns
            $table->foreignId('karyawan_id')->after('id')->constrained('karyawan')->cascadeOnDelete();
            $table->foreignId('dicatat_oleh')->after('karyawan_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('katim_id')->nullable()->after('dicatat_oleh')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('izin_keluars', function (Blueprint $table) {
            $table->dropForeign(['katim_id']);
            $table->dropForeign(['dicatat_oleh']);
            $table->dropForeign(['karyawan_id']);
            $table->dropColumn(['katim_id', 'dicatat_oleh', 'karyawan_id']);

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        });
    }
};
