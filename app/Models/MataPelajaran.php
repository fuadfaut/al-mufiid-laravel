<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran'; // Nama tabel

    protected $fillable = [
        'nama',
        'kategori',
        'deskripsi',
    ];

    // Relasi ke Nilai
    public function nilai(): HasMany
    {
        return $this->hasMany(Nilai::class);
    }
}