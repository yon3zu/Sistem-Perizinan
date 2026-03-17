<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('nip')->unique()->nullable()->after('name');
            $table->enum('role', ['pegawai', 'katim', 'admin'])->default('pegawai')->after('nip');
            $table->foreignId('team_id')->nullable()->constrained('teams')->nullOnDelete()->after('role');
            $table->string('phone')->nullable()->after('team_id');
            $table->string('foto')->nullable()->after('phone');
            $table->boolean('is_active')->default(true)->after('foto');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['team_id']);
            $table->dropColumn(['nip', 'role', 'team_id', 'phone', 'foto', 'is_active']);
        });
    }
};
