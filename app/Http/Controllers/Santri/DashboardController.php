<?php

namespace App\Http\Controllers\Santri;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TahunAjaran;
use App\Models\Nilai;
use App\Models\Santri;
use Barryvdh\DomPDF\Facade\Pdf; // Untuk cetak PDF
use Illuminate\Support\Facades\App; // Untuk set locale PDF


class DashboardController extends Controller
{
    // Menampilkan halaman dashboard utama santri
    public function index()
    {

        $user = Auth::user();
        $santri = $user->santri; // Ambil data santri dari user yg login


        if (!$santri) {
            // Handle jika data santri tidak ditemukan (seharusnya tidak terjadi karena middleware)
            Auth::logout();
            return redirect()->route('login')->withErrors('Data santri tidak ditemukan. Silakan hubungi admin.');
        }

        // Ambil daftar Tahun Ajaran yang memiliki nilai untuk santri ini
        $availableTahunAjaranIds = Nilai::where('santri_id', $santri->id)
                                            ->distinct()
                                            ->pluck('tahun_ajaran_id');

        $listTahunAjaran = TahunAjaran::whereIn('id', $availableTahunAjaranIds)
                                            ->orWhere('aktif', true) // Tampilkan juga TA aktif meskipun belum ada nilai
                                            ->orderBy('tahun', 'desc')
                                            ->orderBy('semester', 'desc')
                                            ->get();

        // Load relasi kelas untuk ditampilkan di profil
        $santri->load('kelas');

        return view('santri.dashboard_shadcn', compact('santri', 'listTahunAjaran'));
    }

    // Menampilkan preview rapor dalam format HTML (mirip PDF tapi bukan PDF)
    public function previewRapor(TahunAjaran $tahun_ajaran) // Route model binding
    {
        return $this->generateRaporView($tahun_ajaran, false);
    }

    // Generate dan download rapor dalam format PDF
    public function cetakRapor(TahunAjaran $tahun_ajaran)
    {
        // return $this->generateRaporView($tahun_ajaran, true);

        // Atau logic PDF terpisah untuk lebih jelas
        $user = Auth::user();
        $santri = $user->santri->load('kelas'); // Load relasi kelas

        if (!$santri) { abort(404); } // Seharusnya tidak terjadi

        // Ambil nilai santri untuk tahun ajaran ini
        $nilaiSantri = Nilai::where('santri_id', $santri->id)
            ->where('tahun_ajaran_id', $tahun_ajaran->id)
            ->with('mataPelajaran')
            ->orderBy('mata_pelajaran_id') // Sesuaikan urutan
            ->get();

        // Data lain yang diperlukan untuk view PDF
        $data = [
            'santri' => $santri,
            'nilai' => $nilaiSantri,
            'tahunAjaran' => $tahun_ajaran,
            'tanggalCetak' => now()->translatedFormat('d F Y'), // Format Indonesia
            'namaKepalaTpa' => config('app.nama_kepala_tpa', 'Nama Kepala TPA'), // Ambil dari config
            'namaWaliKelas' => $santri->kelas?->waliKelas?->nama_lengkap ?? '-', // Wali Kelas dari relasi Santri->Kelas->Ustadz (Wali)
            // ... data lain untuk rapor ...
        ];

        // Set locale agar format tanggal/angka Indonesia (jika perlu)
        App::setLocale('id');

        // Load view PDF yang SAMA dengan yg dipakai Admin/Ustadz
        $pdf = Pdf::loadView('pdf.rapor_santri', $data);

        // Debug data yang dikirim ke view
        //dd($data);

        // === GENERATE NAMA FILE (VERSI PERBAIKAN) ===

        // 1. Dapatkan Identifier Santri (utamakan NIS, fallback ke Nama Panggilan, fallback ke Nama Lengkap)
        $santriIdentifier = $santri->nis ?: ($santri->nama_panggilan ?: $santri->nama_lengkap);
        // Ganti spasi dengan underscore dan hapus karakter selain alphanumeric/underscore/strip
        $santriIdentifierSafe = preg_replace('/[^A-Za-z0-9_\-]+/', '_', str_replace(' ', '_', $santriIdentifier ?? 'Santri'));
        // Jika hasil sanitasi kosong (sangat jarang), beri nama default
        if (empty($santriIdentifierSafe)) {
            $santriIdentifierSafe = 'Santri_' . $santri->id; // Gunakan ID sebagai fallback terakhir
        }

        // 2. Dapatkan Identifier Tahun Ajaran (utamakan Tahun-Semester, fallback ke ID)
        $tahunAjaranIdentifier = ($tahun_ajaran->tahun && $tahun_ajaran->semester)
                                    ? str_replace('/', '-', $tahun_ajaran->tahun) . '_' . $tahun_ajaran->semester
                                    : 'TA_' . $tahun_ajaran->id;
        // Sanitasi tahun ajaran (lebih aman karena mungkin ada '/', '-', '_')
        $tahunAjaranIdentifierSafe = preg_replace('/[^A-Za-z0-9_\-]+/', '_', $tahunAjaranIdentifier);
        // Fallback jika kosong
        if (empty($tahunAjaranIdentifierSafe)) {
           $tahunAjaranIdentifierSafe = 'TA_' . $tahun_ajaran->id;
        }

        // 3. Gabungkan menjadi Nama File Akhir
        $namaFile = "Rapor_{$santriIdentifierSafe}_{$tahunAjaranIdentifierSafe}.pdf";

        // === AKHIR GENERATE NAMA FILE ===


        // // Kode lama Anda (kemungkinan salah jika getter tidak ada atau data kosong):
        // $namaFile = 'Rapor_' . $santri->nis_or_nama . '_' . $tahun_ajaran->simple_name . '.pdf';
        // // (Anda perlu menambahkan getter seperti nis_or_nama / simple_name di model jika ingin nama file dinamis yg bagus)
        // $namaFile = preg_replace('/[^A-Za-z0-9_\-]/', '_', $namaFile); // Sanitasi nama file


        // Download file
        return $pdf->download($namaFile);

    }

    // Helper method untuk mengambil data & menampilkan view (preview atau dasar PDF)
    // (Dipisah agar tidak duplikasi kode antara preview & cetak jika strukturnya sama)
    private function generateRaporView(TahunAjaran $tahun_ajaran, bool $isPdf = false)
    {
        $user = Auth::user();
        $santri = $user->santri->load('kelas'); // Load relasi kelas

        if (!$santri) { abort(404); } // Pengaman

        // Ambil nilai santri
        $nilaiSantri = Nilai::where('santri_id', $santri->id)
            ->where('tahun_ajaran_id', $tahun_ajaran->id)
            ->with('mataPelajaran') // Eager load mapel
            // ->with('ustadz') // Eager load ustadz pengampu jika perlu ditampilkan
            ->orderBy('mata_pelajaran_id') // Urutkan sesuai mapel
            ->get();

        // Ambil data lain yang dibutuhkan view (kepala tpa, wali kelas, dll.)
        $data = [
            'santri' => $santri,
            'nilai' => $nilaiSantri,
            'tahunAjaran' => $tahun_ajaran,
            'tanggalCetak' => now()->translatedFormat('d F Y'),
            'namaKepalaTpa' => config('app.nama_kepala_tpa', 'Nama Kepala TPA Default'),
            'namaWaliKelas' => $santri->kelas?->waliKelas?->nama_lengkap ?? 'Belum Ditentukan', // Contoh Wali Kelas
        ];

        App::setLocale('id');

        // Gunakan view rapor PDF yang sudah ada
        $viewName = 'pdf.rapor_santri'; // Pastikan view ini ada

        // Jika ini request PDF, gunakan DomPDF
        if ($isPdf) {
            $pdf = Pdf::loadView($viewName, $data);
            $namaFile = 'rapor_' . str_replace('/', '-', $santri->nis ?? $santri->id) . '_' . $tahun_ajaran->id . '.pdf';
            return $pdf->download($namaFile);
        } else {
            // Jika hanya preview, tampilkan view biasa
            // Bungkus view PDF dalam view preview agar ada tombol Cetak dll.
            return view('santri.rapor_preview', array_merge($data, ['viewRaporContent' => $viewName]));
            // Atau tampilkan view pdf langsung jika tidak perlu wrapper:
            // return view($viewName, $data);
        }
    }
}