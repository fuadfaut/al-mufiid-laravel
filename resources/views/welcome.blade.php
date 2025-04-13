<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Taman Pendidikan Al-Mufiid</title>
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
            <a href="" class="text-xl font-bold">
                Al-Mufiid Loa Bakung
            </a>
            <nav class="flex items-center space-x-6">
                <a href="" class="hover:text-green-600 transition-colors">
                    Home
                </a>
                <a href="" class="hover:text-green-600 transition-colors">
                    Tentang
                </a>
                <a href="login" class="hover:text-green-600 transition-colors">
                    Login
                </a>
                <a
                    href=""
                    class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded transition-colors"
                >
                    Daftar
                </a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-grow">
        <div class="container mx-auto px-4 py-16 md:py-24">
            <div class="flex flex-col md:flex-row items-center justify-between gap-8">
                <!-- Kolom Gambar -->
                <div class="w-full md:w-1/2 lg:w-2/5 flex justify-center">
                    <div class="w-3/4 md:w-2/3 lg:w-1/2">
                        <img
                            src="/images/quran-vector.png"
                            alt="Al-Quran"
                            class="w-full h-auto rounded shadow-lg"
                            onerror="this.onerror=null; this.src='{{ asset('images/quran-vector.png') }}'"
                        >
                    </div>
                </div>

                <!-- Kolom Teks -->
                <div class="w-full md:w-1/2 text-center md:text-left">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                        Taman Pendidikan <span class="text-green-600">Al-Mufiid</span>
                    </h1>
                    <p class="text-lg text-gray-600 mb-6">
                        Menanamkan Nilai-Nilai Islami, Membentuk Generasi Berkualitas di Loa Bakung, Samarinda.
                    </p>
                    <p class="text-md text-gray-700 mb-8">
                        Daftarkan Putra-Putri Anda Sekarang
                    </p>
                    <a
                        href="{{ route('register') }}"
                        class="inline-block bg-green-600 hover:bg-green-700 text-white font-semibold py-3 px-6 rounded-lg shadow transition duration-300"
                    >
                        Daftar
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-green-600 text-white py-6">
        <div class="container mx-auto px-4">
            <p class="text-center">
                Taman Pendidikan Alquran Al-Mufiid Loa Bakung Samarinda
            </p>
        </div>
    </footer>
</body>
</html>