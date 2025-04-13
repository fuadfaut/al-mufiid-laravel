<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Santri extends Model
{
    use HasFactory;

    protected $table = 'santri'; // Eksplisit nama tabel

    protected $fillable = [
        'nis', 'nama_lengkap', 'nama_panggilan', 'tempat_lahir', 'tanggal_lahir',
        'jenis_kelamin', 'kelas_id', 'nama_ayah', 'nama_ibu', 'alamat',
        'no_hp_ortu', 'tanggal_masuk', 'aktif', 'user_id', 'is_confirmed',
    ];


    protected $casts = [
        'tanggal_lahir' => 'date',
        'tanggal_masuk' => 'date',
        'aktif' => 'boolean',
        'is_confirmed' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Kelas
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi ke Nilai
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }
}