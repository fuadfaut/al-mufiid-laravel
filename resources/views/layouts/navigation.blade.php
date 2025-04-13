<!-- Primary Navigation Menu -->
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- ... kode lainnya ... -->
            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                {{-- === PERUBAHAN DI SINI === --}}
                <x-nav-link :href="route('santri.dashboard')" :active="request()->routeIs('santri.dashboard')">
                    {{ __('Dashboard') }}
                </x-nav-link>
                {{-- ======================== --}}

                {{-- Tambahkan link lain jika perlu (misal profil santri) --}}
                 <x-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                     {{ __('Profil') }}
                 </x-nav-link>
            </div>
    <!-- ... kode lainnya ... -->

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
             {{-- === PERUBAHAN DI SINI === --}}
            <x-responsive-nav-link :href="route('santri.dashboard')" :active="request()->routeIs('santri.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            {{-- ======================== --}}

             {{-- Tambahkan link lain jika perlu --}}
              <x-responsive-nav-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
                  {{ __('Profil') }}
              </x-responsive-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                 {{-- Link profile di responsive menu --}}
                 <x-responsive-nav-link :href="route('profile.edit')">
                     {{ __('Profil Saya') }}
                 </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>