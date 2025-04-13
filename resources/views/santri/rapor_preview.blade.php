<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapor Santri - Taman Pendidikan Al-Mufiid</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/js/app.js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        green: {
                            50: '#f0fdf4',
                            100: '#dcfce7',
                            200: '#bbf7d0',
                            300: '#86efac',
                            400: '#4ade80',
                            500: '#22c55e',
                            600: '#16a34a',
                            700: '#15803d',
                            800: '#166534',
                            900: '#14532d',
                            950: '#052e16',
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .shadow-bottom {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .nilai-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .nilai-table th, .nilai-table td { border: 1px solid #e5e7eb; padding: 8px; text-align: left; }
        .nilai-table th { background-color: #f9fafb; text-align: center; font-weight: 600; }
        .nilai-table .center { text-align: center; }
        .nilai-table .no { width: 30px; }
        .nilai-table .mapel { width: 40%; }
        .nilai-table .nilai { width: 15%; }
        .nilai-table .catatan { width: 30%; }

        .signature-section { width: 100%; margin-top: 50px; }
        .signature { width: 30%; float: left; text-align: center; }
        .signature.right { float: right; }
        .signature p { margin-bottom: 60px; }
        .clear { clear: both; }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <!-- Navigation Bar -->
    <header class="sticky top-0 bg-white shadow-bottom z-10">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="/" class="text-xl font-bold">
                Al-Mufiid Loa Bakung
            </a>
            <nav class="flex items-center space-x-6">
                <a href="/" class="hover:text-green-600 transition-colors">
                    Home
                </a>
                <a href="{{ route('santri.dashboard') }}" class="hover:text-green-600 transition-colors">
                    Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-green-600 transition-colors">
                        Logout
                    </button>
                </form>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-8">
            <!-- Title Banner -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-semibold">Rapor Santri</h1>
                        <p class="text-green-100 mt-1">{{ $santri->nama_lengkap }} - {{ $tahunAjaran->semester }} / {{ $tahunAjaran->tahun }}</p>
                    </div>
                    <div class="hidden sm:block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-200 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Rapor Content -->
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 md:p-8">

                    <!-- Header Rapor -->
                    <div class="text-center mb-6 pb-4 border-b border-gray-200">
                        <h1 class="text-2xl font-bold text-gray-800 mb-1">LAPORAN HASIL BELAJAR SANTRI</h1>
                        <h2 class="text-xl font-semibold text-gray-700 mb-1">TAMAN PENDIDIKAN AL-QUR'AN Al-Mufiid</h2>
                        <p class="text-gray-600">Loa Bakung - Telepon: 081234567890</p>
                    </div>

                    <!-- Informasi Santri -->
                    <div class="bg-green-50 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold text-green-800 mb-3">Informasi Santri</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex">
                                <div class="w-40 font-medium text-gray-700">Nama Santri</div>
                                <div>: {{ $santri->nama_lengkap }}</div>
                            </div>
                            <div class="flex">
                                <div class="w-40 font-medium text-gray-700">Kelas</div>
                                <div>: {{ $santri->kelas?->nama_kelas ?? '-' }}</div>
                            </div>
                            <div class="flex">
                                <div class="w-40 font-medium text-gray-700">Nomor Induk Santri</div>
                                <div>: {{ $santri->nis ?? '-' }}</div>
                            </div>
                            <div class="flex">
                                <div class="w-40 font-medium text-gray-700">Semester / Tahun</div>
                                <div>: {{ $tahunAjaran->semester }} / {{ $tahunAjaran->tahun }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Tabel Nilai -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-green-800 mb-3">Daftar Nilai</h3>
                        <div class="overflow-x-auto rounded-lg border border-gray-200">
                            <table class="nilai-table">
                                <thead>
                                    <tr class="bg-green-50">
                                        <th class="no">No</th>
                                        <th class="mapel">Mata Pelajaran</th>
                                        <th class="nilai">Nilai Angka</th>
                                        <th class="nilai">Predikat</th>
                                        <th class="catatan">Catatan/Deskripsi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($nilai as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="center">{{ $loop->iteration }}</td>
                                            <td>{{ $item->mataPelajaran->nama }}</td>
                                            <td class="center">{{ $item->nilai_angka ?? '-' }}</td>
                                            <td class="center">{{ $item->jenis_nilai ?? '-' }}</td>
                                            <td>{{ $item->catatan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="center py-4 text-gray-500">Belum ada data nilai untuk semester/tahun ajaran ini.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Catatan Tambahan -->
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-green-800 mb-3">Catatan Wali Kelas</h3>
                        <div class="border border-gray-200 rounded-lg p-4 min-h-[80px] bg-gray-50">
                            <!-- Catatan wali kelas akan ditampilkan di sini -->
                        </div>
                    </div>

                    <!-- Tanda Tangan -->
                    <div class="mt-10">
                        <div class="signature-section">
                            <div class="signature">
                                <p class="text-gray-700">Orang Tua/Wali Santri,</p>
                                <div class="mt-16"></div>
                                <p class="border-t border-gray-400 pt-1 inline-block">(_________________________)</p>
                            </div>
                            <div class="signature right">
                                <p class="text-gray-700">Samarinda, {{ $tanggalCetak }}</p>
                                <p class="text-gray-700">Wali Kelas/Pengajar,</p>
                                <div class="mt-16"></div>
                                <p class="border-t border-gray-400 pt-1 inline-block font-medium">{{ $namaWaliKelas }}</p>
                            </div>
                            <div class="clear"></div>
                        </div>

                        <div class="text-center mt-16">
                            <p class="text-gray-700">Mengetahui,<br>Pimpinan TPA AL - Mufiid</p>
                            <div class="mt-16"></div>
                            <p class="border-t border-gray-400 pt-1 inline-block font-medium">{{ $namaKepalaTpa }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end mt-6 space-x-4">
                <a href="{{ route('santri.dashboard') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-md transition-colors">
                    Kembali ke Dashboard
                </a>
                <a href="{{ route('santri.rapor.cetak', ['tahun_ajaran' => $tahunAjaran->id]) }}" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md transition-colors">
                    Download PDF
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-green-600 text-white py-6 mt-12">
        <div class="container mx-auto px-4">
            <p class="text-center">
                Taman Pendidikan Alquran Al-Mufiid Loa Bakung Samarinda
            </p>
        </div>
    </footer>
</body>
</html>