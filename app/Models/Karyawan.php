<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Karyawan extends Model
{
    protected $table = 'karyawan';

    protected $fillable = [
        'nama',
        'nip',
        'jabatan',
        'team_id',
        'phone',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function izinKeluars(): HasMany
    {
        return $this->hasMany(IzinKeluar::class);
    }

    // Helpers
    public function izinAktif()
    {
        return $this->izinKeluars()
            ->where('status', 'disetujui')
            ->whereDate('tanggal', today())
            ->whereNull('jam_kembali_aktual')
            ->first();
    }

    public function sedangDiLuar(): bool
    {
        return $this->izinAktif() !== null;
    }
}
