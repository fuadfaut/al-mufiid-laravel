<x-guest-layout>
    <div class="space-y-6">
        <div class="space-y-2 text-center">
            <h1 class="text-3xl font-bold">Daftar Santri</h1>
            <p class="text-gray-500 dark:text-gray-400">Silakan isi data diri untuk mendaftar sebagai santri</p>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="role" value="santri">

            <!-- Santri Data Section -->
            <div class="space-y-4 p-4 border border-border rounded-lg">
                <h2 class="font-semibold text-lg">Data Santri</h2>

                <!-- Nama Lengkap -->
                <div class="space-y-2">
                    <label for="nama_lengkap" class="text-sm font-medium leading-none">Nama Lengkap</label>
                    <input
                        id="nama_lengkap"
                        type="text"
                        name="nama_lengkap"
                        value="{{ old('nama_lengkap') }}"
                        required
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                </div>

                <!-- Nama Panggilan -->
                <div class="space-y-2">
                    <label for="nama_panggilan" class="text-sm font-medium leading-none">Nama Panggilan</label>
                    <input
                        id="nama_panggilan"
                        type="text"
                        name="nama_panggilan"
                        value="{{ old('nama_panggilan') }}"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <x-input-error :messages="$errors->get('nama_panggilan')" class="mt-2" />
                </div>

                <!-- Jenis Kelamin -->
                <div class="space-y-2">
                    <label class="text-sm font-medium leading-none">Jenis Kelamin</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="jenis_kelamin" value="L" {{ old('jenis_kelamin') == 'L' ? 'checked' : '' }} required class="h-4 w-4 text-primary focus:ring-primary">
                            <span>Laki-laki</span>
                        </label>
                        <label class="flex items-center space-x-2">
                            <input type="radio" name="jenis_kelamin" value="P" {{ old('jenis_kelamin') == 'P' ? 'checked' : '' }} required class="h-4 w-4 text-primary focus:ring-primary">
                            <span>Perempuan</span>
                        </label>
                    </div>
                    <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                </div>

                <!-- Tempat Lahir -->
                <div class="space-y-2">
                    <label for="tempat_lahir" class="text-sm font-medium leading-none">Tempat Lahir</label>
                    <input
                        id="tempat_lahir"
                        type="text"
                        name="tempat_lahir"
                        value="{{ old('tempat_lahir') }}"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
                </div>

                <!-- Tanggal Lahir -->
                <div class="space-y-2">
                    <label for="tanggal_lahir" class="text-sm font-medium leading-none">Tanggal Lahir</label>
                    <input
                        id="tanggal_lahir"
                        type="date"
                        name="tanggal_lahir"
                        value="{{ old('tanggal_lahir') }}"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                </div>

                <!-- Nama Ayah -->
                <div class="space-y-2">
                    <label for="nama_ayah" class="text-sm font-medium leading-none">Nama Ayah</label>
                    <input
                        id="nama_ayah"
                        type="text"
                        name="nama_ayah"
                        value="{{ old('nama_ayah') }}"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <x-input-error :messages="$errors->get('nama_ayah')" class="mt-2" />
                </div>

                <!-- Nama Ibu -->
                <div class="space-y-2">
                    <label for="nama_ibu" class="text-sm font-medium leading-none">Nama Ibu</label>
                    <input
                        id="nama_ibu"
                        type="text"
                        name="nama_ibu"
                        value="{{ old('nama_ibu') }}"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <x-input-error :messages="$errors->get('nama_ibu')" class="mt-2" />
                </div>

                <!-- Alamat -->
                <div class="space-y-2">
                    <label for="alamat" class="text-sm font-medium leading-none">Alamat</label>
                    <textarea
                        id="alamat"
                        name="alamat"
                        rows="3"
                        class="flex w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    >{{ old('alamat') }}</textarea>
                    <x-input-error :messages="$errors->get('alamat')" class="mt-2" />
                </div>

                <!-- No HP Ortu -->
                <div class="space-y-2">
                    <label for="no_hp_ortu" class="text-sm font-medium leading-none">No. HP Orang Tua</label>
                    <input
                        id="no_hp_ortu"
                        type="tel"
                        name="no_hp_ortu"
                        value="{{ old('no_hp_ortu') }}"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <x-input-error :messages="$errors->get('no_hp_ortu')" class="mt-2" />
                </div>
            </div>

            <!-- Account Data Section -->
            <div class="space-y-4 p-4 border border-border rounded-lg">
                <h2 class="font-semibold text-lg">Data Akun</h2>

                <!-- Name for User Account -->
                <div class="space-y-2">
                    <label for="name" class="text-sm font-medium leading-none">Nama Pengguna</label>
                    <input
                        id="name"
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email Address -->
                <div class="space-y-2">
                    <label for="email" class="text-sm font-medium leading-none">Email</label>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div class="space-y-2">
                    <label for="password" class="text-sm font-medium leading-none">Password</label>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-2">
                    <label for="password_confirmation" class="text-sm font-medium leading-none">Konfirmasi Password</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                    />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                </div>
            </div>

            <div class="flex items-center justify-between pt-4">
                <a class="text-sm underline text-primary hover:text-primary/90" href="{{ route('login') }}">
                    {{ __('Sudah punya akun?') }}
                </a>

                <button type="submit" class="inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 bg-primary text-primary-foreground hover:bg-primary/90 h-10 px-4 py-2">
                    {{ __('Daftar') }}
                </button>
            </div>
        </form>
    </div>
</x-guest-layout>
