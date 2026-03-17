<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IzinKeluar extends Model
{
    protected $fillable = [
        'karyawan_id',
        'dicatat_oleh',
        'katim_id',
        'tanggal',
        'tanggal_kembali',
        'jam_keluar_rencana',
        'jam_kembali_rencana',
        'jam_keluar_aktual',
        'jam_kembali_aktual',
        'tujuan',
        'keterangan',
        'bukti',
        'status',
        'approved_by',
        'approved_at',
        'catatan_katim',
    ];

    protected function casts(): array
    {
        return [
            'tanggal'          => 'date',
            'tanggal_kembali'  => 'date',
            'approved_at'      => 'datetime',
        ];
    }

    // Relationships
    public function karyawan(): BelongsTo
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function pencatat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dicatat_oleh');
    }

    public function katim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'katim_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Status helpers
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isDisetujui(): bool
    {
        return $this->status === 'disetujui';
    }

    public function isDitolak(): bool
    {
        return $this->status === 'ditolak';
    }

    public function isSelesai(): bool
    {
        return $this->status === 'selesai';
    }

    public function getStatusLabel(): string
    {
        return match($this->status) {
            'pending'   => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
            'selesai'   => 'Selesai',
            default     => $this->status,
        };
    }

    public function getStatusColor(): string
    {
        return match($this->status) {
            'pending'   => 'yellow',
            'disetujui' => 'green',
            'ditolak'   => 'red',
            'selesai'   => 'blue',
            default     => 'gray',
        };
    }

    public function canKonfirmasiKembali(): bool
    {
        return $this->status === 'disetujui';
    }
}
