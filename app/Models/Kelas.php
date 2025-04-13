<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
// Pastikan Model Ustadz dan TahunAjaran di-import
use App\Models\Ustadz;
use App\Models\TahunAjaran;
use App\Models\Santri; // Import Santri jika belum ada

class Kelas extends Model
{
    use HasFactory;

    // Opsional tapi baik: Definisikan nama tabel secara eksplisit
    // protected $table = 'kelas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_kelas',
        'tingkat',
        'ustadzs_id',        // Foreign key ke tabel Ustadz (wali kelas)
        'tahun_ajaran_id', // Foreign key ke tabel TahunAjaran
    ];

    /**
     * Mendapatkan data Wali Kelas (Ustadz) yang terkait dengan Kelas ini.
     *
     * Nama method diubah menjadi 'waliKelas' agar sesuai dengan pemanggilan
     * yang menyebabkan error. Foreign key disesuaikan menjadi 'ustadzs_id'
     * agar cocok dengan $fillable dan asumsi skema database Anda.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function waliKelas(): BelongsTo // Nama method diubah ke waliKelas
    {
        // Menghubungkan ke Model Ustadz
        // Menggunakan 'ustadzs_id' sebagai foreign key di tabel 'kelas'
        return $this->belongsTo(Ustadz::class, 'ustadzs_id');
    }

    /*
     * Method ustadz() yang lama dihapus untuk menghindari duplikasi
     * dan potensi kebingungan karena nama/foreign key yang salah.
     * public function ustadz()
     * {
     *    return $this->belongsTo(Ustadz::class, 'ustadz_id'); // Ini dihapus
     * }
    */

    /**
     * Mendapatkan Tahun Ajaran yang terkait dengan Kelas ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tahunAjaran(): BelongsTo
    {
        // Asumsi foreign key di tabel 'kelas' adalah 'tahun_ajaran_id'
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id'); // Baik untuk eksplisit FK
    }

    /**
     * Mendapatkan daftar Santri yang ada di Kelas ini.
     * (Asumsi: tabel 'santri' memiliki kolom 'kelas_id')
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function santris(): HasMany // Nama 'santris' sudah ok, meski 'santri' lebih umum
    {
        return $this->hasMany(Santri::class, 'kelas_id'); // Baik untuk eksplisit FK
    }
}