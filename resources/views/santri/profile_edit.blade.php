<!DOCTYPE html>
<html lang="id"
    x-data="{ theme: localStorage.getItem('theme') || 'light' }"
    x-init="() => {
        // Logic inisialisasi tema Alpine.js (sama seperti di dashboard)
        $watch('theme', val => localStorage.setItem('theme', val));
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            theme = 'dark';
        } else {
            document.documentElement.classList.remove('dark');
            theme = 'light';
        }
        document.body.classList.add('loaded');
    }"
    :class="theme"
    class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - {{ config('app.name', 'E-Rapot TPA') }}</title>
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
<body class="h-full font-sans antialiased bg-background text-foreground">

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

        <!-- Header Sederhana (Konsisten dengan Dashboard) -->
         <header class="sticky top-0 z-40 w-full border-b bg-background/95 backdrop-blur supports-[backdrop-filter]:bg-background/60">
             <div class="container flex h-16 items-center space-x-4 sm:justify-between sm:space-x-0 px-4 md:px-6">
                {{-- Logo/Nama App --}}
                 <a href="{{ route('santri.dashboard') }}" class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-primary"><path d="M4 19.5v-15A2.5 2.5 0 0 1 6.5 2H20v20H6.5a2.5 2.5 0 0 1 0-5H20"></path></svg>
                    <span class="font-bold inline-block">{{ config('app.name', 'E-Rapot') }}</span>
                </a>
                {{-- Navigasi Kanan --}}
                <div class="flex flex-1 items-center justify-end space-x-4">
                     {{-- Tombol Kembali ke Dashboard --}}
                     <a href="{{ route('santri.dashboard') }}" title="Kembali ke Dashboard" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 hover:bg-accent hover:text-accent-foreground h-9 px-3 mr-2">
                        <i class="fas fa-arrow-left mr-1 text-xs"></i> Dashboard
                    </a>
                    <nav class="flex items-center space-x-1">
                         <span class="text-sm mr-3 hidden sm:inline">{{ Auth::user()->name }}</span>
                         <div class="relative flex h-9 w-9 shrink-0 overflow-hidden rounded-full border">
                            {{-- Ganti dengan logika avatar yang sama dari dashboard --}}
                             @php $avatarNameNavProf = urlencode(Auth::user()->name); $avatarUrlNavProf = "https://ui-avatars.com/api/?name={$avatarNameNavProf}&background=e0e7ff&color=4338ca&size=96"; @endphp
                             <img class="aspect-square h-full w-full" src="{{ $avatarUrlNavProf }}" alt="Avatar {{ Auth::user()->name }}">
                         </div>
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
            <h1 class="text-2xl font-semibold tracking-tight text-foreground">Edit Profil</h1>

             {{-- Jika menggunakan Laravel Breeze, partials-nya bisa di-include --}}
            {{-- Atau buat form langsung di sini meniru styling Shadcn --}}

            <div class="space-y-8"> {{-- Container untuk beberapa kartu form --}}

                {{-- Kartu: Update Informasi Profil --}}
                 <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-6">
                         <h3 class="text-lg font-semibold leading-none tracking-tight">Informasi Profil</h3>
                         <p class="text-sm text-muted-foreground">Perbarui informasi nama dan email akun Anda.</p>
                    </div>
                    {{-- Pastikan route 'profile.update' menggunakan method PATCH --}}
                     <form method="post" action="{{ route('profile.update') }}" class="p-6 pt-0 space-y-4">
                        @csrf
                        @method('patch')

                        <div>
                            <label for="name" class="block text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70 mb-1.5">Nama</label>
                            <input type="text" name="name" id="name" required autofocus autocomplete="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50 @error('name') border-red-500 dark:border-red-600 @enderror">
                             {{-- Error message --}}
                            @error('name')
                                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                             @enderror
                        </div>

                        <div>
                             <label for="email" class="block text-sm font-medium mb-1.5">Email</label>
                             <input type="email" name="email" id="email" required autocomplete="username"
                                   value="{{ old('email', $user->email) }}"
                                   class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 @error('email') border-red-500 dark:border-red-600 @enderror">
                            @error('email')
                                <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                             @enderror

                            {{-- Info jika user belum verifikasi email --}}
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div>
                                     <p class="text-sm mt-2 text-muted-foreground">
                                        Alamat email Anda belum diverifikasi.
                                        <button form="send-verification" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                            Kirim ulang email verifikasi?
                                         </button>
                                     </p>
                                     {{-- Tampilkan pesan sukses jika email terkirim --}}
                                     @if (session('status') === 'verification-link-sent')
                                        <p class="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                            Tautan verifikasi baru telah dikirim ke alamat email Anda.
                                         </p>
                                     @endif
                                 </div>
                            @endif
                         </div>

                         {{-- Tombol Simpan & Pesan Sukses --}}
                        <div class="flex items-center gap-4">
                             <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                Simpan Perubahan
                            </button>
                            {{-- Pesan Sukses Disimpan --}}
                             @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                   class="text-sm text-green-600 dark:text-green-400">Tersimpan.</p>
                            @endif
                         </div>
                     </form>
                     {{-- Form untuk kirim ulang verifikasi (terpisah) --}}
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}" class="hidden">
                         @csrf
                    </form>
                 </div>

                {{-- Kartu: Update Password --}}
                <div class="rounded-lg border bg-card text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-6">
                        <h3 class="text-lg font-semibold leading-none tracking-tight">Ubah Password</h3>
                        <p class="text-sm text-muted-foreground">Pastikan menggunakan password yang kuat dan acak.</p>
                    </div>
                     {{-- Pastikan route('password.update') pakai method PUT --}}
                    <form method="post" action="{{ route('password.update') }}" class="p-6 pt-0 space-y-4">
                         @csrf
                         @method('put')

                        <div>
                            <label for="current_password" class="block text-sm font-medium mb-1.5">Password Saat Ini</label>
                             <input type="password" name="current_password" id="current_password" required autocomplete="current-password"
                                   class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 @error('current_password', 'updatePassword') border-red-500 dark:border-red-600 @enderror">
                             @error('current_password', 'updatePassword') {{-- Error bag spesifik --}}
                                 <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                         <div>
                            <label for="password" class="block text-sm font-medium mb-1.5">Password Baru</label>
                             <input type="password" name="password" id="password" required autocomplete="new-password"
                                   class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 @error('password', 'updatePassword') border-red-500 dark:border-red-600 @enderror">
                             @error('password', 'updatePassword')
                                 <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                         <div>
                             <label for="password_confirmation" class="block text-sm font-medium mb-1.5">Konfirmasi Password Baru</label>
                             <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                                    class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:opacity-50 @error('password_confirmation', 'updatePassword') border-red-500 dark:border-red-600 @enderror">
                              @error('password_confirmation', 'updatePassword')
                                  <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                         </div>

                         <div class="flex items-center gap-4">
                            <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                                Ubah Password
                             </button>
                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                                   class="text-sm text-green-600 dark:text-green-400">Tersimpan.</p>
                            @endif
                        </div>
                     </form>
                </div>

                {{-- Kartu: Hapus Akun (Danger Zone) --}}
                <div class="rounded-lg border border-red-300 dark:border-red-700 bg-red-50 dark:bg-gray-800 text-card-foreground shadow-sm">
                    <div class="flex flex-col space-y-1.5 p-6">
                         <h3 class="text-lg font-semibold leading-none tracking-tight text-red-700 dark:text-red-400">Zona Berbahaya</h3>
                         <p class="text-sm text-red-600 dark:text-red-300">Tindakan ini tidak dapat dibatalkan. Semua data Anda akan dihapus permanen.</p>
                    </div>
                     {{-- Tombol ini idealnya memicu modal konfirmasi --}}
                    {{-- Untuk kesederhanaan, form hapus langsung --}}
                     <form method="post" action="{{ route('profile.destroy') }}" class="p-6 pt-0">
                        @csrf
                        @method('delete')

                        {{-- Tombol Hapus Akun (Shadcn Destructive) --}}
                         <button type="button"
                                 {{-- Ganti button biasa dengan logic modal AlpineJS jika diinginkan --}}
                                 x-data="{}"
                                 x-on:click.prevent="if(confirm('Apakah Anda YAKIN ingin menghapus akun Anda? Tindakan ini tidak bisa dibatalkan.')) { $el.closest('form').submit() }"
                                 class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-destructive text-destructive-foreground hover:bg-destructive/90 h-10 px-4 py-2">
                             Hapus Akun Saya
                         </button>

                         {{-- Konfirmasi Password (Opsional, tapi lebih aman jika diperlukan) --}}
                         {{-- <div class="mt-4">
                             <label for="password_delete" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Konfirmasi Password</label>
                             <input id="password_delete" name="password" type="password" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-gray-200" placeholder="Masukkan password Anda">
                              Error password saat delete, perlu handling di controller atau pakai error bag 'userDeletion' dari Breeze
                             @error('password', 'userDeletion')
                                 <p class="mt-1.5 text-xs text-red-600 dark:text-red-400">{{ $message }}</p>
                             @enderror
                         </div> --}}
                    </form>
                 </div>
            </div>

        </main>

         <!-- Footer -->
         <footer class="mt-auto py-4 border-t bg-background">
             <div class="container text-center text-xs text-muted-foreground">
                 © {{ date('Y') }} {{ config('app.name', 'E-Rapot TPA') }}. All rights reserved.
             </div>
        </footer>
    </div>

    <!-- Scripts -->
    @vite(['resources/js/app.js'])
     {{-- Tidak perlu script JS vanilla untuk theme toggle/animasi karena sudah Alpine/CSS --}}
    {{-- Pastikan Anda punya script konfirmasi untuk tombol hapus --}}
     <script>
         // Logika konfirmasi hapus akun via AlpineJS sudah inline di tombol Hapus Akun
         // Pastikan body di-fade in
          document.addEventListener('DOMContentLoaded', function() {
              document.body.classList.add('loaded');
          });
     </script>
</body>
</html>