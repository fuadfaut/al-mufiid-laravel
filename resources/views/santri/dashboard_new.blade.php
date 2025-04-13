<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Santri - Taman Pendidikan Al-Mufiid</title>
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
        .nilai-table {
            width: 100%;
            border-collapse: collapse;
        }
        .nilai-table th, .nilai-table td {
            border: 1px solid #e5e7eb;
            padding: 0.75rem 1rem;
            text-align: left;
        }
        .nilai-table th {
            background-color: #f9fafb;
            font-weight: 600;
        }
        .nilai-table tr:nth-child(even) {
            background-color: #f9fafb;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <!-- Navigation Bar -->
    <header class="sticky top-0 bg-white shadow-bottom z-10">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('santri.dashboard') }}" class="text-xl font-bold">
                Al-Mufiid Loa Bakung
            </a>
            <nav class="flex items-center space-x-6">
                <span class="text-gray-700">
                    {{ $santri->nama_lengkap }}
                </span>
                <form method="POST" action="{{ route('logout') }}">
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
            <!-- Welcome Banner -->
            <div class="bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg shadow-md p-6 mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-semibold">Assalamualaikum, {{ $santri->nama_panggilan ?? $santri->nama_lengkap }}!</h1>
                        <p class="text-green-100 mt-1">Selamat datang di dashboard E-Rapor Al-Mufiid.</p>
                    </div>
                    <div class="hidden sm:block">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-green-200 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Profil Santri -->
                <div class="md:col-span-1">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-green-600 text-white p-4">
                            <h2 class="text-lg font-semibold">Profil Santri</h2>
                        </div>
                        <div class="p-4 space-y-4">
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Nama Lengkap:</span>
                                <span class="font-medium">{{ $santri->nama_lengkap }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">NIS:</span>
                                <span class="font-medium">{{ $santri->nis ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Kelas:</span>
                                <span class="font-medium">{{ $santri->kelas->nama_kelas ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Jenis Kelamin:</span>
                                <span class="font-medium">{{ $santri->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            </div>
                            <div class="flex justify-between border-b pb-2">
                                <span class="text-gray-600">Tanggal Lahir:</span>
                                <span class="font-medium">{{ $santri->tanggal_lahir ? $santri->tanggal_lahir->format('d F Y') : '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Tanggal Masuk:</span>
                                <span class="font-medium">{{ $santri->tanggal_masuk ? $santri->tanggal_masuk->format('d F Y') : '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nilai dan Rapor -->
                <div class="md:col-span-2">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden">
                        <div class="bg-green-600 text-white p-4 flex justify-between items-center">
                            <h2 class="text-lg font-semibold">Nilai dan Rapor</h2>
                            
                            @if($listTahunAjaran->isNotEmpty())
                            <div class="flex items-center space-x-2">
                                <span class="text-sm text-green-100">Pilih Tahun Ajaran:</span>
                                <select id="tahun-ajaran-selector" class="text-gray-800 text-sm rounded px-2 py-1">
                                    @foreach($listTahunAjaran as $ta)
                                        <option value="{{ $ta->id }}">{{ $ta->tahun }} - {{ $ta->semester }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                        </div>
                        
                        <div class="p-4">
                            @if($listTahunAjaran->isNotEmpty())
                                @foreach($listTahunAjaran as $index => $ta)
                                    <div id="nilai-{{ $ta->id }}" class="nilai-container" style="{{ $index > 0 ? 'display: none;' : '' }}">
                                        <div class="mb-4 flex justify-between items-center">
                                            <h3 class="text-lg font-medium text-gray-800">Tahun Ajaran: {{ $ta->tahun }} - {{ $ta->semester }}</h3>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('santri.rapor.preview', $ta) }}" class="text-sm bg-blue-500 hover:bg-blue-600 text-white py-1 px-3 rounded transition-colors">
                                                    Preview
                                                </a>
                                                <a href="{{ route('santri.rapor.cetak', $ta) }}" class="text-sm bg-green-500 hover:bg-green-600 text-white py-1 px-3 rounded transition-colors">
                                                    Cetak PDF
                                                </a>
                                            </div>
                                        </div>
                                        
                                        @php
                                            $nilaiSantri = \App\Models\Nilai::where('santri_id', $santri->id)
                                                ->where('tahun_ajaran_id', $ta->id)
                                                ->with('mataPelajaran')
                                                ->orderBy('mata_pelajaran_id')
                                                ->get();
                                        @endphp
                                        
                                        @if($nilaiSantri->isNotEmpty())
                                            <div class="overflow-x-auto">
                                                <table class="nilai-table">
                                                    <thead>
                                                        <tr>
                                                            <th class="w-12">No</th>
                                                            <th>Mata Pelajaran</th>
                                                            <th class="w-24 text-center">Nilai</th>
                                                            <th class="w-24 text-center">Predikat</th>
                                                            <th>Catatan</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($nilaiSantri as $nilai)
                                                            <tr>
                                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                                <td>{{ $nilai->mataPelajaran->nama }}</td>
                                                                <td class="text-center">{{ $nilai->nilai_angka ?? '-' }}</td>
                                                                <td class="text-center">{{ $nilai->nilai_predikat }}</td>
                                                                <td>{{ $nilai->catatan ?? '-' }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-4">
                                                <div class="flex">
                                                    <div class="flex-shrink-0">
                                                        <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                        </svg>
                                                    </div>
                                                    <div class="ml-3">
                                                        <p class="text-sm text-yellow-700">
                                                            Belum ada data nilai untuk tahun ajaran ini.
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            @else
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <p class="text-sm text-yellow-700">
                                                Belum ada data nilai yang tersedia. Silakan hubungi admin atau ustadz untuk informasi lebih lanjut.
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-green-600 text-white py-6 mt-8">
        <div class="container mx-auto px-4">
            <p class="text-center">
                Taman Pendidikan Alquran Al-Mufiid Loa Bakung Samarinda
            </p>
        </div>
    </footer>

    <script>
        // JavaScript untuk mengubah tampilan nilai berdasarkan tahun ajaran yang dipilih
        document.addEventListener('DOMContentLoaded', function() {
            const selector = document.getElementById('tahun-ajaran-selector');
            if (selector) {
                selector.addEventListener('change', function() {
                    // Sembunyikan semua container nilai
                    document.querySelectorAll('.nilai-container').forEach(container => {
                        container.style.display = 'none';
                    });
                    
                    // Tampilkan container nilai yang dipilih
                    const selectedContainer = document.getElementById('nilai-' + this.value);
                    if (selectedContainer) {
                        selectedContainer.style.display = 'block';
                    }
                });
            }
        });
    </script>
</body>
</html>
