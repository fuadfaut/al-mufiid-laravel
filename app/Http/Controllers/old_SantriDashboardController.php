<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Santri;
use App\Models\Nilai;
use App\Models\TahunAjaran;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse; // Import RedirectResponse
use Illuminate\Support\Facades\App; // Untuk set locale jika perlu


class SantriDashboardController extends Controller
{
    // Metode index (yang sudah ada)
    public function index(Request $request): View | RedirectResponse // Bisa juga Redirect jika belum pilih TA
    {
        // ... (logika metode index yang sudah ada) ...

        // Cek apakah ada selectedTahunAjaran, jika tidak ada mungkin redirect dengan pesan
        if ($listTahunAjaran->isNotEmpty() && !$selectedTahunAjaran) {
             // Jika ada pilihan tahun ajaran tapi tidak ada yang terpilih (misal baru pertama buka),
             // mungkin otomatis pilih yang terbaru atau tampilkan pesan
             if($listTahunAjaran->first()){
                  return redirect()->route('santri.dashboard', ['tahun_ajaran_id' => $listTahunAjaran->first()->id]);
             }
        }

         return view('santri.dashboard', [
            'santri' => $santri,
            'nilaiSantri' => $nilaiSantri,
            'listTahunAjaran' => $listTahunAjaran,
            'selectedTahunAjaran' => $selectedTahunAjaran,
        ]);
    }

    // === METODE BARU UNTUK LIHAT RAPOR ===
    public function lihatRapor(Request $request): View | RedirectResponse
    {
        $user = Auth::user();
        $santri = Santri::where('user_id', $user->id)->with('kelas')->firstOrFail();

        $tahunAjaranId = $request->query('tahun_ajaran_id');
        if (!$tahunAjaranId) {
            return redirect()->route('santri.dashboard')
                ->with('error', 'Silakan pilih Tahun Ajaran terlebih dahulu untuk melihat rapor.');
        }

        $tahunAjaran = TahunAjaran::find($tahunAjaranId);
        if (!$tahunAjaran) {
             return redirect()->route('santri.dashboard')
                ->with('error', 'Tahun Ajaran yang dipilih tidak valid.');
        }

        // Ambil nilai
        $nilai = Nilai::where('santri_id', $santri->id)
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->with('mataPelajaran')
            ->orderBy('mata_pelajaran_id')
            ->get();

        // Data tambahan (mirip RaporController)
         App::setLocale('id');
         $tanggalCetak = now()->translatedFormat('d F Y');
         $namaKepalaTpa = 'Nama Kepala TPA Anda'; // Ganti dinamis
         $namaWaliKelas = $santri->kelas?->waliKelas?->nama ?? 'Belum Ditentukan'; // Ganti dinamis

        // === Gunakan View rapor_preview ===
        return view('rapor_preview', [ // <-- PASTIKAN VIEW INI
            'santri' => $santri,
            'nilai' => $nilai,
            'tahunAjaran' => $tahunAjaran,
            'tanggalCetak' => $tanggalCetak,
            'namaKepalaTpa' => $namaKepalaTpa,
            'namaWaliKelas' => $namaWaliKelas,
        ]);
        // ===============================
    }
}