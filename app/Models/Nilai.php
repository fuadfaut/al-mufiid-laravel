<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Nilai extends Model
{
    use HasFactory;

    protected $table = 'nilai'; // Nama tabel

    protected $fillable = [
        'santri_id',
        'mata_pelajaran_id',
        'tahun_ajaran_id',
        'ustadz_id',
        'kelas_id',
        'nilai_angka',
        'nilai_predikat',
        'catatan',
        'aktif',
    ];

    protected $casts = [
        // Casting nilai_angka ke tipe float/decimal
        'nilai_angka' => 'decimal:2',
    ];

    // Relasi ke Santri
    public function santri(): BelongsTo
    {
        return $this->belongsTo(Santri::class);
    }

    // Relasi ke Mata Pelajaran
    public function mataPelajaran(): BelongsTo
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    // Relasi ke Tahun Ajaran
    public function tahunAjaran(): BelongsTo
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // Relasi ke Ustadz (yang menginput)
    public function ustadz(): BelongsTo
    {
        return $this->belongsTo(Ustadz::class);
    }
    // Relasi ke Kelas  
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }
}