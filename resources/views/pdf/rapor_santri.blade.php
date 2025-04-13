<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Santri - {{ $santri->nama_lengkap }}</title>
    <style>
        /* CSS Sederhana untuk PDF - Hindari CSS kompleks seperti flexbox/grid */
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; margin: 20px; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 1px solid #000; padding-bottom: 10px;}
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 2px 0; }
        .info-santri table { width: 100%; margin-bottom: 15px; border-collapse: collapse;}
        .info-santri td { padding: 3px 5px; }
        .info-santri .label { width: 150px; } /* Lebar kolom label */

        .nilai-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .nilai-table th, .nilai-table td { border: 1px solid #666; padding: 6px; text-align: left; }
        .nilai-table th { background-color: #f2f2f2; text-align: center; }
        .nilai-table .center { text-align: center; }
        .nilai-table .no { width: 30px; }
        .nilai-table .mapel { width: 40%; }
        .nilai-table .nilai { width: 15%; }
        .nilai-table .catatan { width: 30%; }

        .footer { margin-top: 40px; }
        .signature-section { width: 100%; margin-top: 50px; }
        .signature { width: 30%; float: left; text-align: center; } /* Atur float untuk tanda tangan */
        .signature.right { float: right; }
        .signature p { margin-bottom: 60px; } /* Jarak untuk tanda tangan */
        .clear { clear: both; } /* Clear float */

        /* Page Break untuk halaman berikutnya jika perlu */
        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <div class="header">
        {{-- Tambahkan Logo TPA jika ada --}}
        {{-- <img src="{{ public_path('images/logo_tpa.png') }}" alt="Logo TPA" style="width: 80px; float: left;"> --}}
        <h1>LAPORAN HASIL BELAJAR SANTRI</h1>
        <h2>TAMAN PENDIDIKAN AL-QUR'AN Al-Mufiid</h2> {{-- Ganti Nama TPA --}}
        <p>Loa Bakung - Telepon: 081234567890</p>
        <div style="clear: both;"></div>
    </div>

    <div class="info-santri">
        <table>
            <tr>
                <td class="label">Nama Santri</td>
                <td>: {{ $santri->nama_lengkap }}</td>
                <td class="label">Kelas</td>
                <td>: {{ $santri->kelas?->nama_kelas ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Nomor Induk Santri</td>
                <td>: {{ $santri->nis ?? '-' }}</td>
                <td class="label">Semester / Tahun Ajaran</td>
                <td>: {{ $tahunAjaran->semester }} / {{ $tahunAjaran->tahun }}</td>
            </tr>
        </table>
    </div>

    <table class="nilai-table">
        <thead>
            <tr>
                <th class="no">No</th>
                <th class="mapel">Mata Pelajaran</th>
                <th class="nilai">Nilai Angka</th>
                <th class="nilai">Predikat</th>
                <th class="catatan">Catatan/Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($nilai as $item)
                <tr>
                    <td class="center">{{ $loop->iteration }}</td>
                    <td>{{ $item->mataPelajaran->nama }}</td>
                    <td class="center">{{ $item->nilai_angka ?? '-' }}</td>
                    <td class="center">{{ $item->jenis_nilai ?? '-' }}</td>
                    <td>{{ $item->catatan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="center">Belum ada data nilai untuk semester/tahun ajaran ini.</td>
                </tr>
            @endforelse
            {{-- Tambahkan rekap absensi, catatan wali kelas, dll jika ada --}}
        </tbody>
    </table>

    {{-- Contoh Catatan Tambahan --}}
    <div style="margin-top: 20px;">
        <strong>Catatan Wali Kelas:</strong>
        <p style="border: 1px solid #ccc; padding: 10px; min-height: 50px;">
            {{-- Ambil catatan dari data atau biarkan kosong --}}
        </p>
    </div>

    <div class="footer">
         <div class="signature-section">
            <div class="signature">
                <p>Orang Tua/Wali Santri,</p>
                <p>(_________________________)</p>
            </div>
             <div class="signature right">
                <p>Kota Anda, {{ $tanggalCetak }}</p> {{-- Ganti Kota --}}
                <p>Wali Kelas/Pengajar,</p>
                <p><strong>{{ $namaWaliKelas }}</strong></p> {{-- Ganti dengan nama wali kelas/pengajar dinamis --}}
             </div>
            <div class="clear"></div>
        </div>

         <div style="text-align: center; margin-top: 40px;">
            <p>Mengetahui,<br>Pimpinan TPA AL - Mufiid</p> {{-- Ganti Nama TPA --}}
            <br><br><br> {{-- Jarak untuk TTD & Stempel --}}
            <p><strong>{{ $namaKepalaTpa }}</strong></p> {{-- Ganti nama kepala TPA dinamis --}}
         </div>
    </div>

</body>
</html>