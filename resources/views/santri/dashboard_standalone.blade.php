<!DOCTYPE html>
<html lang="id"
    x-data="{ theme: localStorage.getItem('theme') || 'light' }"
    x-init="() => {
        $watch('theme', val => localStorage.setItem('theme', val));
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            theme = 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            theme = 'light';
        }
        document.body.classList.add('loaded'); // Fade in body
    }"
    :class="theme"
    class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Santri - {{ config('app.name', 'E-Rapot TPA') }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite('resources/js/app.js')

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body { opacity: 0; transition: opacity 0.3s ease-in-out; }
        body.loaded { opacity: 1; }
    </style>

</head>
<body class="h-full font-sans antialiased bg-background text-foreground"> {{-- Gunakan warna Shadcn jika terdefinisi --}}

    <!-- Theme Toggle Button -->
    <div class="fixed bottom-5 right-5 z-50">
        <button @click="theme = (theme === 'light' ? 'dark' : 'light')"
           title="Toggle theme"
           class="inline-flex items-center justify-center rounded-full text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-10 w-10 shadow-md">
            <i class="fas fa-sun h-[1.2rem] w-[1.2rem] rotate-0 scale-100 transition-all dark:-rotate-90 dark:scale-0"></i>
            <i class="fas fa-moon absolute h-[1.2rem] w-[1.2rem] rotate-90 scale-0 transition-all dark:rotate-0 dark:scale-100"></i>
            <span class="sr-only">Toggle theme</span>
        </button>
    </div>

    {{-- Main container --}}
    <div class="flex min-h-screen flex-col">

        <!-- Header Sederhana -->
        <header class="sticky top-0 z-40 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
             <div class="container flex h-16 items-center space-x-4 sm:justify-between sm:space-x-0 px-4 md:px-6">
                {{-- Logo/Nama App --}}
                 <a href="{{ route('santri.dashboard') }}" class="flex items-center space-x-2">
                    {{-- Icon SVG Buku atau sejenisnya --}}
                     <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-primary"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path></svg>
                    <span class="font-bold inline-block">{{ config('app.name', 'E-Rapot') }}</span>
                </a>
                {{-- Navigasi Kanan --}}
                <div class="flex flex-1 items-center justify-end space-x-4">
                    <nav class="flex items-center space-x-1">
                         {{-- Jam/Tanggal (Minimalis) --}}
                        <div class="text-xs text-muted-foreground hidden sm:block mr-4">
                            <span id="current-date"></span> | <span id="current-time"></span>
                        </div>
                         {{-- User Info & Logout (Dropdown/Popover ideal, tapi simple link untuk Blade) --}}
                         <span class="text-sm mr-3 hidden sm:inline">Halo, {{ Auth::user()->name }}</span>
                        {{-- Avatar Shadcn --}}
                         <div class="relative flex h-9 w-9 shrink-0 overflow-hidden rounded-full border">
                             @php
                                $avatarNameNav = urlencode($santri->nama_panggilan ?? $santri->nama_lengkap ?? 'SA');
                                $avatarUrlNav = "https://ui-avatars.com/api/?name={$avatarNameNav}&background=e0e7ff&color=4338ca&size=96"; // Warna Indigo mirip primary
                             @endphp
                             <img class="aspect-square h-full w-full" src="{{ $avatarUrlNav }}" alt="Avatar {{ Auth::user()->name }}">
                            {{-- Fallback initials (jika perlu)
                             <span class="flex h-full w-full items-center justify-center rounded-full bg-muted">SA</span>
                             --}}
                         </div>
                         {{-- Tombol Profil & Logout (Contoh) --}}
                        <a href="{{ route('profile.edit') }}" title="Profil Saya" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9">
                           <i class="fas fa-user text-base"></i> <span class="sr-only">Profil</span>
                        </a>
                         <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                             <button type="submit" title="Logout" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 w-9">
                                 <i class="fas fa-sign-out-alt text-base"></i> <span class="sr-only">Logout</span>
                             </button>
                         </form>
                     </nav>
                 </div>
             </div>
         </header>

         <!-- Konten Utama -->
         <main class="flex-1 container mx-auto py-8 px-4 md:px-6 space-y-8">

             {{-- Welcome Banner --}}
             <section class="bg-gradient-to-r from-primary/90 to-indigo-600 dark:from-primary/70 dark:to-indigo-700 text-white rounded-lg shadow-md p-6 flex justify-between items-center">
                 <div>
                     @if(isset($santri))
                         <h1 class="text-2xl font-semibold">Assalamualaikum, {{ $santri->nama_panggilan ?? $santri->nama_lengkap }}!</h1>
                        <p class="text-indigo-100 mt-1">Selamat datang kembali di dashboard E-Rapot.</p>
                    @else
                        <p class="text-red-100">Data santri tidak valid.</p>
                     @endif
                 </div>
                 {{-- Mungkin gambar ilustrasi atau ikon besar --}}
                 <i class="fas fa-graduation-cap text-5xl text-indigo-200 opacity-50 hidden sm:block"></i>
             </section>

             {{-- Grid Utama --}}
             <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                {{-- Kolom 1: Informasi Santri --}}
                <section class="md:col-span-1">
                     <div class="rounded-lg border bg-card text-card-foreground shadow-sm" data-v0-t="card">
                         <div class="flex flex-col space-y-1.5 p-6">
                             <h3 class="text-lg font-semibold leading-none tracking-tight">Profil Santri</h3>
                            <p class="text-sm text-muted-foreground">Informasi detail santri</p>
                         </div>
                        <div class="p-6 pt-0 text-sm space-y-3">
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">Nama:</span>
                                <span class="font-medium text-right">{{ $santri->nama_lengkap ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-muted-foreground">NIS:</span>
                                <span class="font-medium">{{ $santri->nis ?? '-' }}</span>
                            </div>
                             <div class="flex justify-between">
                                 <span class="text-muted-foreground">Kelas:</span>
                                 <span class="font-medium">{{ $santri->kelas?->nama_kelas ?? 'N/A' }}</span>
                            </div>
                             <div class="flex justify-between">
                                 <span class="text-muted-foreground">Wali:</span>
                                 <span class="font-medium">{{ $santri->kelas?->waliKelas?->nama_lengkap ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-muted-foreground">Status:</span>
                                 @if($santri->aktif ?? true)
                                    <span class="inline-flex items-center rounded-full border px-2 py-0 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">Aktif</span>
                                @else
                                     <span class="inline-flex items-center rounded-full border px-2 py-0 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">Tidak Aktif</span>
                                @endif
                            </div>
                         </div>
                         <div class="items-center p-6 pt-0 flex">
                            <a href="{{ route('profile.edit') }}" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-9 px-3 w-full">
                                <i class="fas fa-pencil-alt mr-2 text-xs"></i> Edit Detail Profil
                            </a>
                         </div>
                     </div>
                 </section>

                 {{-- Kolom 2: Pengumuman & Raport --}}
                 <section class="md:col-span-2 space-y-6">
                    {{-- Card Pengumuman --}}
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm" data-v0-t="card">
                        <div class="flex flex-col space-y-1.5 p-6">
                            <h3 class="text-lg font-semibold leading-none tracking-tight">Pengumuman</h3>
                             <p class="text-sm text-muted-foreground">Informasi penting dari TPA</p>
                        </div>
                        <div class="p-6 pt-0 space-y-4">
                            {{-- Ganti dengan data dinamis jika ada --}}
                            <div class="flex items-start space-x-3 group">
                                 <div class="w-1.5 h-auto mt-1 bg-blue-500 rounded-full flex-shrink-0 group-hover:bg-blue-600 transition-colors"></div>
                                <div>
                                    <p class="text-sm font-medium text-foreground">Ujian Tengah Semester</p>
                                    <p class="text-xs text-muted-foreground">12 - 18 Desember 2023</p>
                                </div>
                             </div>
                             <div class="flex items-start space-x-3 group">
                                 <div class="w-1.5 h-auto mt-1 bg-green-500 rounded-full flex-shrink-0 group-hover:bg-green-600 transition-colors"></div>
                                <div>
                                     <p class="text-sm font-medium text-foreground">Pembagian Raport</p>
                                    <p class="text-xs text-muted-foreground">23 Desember 2023</p>
                                </div>
                             </div>
                              <div class="flex items-start space-x-3 group">
                                 <div class="w-1.5 h-auto mt-1 bg-purple-500 rounded-full flex-shrink-0 group-hover:bg-purple-600 transition-colors"></div>
                                <div>
                                     <p class="text-sm font-medium text-foreground">Libur Semester</p>
                                     <p class="text-xs text-muted-foreground">24 Des 2023 - 5 Jan 2024</p>
                                </div>
                            </div>
                         </div>
                    </div>

                    {{-- Card Daftar Raport --}}
                    <div class="rounded-lg border bg-card text-card-foreground shadow-sm mt-6" data-v0-t="card">
                         <div class="flex flex-col space-y-1.5 p-6">
                             <h3 class="text-lg font-semibold leading-none tracking-tight">Riwayat Raport</h3>
                             <p class="text-sm text-muted-foreground">Akses raport tahun ajaran sebelumnya.</p>
                         </div>
                         <div class="p-0 {{-- Hapus padding jika tabel full-width --}}">
                            @if(isset($listTahunAjaran) && $listTahunAjaran->count() > 0)
                                <div class="relative w-full overflow-auto">
                                     <table class="w-full caption-bottom text-sm">
                                        <thead class="[&_tr]:border-b">
                                            <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                                 <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0 w-[150px]">Tahun Ajaran</th>
                                                 <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0 w-[100px]">Semester</th>
                                                 <th class="h-12 px-4 text-left align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0 w-[100px]">Status</th>
                                                 <th class="h-12 px-4 align-middle font-medium text-muted-foreground [&:has([role=checkbox])]:pr-0 text-right">Aksi</th>
                                             </tr>
                                        </thead>
                                        <tbody class="[&_tr:last-child]:border-0">
                                            @foreach($listTahunAjaran as $ta)
                                                <tr class="border-b transition-colors hover:bg-muted/50 data-[state=selected]:bg-muted">
                                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 font-medium">{{ $ta->tahun }}</td>
                                                    <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                         @if($ta->semester == 'Ganjil')
                                                        <span class="inline-flex items-center rounded-full border px-2 py-0 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-yellow-100 dark:bg-yellow-900/50 text-yellow-800 dark:text-yellow-300">{{ $ta->semester }}</span>
                                                         @else
                                                         <span class="inline-flex items-center rounded-full border px-2 py-0 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300">{{ $ta->semester }}</span>
                                                         @endif
                                                     </td>
                                                     <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0">
                                                        @if($ta->aktif)
                                                             <span class="inline-flex items-center rounded-full border px-2 py-0 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300">Aktif</span>
                                                         @else
                                                             <span class="inline-flex items-center rounded-full border px-2 py-0 text-xs font-semibold transition-colors focus:outline-none focus:ring-2 focus:ring-ring focus:ring-offset-2 border-transparent bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">Arsip</span>
                                                         @endif
                                                    </td>
                                                     <td class="p-4 align-middle [&:has([role=checkbox])]:pr-0 text-right space-x-2">
                                                        {{-- Tombol Shadcn Outline --}}
                                                         <a href="{{ route('santri.rapor.preview', ['tahun_ajaran' => $ta->id]) }}" target="_blank"
                                                            class="inline-flex items-center justify-center rounded-md text-xs font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 border border-input bg-background hover:bg-accent hover:text-accent-foreground h-8 px-2">
                                                             <i class="fas fa-eye mr-1 text-xs"></i> Lihat
                                                         </a>
                                                        {{-- Tombol Shadcn Primary --}}
                                                         <a href="{{ route('santri.rapor.cetak', ['tahun_ajaran' => $ta->id]) }}"
                                                            class="inline-flex items-center justify-center rounded-md text-xs font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-8 px-2">
                                                             <i class="fas fa-print mr-1 text-xs"></i> Cetak PDF
                                                         </a>
                                                    </td>
                                                </tr>
                                             @endforeach
                                        </tbody>
                                    </table>
                                 </div>
                             @else
                                 <div class="p-6 text-center">
                                     <p class="text-sm text-muted-foreground">Belum ada riwayat raport.</p>
                                 </div>
                             @endif
                        </div>
                     </div>
                </section> {{-- Akhir Kolom 2 --}}
            </div> {{-- Akhir Grid Utama --}}
         </main>

         <!-- Footer -->
         <footer class="mt-auto py-4 border-t bg-background">
             <div class="container text-center text-xs text-muted-foreground">
                 © {{ date('Y') }} {{ config('app.name', 'E-Rapot TPA') }}. All rights reserved.
             </div>
        </footer>

    </div> {{-- Akhir Main container --}}

    <!-- Scripts already loaded in the head section -->

    <script>
         // Fungsi untuk mengupdate tanggal dan waktu
        function updateDateTime() {
            const now = new Date();
            const dateOptions = { year: 'numeric', month: 'long', day: 'numeric' };
             const timeOptions = { hour: '2-digit', minute: '2-digit', hour12: false };
             const dateEl = document.getElementById('current-date');
             const timeEl = document.getElementById('current-time');
             if (dateEl) dateEl.textContent = now.toLocaleDateString('id-ID', dateOptions);
             if (timeEl) timeEl.textContent = now.toLocaleTimeString('id-ID', timeOptions);
        }
        setInterval(updateDateTime, 1000 * 60); // Update tiap menit saja cukup
        updateDateTime();

         // Animasi Card saat Load (simplifikasi)
         document.addEventListener('DOMContentLoaded', function() {
             const cards = document.querySelectorAll('[data-v0-t="card"]'); // Target card shadcn
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                 }, 100 + index * 50);
             });
             // Hapus event listener theme toggle karena Alpine sudah handle
         });

         // Catatan: Theme toggle logic sekarang dihandle oleh AlpineJS di tag <html>
    </script>

</body>
</html>