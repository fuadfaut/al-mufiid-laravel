<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Casts\Attribute; // Import Attribute

class TahunAjaran extends Model
{
    use HasFactory;

    protected $table = 'tahun_ajaran'; // Pastikan nama tabel sudah benar

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tahun',      // <-- Pastikan ada
        'semester',   // <-- Pastikan ada
        'nama_tahun_ajaran', // <-- Pastikan ada
        'aktif',      // <-- Pastikan ada
        // Tambahkan kolom lain yang perlu diisi melalui form jika ada
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'aktif' => 'boolean', // Casting untuk boolean
    ];

    // Relasi jika ada (misalnya ke Nilai)
    // public function nilai() { ... }


    // !!! TAMBAHKAN ACCESSOR INI !!!
    /**
     * Get the display name combining tahun and semester.
     */
    protected function namaTahunAjaran(): Attribute // Nama accessor bisa bebas, contoh: namaTahunAjaran
    {
        return Attribute::make(
            get: fn () => $this->tahun . ' - ' . $this->semester,
        );
    }

    // Opsional: Jika ingin otomatis ter-load saat model di-serialize (misal jadi JSON)
    // protected $appends = ['nama_tahun_ajaran'];
}