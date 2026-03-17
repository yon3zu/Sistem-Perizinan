<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nip',
        'role',
        'team_id',
        'phone',
        'foto',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function izinDicatat(): HasMany
    {
        return $this->hasMany(IzinKeluar::class, 'dicatat_oleh');
    }

    public function izinDisetujui(): HasMany
    {
        return $this->hasMany(IzinKeluar::class, 'approved_by');
    }

    public function izinKatim(): HasMany
    {
        return $this->hasMany(IzinKeluar::class, 'katim_id');
    }

    public function notifikasi(): HasMany
    {
        return $this->hasMany(Notifikasi::class);
    }

    // Role helpers
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isKatim(): bool
    {
        return $this->role === 'katim';
    }

    public function isSecurity(): bool
    {
        return $this->role === 'security';
    }

    // Deprecated alias kept for backwards compat
    public function isPegawai(): bool
    {
        return $this->role === 'security';
    }

    public function hasRole(string|array $roles): bool
    {
        if (is_string($roles)) {
            return $this->role === $roles;
        }
        return in_array($this->role, $roles);
    }

    public function getRoleLabel(): string
    {
        return match($this->role) {
            'admin'    => 'Administrator',
            'katim'    => 'Ketua Tim',
            'security' => 'Security',
            default    => $this->role,
        };
    }
}
