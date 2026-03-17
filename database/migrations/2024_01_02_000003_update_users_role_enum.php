<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // SQLite doesn't support modifying enum, so we use a workaround
        // For MySQL, alter the column directly
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            // SQLite: column is just string, no enum enforcement - update values
            DB::table('users')->where('role', 'pegawai')->update(['role' => 'security']);
        } else {
            // MySQL: modify enum
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('security', 'katim', 'admin') NOT NULL DEFAULT 'security'");
            DB::table('users')->where('role', 'pegawai')->update(['role' => 'security']);
        }
    }

    public function down(): void
    {
        $driver = DB::connection()->getDriverName();

        if ($driver === 'sqlite') {
            DB::table('users')->where('role', 'security')->update(['role' => 'pegawai']);
        } else {
            DB::table('users')->where('role', 'security')->update(['role' => 'pegawai']);
            DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('pegawai', 'katim', 'admin') NOT NULL DEFAULT 'pegawai'");
        }
    }
};
