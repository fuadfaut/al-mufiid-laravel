<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menunggu Konfirmasi - Taman Pendidikan Al-Mufiid</title>
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
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Navigation Bar -->
    <header class="sticky top-0 bg-white shadow-bottom z-10">
        <div class="container mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-xl font-bold">
                Al-Mufiid Loa Bakung
            </a>
            <nav class="flex items-center space-x-6">
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
        <div class="container mx-auto px-4 py-16 md:py-24">
            <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
                <div class="bg-green-600 p-6 text-white">
                    <h1 class="text-2xl font-bold">Pendaftaran Berhasil</h1>
                    <p class="mt-2">Akun Anda telah terdaftar dan sedang menunggu konfirmasi dari admin.</p>
                </div>
                
                <div class="p-6 space-y-6">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">
                                    Anda belum dapat mengakses dashboard santri hingga admin mengkonfirmasi pendaftaran Anda.
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-6 space-y-4">
                        <h2 class="text-xl font-semibold text-gray-800">Informasi Santri</h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Nama Lengkap</p>
                                <p class="font-medium">{{ $santri->nama_lengkap ?? 'Tidak tersedia' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">NIS</p>
                                <p class="font-medium">{{ $santri->nis ?? 'Belum ditetapkan' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Email</p>
                                <p class="font-medium">{{ $user->email ?? 'Tidak tersedia' }}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Tanggal Pendaftaran</p>
                                <p class="font-medium">{{ $santri->created_at ? $santri->created_at->format('d F Y') : 'Tidak tersedia' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <p class="text-gray-600">Silakan hubungi admin untuk informasi lebih lanjut.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-100 py-8">
        <div class="container mx-auto px-4 text-center text-gray-600">
            <p>&copy; {{ date('Y') }} Taman Pendidikan Al-Mufiid. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
