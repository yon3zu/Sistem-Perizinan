<?php

namespace Database\Seeders;

use App\Models\Karyawan;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'name'      => 'Administrator',
            'nip'       => 'ADMIN001',
            'email'     => 'admin@perizinan.local',
            'password'  => Hash::make('admin123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        // Create Security
        User::create([
            'name'      => 'Security 1',
            'nip'       => 'SEC001',
            'email'     => 'sec001@perizinan.local',
            'password'  => Hash::make('security123'),
            'role'      => 'security',
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Security 2',
            'nip'       => 'SEC002',
            'email'     => 'sec002@perizinan.local',
            'password'  => Hash::make('security123'),
            'role'      => 'security',
            'is_active' => true,
        ]);

        // Create Teams
        $teamAlpha = Team::create(['nama' => 'Tim Alpha']);
        $teamBeta  = Team::create(['nama' => 'Tim Beta']);

        // Create Katim (Team Leaders)
        User::create([
            'name'      => 'Kepala Tim Alpha',
            'nip'       => 'KATIM001',
            'email'     => 'katim001@perizinan.local',
            'password'  => Hash::make('katim123'),
            'role'      => 'katim',
            'team_id'   => $teamAlpha->id,
            'is_active' => true,
        ]);

        User::create([
            'name'      => 'Kepala Tim Beta',
            'nip'       => 'KATIM002',
            'email'     => 'katim002@perizinan.local',
            'password'  => Hash::make('katim123'),
            'role'      => 'katim',
            'team_id'   => $teamBeta->id,
            'is_active' => true,
        ]);

        // Create Karyawan (bukan users, tidak bisa login)
        $karyawanData = [
            ['nama' => 'Ahmad Susanto',  'nip' => 'KAR001', 'jabatan' => 'Staff',   'team_id' => $teamAlpha->id],
            ['nama' => 'Budi Prasetyo',  'nip' => 'KAR002', 'jabatan' => 'Staff',   'team_id' => $teamAlpha->id],
            ['nama' => 'Citra Dewi',     'nip' => 'KAR003', 'jabatan' => 'Staff',   'team_id' => $teamAlpha->id],
            ['nama' => 'Diana Sari',     'nip' => 'KAR004', 'jabatan' => 'Staff',   'team_id' => $teamAlpha->id],
            ['nama' => 'Eko Santoso',    'nip' => 'KAR005', 'jabatan' => 'Staff',   'team_id' => $teamBeta->id],
            ['nama' => 'Fitri Rahayu',   'nip' => 'KAR006', 'jabatan' => 'Staff',   'team_id' => $teamBeta->id],
            ['nama' => 'Gunawan',        'nip' => 'KAR007', 'jabatan' => 'Staff',   'team_id' => $teamBeta->id],
            ['nama' => 'Hana Pertiwi',   'nip' => 'KAR008', 'jabatan' => 'Staff',   'team_id' => $teamBeta->id],
        ];

        foreach ($karyawanData as $data) {
            Karyawan::create(array_merge($data, ['is_active' => true]));
        }

        $this->command->info('Seeder berhasil dijalankan!');
        $this->command->table(
            ['Role', 'NIP', 'Password'],
            [
                ['Admin',        'ADMIN001', 'admin123'],
                ['Security 1',   'SEC001',   'security123'],
                ['Security 2',   'SEC002',   'security123'],
                ['Katim Alpha',  'KATIM001', 'katim123'],
                ['Katim Beta',   'KATIM002', 'katim123'],
            ]
        );
        $this->command->info('8 karyawan (bukan user) berhasil dibuat: KAR001 - KAR008');
    }
}
