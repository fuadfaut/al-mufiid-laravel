<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Scripts -->
        @vite('resources/js/app.js')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">

             {{-- Tambahkan pesan debug sebelum include navigation --}}
            <!-- DEBUG: Sebelum Include Navigation -->

            @include('layouts.navigation')

            {{-- Tambahkan pesan debug setelah include navigation --}}
            <!-- DEBUG: Setelah Include Navigation -->


            <!-- Page Heading -->
            @if (isset($header))
                <!-- DEBUG: Sebelum Header -->
                 <header class="bg-white shadow">
                     <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                         {{ $header }}
                     </div>
                 </header>
                <!-- DEBUG: Setelah Header -->
             @else
                 <!-- DEBUG: Header Tidak Ada -->
            @endif

            <!-- Page Content -->
            <main>
                 {{-- === Tambahkan Pesan Debug DI SEKITAR Slot === --}}
                <h2>DEBUG: Tepat Sebelum $slot</h2>
                <hr>
                 {{ $slot }}
                <hr>
                <h2>DEBUG: Tepat Sesudah $slot</h2>
                {{-- ============================================== --}}
            </main>

            {{-- Tambahkan pesan debug di akhir body --}}
             <!-- DEBUG: Akhir Body -->

        </div>
         <!-- DEBUG: Sebelum akhir html -->
    </body>
</html>