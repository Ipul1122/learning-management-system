<header class="h-16 bg-white border-b border-[#EBE5DF] sticky top-0 z-30 flex items-center justify-between px-4 sm:px-6 lg:px-8">
    <!-- Left: Mobile Toggle & Page Breadcrumb -->
    <div class="flex items-center gap-3">
        <button type="button"
                @click="sidebarOpen = true"
                class="lg:hidden p-2 rounded-xl text-[#6E675F] hover:text-[#1E1B18] hover:bg-[#FAF8F5] focus:outline-none focus:ring-2 focus:ring-[#FF6B00] transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

        <div class="hidden sm:flex items-center gap-2 text-xs font-montserrat">
            <span class="text-[#6E675F]">Admin Cabang</span>
            <span class="text-[#EBE5DF]">/</span>
            <span class="font-bold text-[#1E1B18]">
                @if(request()->routeIs('cabang.dashboard'))
                    Dashboard
                @elseif(request()->routeIs('cabang.trainers.*'))
                    Trainer Cabang
                @elseif(request()->routeIs('cabang.classes.*') || request()->routeIs('cabang.sessions.*'))
                    Kelas & Jadwal Sesi
                @elseif(request()->routeIs('cabang.logs.*'))
                    Log Audit Cabang
                @else
                    Operasional
                @endif
            </span>
        </div>
    </div>

    <!-- Right: Branch Chip, Role Badge & User Dropdown -->
    <div class="flex items-center gap-3">
        <!-- Branch Chip -->
        @if(Auth::user()->branch)
            <span class="hidden md:inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-montserrat font-bold bg-[#FAF8F5] text-[#1E1B18] border border-[#EBE5DF]">
                <span>🏢</span>
                <span>{{ Auth::user()->branch->name }}</span>
            </span>
        @endif

        <!-- Role Badge -->
        <span class="px-2.5 py-1 rounded-full text-xs font-bold font-montserrat bg-[#FEF2F2] text-[#DC2626] border border-[#FECACA] hidden sm:inline-flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-[#DC2626]"></span>
            <span>Admin Cabang</span>
        </span>

        <!-- Settings Dropdown -->
        <x-dropdown align="right" width="48">
            <x-slot name="trigger">
                <button class="inline-flex items-center gap-2 px-3 py-1.5 border border-[#EBE5DF] text-xs leading-4 font-semibold rounded-xl text-[#1E1B18] bg-[#FAF8F5] hover:bg-white focus:outline-none transition ease-in-out duration-150">
                    <div class="w-6 h-6 rounded-lg bg-[#2D1B18] text-[#DC2626] flex items-center justify-center font-bold text-[10px]">
                        {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
                    </div>
                    <span class="font-montserrat font-bold">{{ Auth::user()->name }}</span>

                    <svg class="fill-current h-3.5 w-3.5 text-[#6E675F]" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </x-slot>

            <x-slot name="content">
                <div class="px-4 py-2 border-b border-[#EBE5DF] text-xs">
                    <div class="font-bold text-[#1E1B18]">{{ Auth::user()->name }}</div>
                    <div class="text-[#6E675F] text-[11px] truncate">{{ Auth::user()->email }}</div>
                    @if(Auth::user()->branch)
                        <div class="text-[10px] text-[#DC2626] mt-0.5 font-mono">{{ Auth::user()->branch->name }}</div>
                    @endif
                </div>

                <x-dropdown-link :href="route('profile.edit')">
                    {{ __('Profil Akun') }}
                </x-dropdown-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();"
                            class="text-[#DC2626] font-semibold">
                        {{ __('Keluar (Log Out)') }}
                    </x-dropdown-link>
                </form>
            </x-slot>
        </x-dropdown>
    </div>
</header>
