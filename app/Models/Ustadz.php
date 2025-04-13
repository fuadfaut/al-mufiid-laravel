<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ustadz extends Model
{
    use HasFactory;

    protected $table = 'ustadzs'; // Nama tabel

    protected $fillable = [
        'nama_lengkap',
        'nip',
        'no_hp',
        'alamat',
        'user_id',
    ];

    // Relasi ke User (jika Ustadz login)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Kelas (sebagai Wali Kelas)
    public function kelasWali(): HasMany
    {
        // Asumsi foreign key di tabel kelas adalah 'ustadz_id'
        return $this->hasMany(Kelas::class, 'ustadz_id');
    }

    // Relasi ke Nilai (sebagai penginput nilai)
    public function nilaiDiinput(): HasMany
    {
        // Asumsi foreign key di tabel nilai adalah 'ustadz_id'
        return $this->hasMany(Nilai::class, 'ustadz_id');
    }
}