<?php

namespace App\Http\Controllers;

use App\Models\Santri;
use App\Models\Nilai;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\App;
use Illuminate\View\View; // <-- Import View
use Symfony\Component\HttpFoundation\Response; // <-- Import Response untuk PDF
use Illuminate\Http\RedirectResponse; // <-- Import RedirectResponse



class RaporController extends Controller
{
    /**
     * Mengambil data umum yang diperlukan untuk rapor (preview dan PDF).
     */
    private function getRaporData(Santri $santri, TahunAjaran $tahunAjaran): array
    {
         // Tahun Ajaran dan Santri sudah diambil melalui Route Model Binding
         // Tidak perlu validasi lagi karena Laravel sudah melakukannya

         // Load relasi santri
         $santri->load('kelas.waliKelas.user'); // Load kelas, wali kelas, dan user wali kelas (jika ada)

         // Ambil nilai
         $nilai = Nilai::where('santri_id', $santri->id)
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->with('mataPelajaran')
            ->orderBy('mata_pelajaran_id')
            ->get();

         // Data tambahan
         App::setLocale('id'); // Set locale
         $tanggalCetak = now()->translatedFormat('d F Y');
         $namaKepalaTpa = 'Ust. H. Muhammad Fulan'; // Ganti dengan data dinamis/config
         // Ambil nama wali kelas dari relasi yang sudah di-load
         $namaWaliKelas = $santri->kelas?->waliKelas?->user?->name ?? 'Belum Ditentukan';

        return [
            'santri' => $santri,
            'nilai' => $nilai,
            'tahunAjaran' => $tahunAjaran,
            'tanggalCetak' => $tanggalCetak,
            'namaKepalaTpa' => $namaKepalaTpa,
            'namaWaliKelas' => $namaWaliKelas,
        ];
    }

    /**
     * Menampilkan preview rapor dalam format HTML.
     */
    public function preview(Santri $santri, TahunAjaran $tahun_ajaran): View
    {
        $data = $this->getRaporData($santri, $tahun_ajaran);

        // Tampilkan view preview
        return view('santri.rapor_preview', $data); // <-- Gunakan view rapor_preview
    }

    /**
     * Menghasilkan dan mengunduh rapor dalam format PDF.
     */
    public function cetak(Santri $santri, TahunAjaran $tahun_ajaran): Response
    {
        $data = $this->getRaporData($santri, $tahun_ajaran);

        // Load view PDF
        $pdf = Pdf::loadView('pdf.rapor_santri', $data); // <-- Gunakan view PDF

        // Tawarkan download
        return $pdf->download('rapor_' . ($santri->nis ?? $santri->id) . '_' . str_replace(' ', '_', $santri->nama_lengkap) . '.pdf');
    }
}