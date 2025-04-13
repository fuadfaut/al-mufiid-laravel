<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Santri - Taman Pendidikan Al-Mufiid</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/js/app.js')
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        border: "hsl(var(--border))",
                        input: "hsl(var(--input))",
                        ring: "hsl(var(--ring))",
                        background: "hsl(var(--background))",
                        foreground: "hsl(var(--foreground))",
                        primary: {
                            DEFAULT: "#16a34a", // green-600
                            foreground: "#ffffff",
                        },
                        secondary: {
                            DEFAULT: "hsl(var(--secondary))",
                            foreground: "hsl(var(--secondary-foreground))",
                        },
                        destructive: {
                            DEFAULT: "hsl(var(--destructive))",
                            foreground: "hsl(var(--destructive-foreground))",
                        },
                        muted: {
                            DEFAULT: "hsl(var(--muted))",
                            foreground: "hsl(var(--muted-foreground))",
                        },
                        accent: {
                            DEFAULT: "hsl(var(--accent))",
                            foreground: "hsl(var(--accent-foreground))",
                        },
                        popover: {
                            DEFAULT: "hsl(var(--popover))",
                            foreground: "hsl(var(--popover-foreground))",
                        },
                        card: {
                            DEFAULT: "hsl(var(--card))",
                            foreground: "hsl(var(--card-foreground))",
                        },
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        lg: "var(--radius)",
                        md: "calc(var(--radius) - 2px)",
                        sm: "calc(var(--radius) - 4px)",
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
/* Shadcn UI CSS Variables */
    :root {
        --background: 0 0% 100%;
        --foreground: 222.2 84% 4.9%;
        --card: 0 0% 100%;
        --card-foreground: 222.2 84% 4.9%;
        --popover: 0 0% 100%;
        --popover-foreground: 222.2 84% 4.9%;
        --primary: 142 72% 29%; /* Adjusted to match green-600 */
        --primary-foreground: 210 40% 98%;
        --secondary: 210 40% 96.1%;
        --secondary-foreground: 222.2 47.4% 11.2%;
        --muted: 210 40% 96.1%;
        --muted-foreground: 215.4 16.3% 46.9%;
        --accent: 210 40% 96.1%;
        --accent-foreground: 222.2 47.4% 11.2%;
        --destructive: 0 84.2% 60.2%;
        --destructive-foreground: 210 40% 98%;
        --border: 214.3 31.8% 91.4%;
        --input: 214.3 31.8% 91.4%;
        --ring: 142 72% 29%; /* Adjusted to match green-600 */
        --radius: 0.5rem;
    }
    
    .dark {
        --background: 222.2 84% 4.9%;
        --foreground: 210 40% 98%;
        --card: 222.2 84% 4.9%;
        --card-foreground: 210 40% 98%;
        --popover: 222.2 84% 4.9%;
        --popover-foreground: 210 40% 98%;
        --primary: 142 64% 24%;
        --primary-foreground: 210 40% 98%;
        --secondary: 217.2 32.6% 17.5%;
        --secondary-foreground: 210 40% 98%;
        --muted: 217.2 32.6% 17.5%;
        --muted-foreground: 215 20.2% 65.1%;
        --accent: 217.2 32.6% 17.5%;
        --accent-foreground: 210 40% 98%;
        --destructive: 0 62.8% 30.6%;
        --destructive-foreground: 210 40% 98%;
        --border: 217.2 32.6% 17.5%;
        --input: 217.2 32.6% 17.5%;
        --ring: 142.5 64% 24%;
    }
    
    /* Shadcn UI Component Styles */
    .btn {
        @apply inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50;
    }
    
    .btn-primary {
        @apply bg-green-600 text-white hover:bg-green-700;
    }
    
    .btn-secondary {
        @apply bg-secondary text-secondary-foreground hover:bg-secondary/80;
    }
    
    .btn-outline {
        @apply border border-input bg-background hover:bg-accent hover:text-accent-foreground;
    }
    
    .btn-ghost {
        @apply hover:bg-accent hover:text-accent-foreground;
    }
    
    .btn-sm { @apply h-9 rounded-md px-3; }
    .btn-md { @apply h-10 px-4 py-2; }
    .btn-lg { @apply h-11 rounded-md px-8; }
    
    .card {
        @apply rounded-lg border bg-card text-card-foreground shadow-sm;
    }
    
    .card-header {
        @apply flex flex-col space-y-1.5 p-6;
    }
    
    .card-title {
        @apply text-lg font-semibold leading-none tracking-tight;
    }
    
    .card-description {
        @apply text-sm text-muted-foreground;
    }
    
    .card-content {
        @apply p-6 pt-0;
    }
    
    .card-footer {
        @apply flex items-center p-6 pt-0;
    }
    
    .select {
        @apply flex h-10 w-full items-center justify-between rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50;
    }
    
    .badge {
        @apply inline-flex items-center rounded-full border px-2.5 py-0.5 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2;
    }
    
    .badge-primary {
        @apply border-transparent bg-green-600 text-white hover:bg-green-600/80;
    }
    
    .badge-secondary {
        @apply border-transparent bg-secondary text-secondary-foreground hover:bg-secondary/80;
    }
    
    .badge-outline {
        @apply text-foreground;
    }
    
    .avatar {
        @apply relative flex h-10 w-10 shrink-0 overflow-hidden rounded-full;
    }
    
    .avatar-image {
        @apply aspect-square h-full w-full;
    }
    
    .avatar-fallback {
        @apply flex h-full w-full items-center justify-center rounded-full bg-muted;
    }
</style>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <!-- Header -->
    <header class="sticky top-0 bg-green-600 text-white shadow-bottom z-10">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ route('santri.dashboard') }}" class="text-xl font-bold">
                Al-Mufiid Loa Bakung
            </a>
            <nav class="flex items-center space-x-6">
                <span class="text-white font-medium">
                    {{ $santri->nama_lengkap }}
                </span>
                <div class="relative">
                    <img src="https://via.placeholder.com/40" alt="Profil" class="h-10 w-10 rounded-full border-2 border-white">
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="hover:text-gray-200 transition-colors">
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
                <div class="card">
                    <div class="card-header bg-green-600 text-white">
                        <h2 class="card-title">Profil Santri</h2>
                    </div>
                    <div class="card-content space-y-4">
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
                <div class="card">
                    <div class="card-header bg-green-600 text-white flex justify-between items-center">
                        <h2 class="card-title">Nilai dan Rapor</h2>
                        
                        @if($listTahunAjaran->isNotEmpty())
                        <div class="flex items-center space-x-2">
                            <span class="text-sm text-green-100">Pilih Tahun Ajaran:</span>
                            <select id="tahun-ajaran-selector" class="select bg-white text-gray-800 text-sm h-8 w-auto">
                                @foreach($listTahunAjaran as $ta)
                                    <option value="{{ $ta->id }}">{{ $ta->tahun }} - {{ $ta->semester }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                    </div>
                    
                    <div class="card-content">
                        @if($listTahunAjaran->isNotEmpty())
                            @foreach($listTahunAjaran as $index => $ta)
                                <div id="nilai-{{ $ta->id }}" class="nilai-container" style="{{ $index > 0 ? 'display: none;' : '' }}">
                                    <div class="mb-4 flex justify-between items-center">
                                        <h3 class="text-lg font-medium text-gray-800">Tahun Ajaran: {{ $ta->tahun }} - {{ $ta->semester }}</h3>
                                        <div class="flex space-x-2">
                                            <a href="{{ route('santri.rapor.preview', $ta) }}" class="btn btn-sm bg-blue-500 hover:bg-blue-600 text-white">
                                                Preview
                                            </a>
                                            <a href="{{ route('santri.rapor.cetak', $ta) }}" class="btn btn-sm bg-green-500 hover:bg-green-600 text-white">
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
                                        <!-- Statistik Nilai -->
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                                            <div class="bg-blue-100 text-blue-800 rounded-lg p-4 text-center shadow">
                                                <h4 class="text-sm font-medium">Nilai Rata-rata</h4>
                                                <p class="text-3xl font-bold">{{ number_format($nilaiSantri->avg('nilai_angka'), 1) }}</p>
                                            </div>
                                            <div class="bg-green-100 text-green-800 rounded-lg p-4 text-center shadow">
                                                <h4 class="text-sm font-medium">Nilai Tertinggi</h4>
                                                <p class="text-3xl font-bold">{{ number_format($nilaiSantri->max('nilai_angka'), 1) }}</p>
                                            </div>
                                            <div class="bg-yellow-100 text-yellow-800 rounded-lg p-4 text-center shadow">
                                                <h4 class="text-sm font-medium">Jumlah Mata Pelajaran</h4>
                                                <p class="text-3xl font-bold">{{ $nilaiSantri->count() }}</p>
                                            </div>
                                        </div>
                                        
                                        <!-- Tabel Nilai -->
                                        <div class="overflow-x-auto rounded-lg border shadow">
                                            <table class="w-full text-sm">
                                                <thead>
                                                    <tr class="bg-gray-100">
                                                        <th class="h-10 px-4 text-left font-medium text-gray-600">No</th>
                                                        <th class="h-10 px-4 text-left font-medium text-gray-600">Mata Pelajaran</th>
                                                        <th class="h-10 px-4 text-center font-medium text-gray-600">Nilai</th>
                                                        <th class="h-10 px-4 text-center font-medium text-gray-600">Predikat</th>
                                                        <th class="h-10 px-4 text-left font-medium text-gray-600">Catatan</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($nilaiSantri as $nilai)
                                                        <tr class="border-t hover:bg-gray-50">
                                                            <td class="p-4 text-center">{{ $loop->iteration }}</td>
                                                            <td class="p-4 font-medium">{{ $nilai->mataPelajaran->nama }}</td>
                                                            <td class="p-4 text-center">{{ $nilai->nilai_angka ?? '-' }}</td>
                                                            <td class="p-4 text-center">
                                                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-semibold">
                                                                    {{ $nilai->nilai_predikat }}
                                                                </span>
                                                            </td>
                                                            <td class="p-4">{{ $nilai->catatan ?? '-' }}</td>
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
<footer class="bg-green-700 text-white py-4">
    <div class="container mx-auto px-4">
        <p class="text-center text-sm">
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