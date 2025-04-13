<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Menambahkan kolom role
        'santri_id', // Menambahkan kolom santri_id
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
// Removed duplicate santri method to avoid redeclaration error

public function ustadz(): HasOne
{
    return $this->hasOne(Ustadz::class); // Jika ada model Ustadz terpisah
}


public function santri(): BelongsTo
{
   return $this->belongsTo(Santri::class);
}

/**
* Cek apakah user adalah Admin.
*/
public function isAdmin(): bool
{
   return $this->role === 'admin';
}

/**
* Cek apakah user adalah Ustadz.
*/
public function isUstadz(): bool
{
   return $this->role === 'ustadz';
}

/**
* Cek apakah user adalah Santri.
*/
public function isSantri(): bool
{
   return $this->role === 'santri';
}

/**
 * Implementasi Filament v3 (Opsional, jika perlu custom logic akses)
 * Cek apakah user bisa akses Panel Filament
 */
// public function canAccessPanel(\Filament\Panel $panel): bool
// {
//     // Izinkan jika role adalah admin atau ustadz
//     return $this->isAdmin() || $this->isUstadz();
// }
}
