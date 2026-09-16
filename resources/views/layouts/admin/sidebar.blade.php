<!-- Desktop Sidebar (Admin Cabang) -->
<aside class="hidden lg:flex lg:flex-col w-64 bg-[#1E1B18] text-white flex-shrink-0 border-r border-[#322E2B] transition-all duration-300">
    <!-- Brand Header -->
    <div class="h-16 px-6 flex items-center gap-3 border-b border-[#322E2B]">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] flex items-center justify-center text-white shadow-sm font-montserrat font-extrabold text-lg">
            L
        </div>
        <div class="flex flex-col min-w-0">
            <span class="font-montserrat font-extrabold text-sm tracking-tight text-white truncate">
                LMS <span class="text-[#FF6B00]">Cabang</span>
            </span>
            <span class="text-[10px] font-mono uppercase tracking-widest text-[#F87171] truncate">
                {{ Auth::user()->branch?->name ?? 'Admin Cabang' }}
            </span>
        </div>
    </div>

    <!-- Branch Badge Banner -->
    @if(Auth::user()->branch)
        <div class="px-4 pt-4 pb-1">
            <div class="px-3 py-2 rounded-xl bg-white/5 border border-white/10 flex items-center justify-between text-xs">
                <div class="flex items-center gap-2 min-w-0">
                    <span class="text-sm">🏢</span>
                    <div class="truncate">
                        <div class="font-montserrat font-bold text-white text-[11px] truncate">{{ Auth::user()->branch->name }}</div>
                        <div class="text-[10px] text-[#6E675F] font-mono">{{ Auth::user()->branch->code }} • {{ Auth::user()->branch->city }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Navigation Menu -->
    <div class="flex-1 py-4 px-4 space-y-1.5 overflow-y-auto font-quicksand">
        <div class="px-3 pb-2 text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#6E675F]">
            Operasional Cabang
        </div>

        <!-- Dashboard -->
        <a href="{{ route('cabang.dashboard') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('cabang.dashboard') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Trainer Cabang -->
        <a href="{{ route('cabang.trainers.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('cabang.trainers.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <span>Trainer Cabang</span>
        </a>

        <!-- Kelas Pelatihan -->
        <a href="{{ route('cabang.classes.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('cabang.classes.*') || request()->routeIs('cabang.sessions.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
            </svg>
            <span>Kelas Pelatihan</span>
        </a>

        <!-- Log Aktivitas Cabang -->
        <a href="{{ route('cabang.logs.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('cabang.logs.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Log Aktivitas</span>
        </a>
    </div>

    <!-- User Footer Card -->
    <div class="p-4 border-t border-[#322E2B] bg-[#171412]">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-[#2D1B18] text-[#DC2626] border border-[#DC2626]/20 flex items-center justify-center font-montserrat font-bold text-xs">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-montserrat font-bold text-xs text-white truncate">{{ Auth::user()->name }}</div>
                <div class="text-[10px] text-[#DC2626] truncate font-mono">Admin Cabang</div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout" class="text-[#6E675F] hover:text-[#DC2626] p-1.5 rounded-lg hover:bg-white/5 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                </button>
            </form>
        </div>
    </div>
</aside>

<!-- Mobile Sidebar Drawer (Admin Cabang) -->
<div x-show="sidebarOpen"
     x-cloak
     class="fixed inset-0 z-50 lg:hidden flex"
     role="dialog"
     aria-modal="true">
    <!-- Backdrop -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

    <!-- Drawer Panel -->
    <div x-show="sidebarOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="relative flex-1 flex flex-col max-w-xs w-full bg-[#1E1B18] text-white">

        <!-- Close Button -->
        <div class="absolute top-0 right-0 -mr-12 pt-4">
            <button type="button"
                    @click="sidebarOpen = false"
                    class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-white text-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Brand Header -->
        <div class="h-16 px-6 flex items-center gap-3 border-b border-[#322E2B]">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] flex items-center justify-center text-white font-montserrat font-extrabold text-lg">
                L
            </div>
            <div class="truncate">
                <span class="font-montserrat font-extrabold text-sm tracking-tight text-white block truncate">
                    LMS Cabang
                </span>
                <span class="text-[10px] text-[#DC2626] font-mono block truncate">
                    {{ Auth::user()->branch?->name ?? 'Cabang' }}
                </span>
            </div>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto">
            <a href="{{ route('cabang.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('cabang.dashboard') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('cabang.trainers.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('cabang.trainers.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span>Trainer Cabang</span>
            </a>

            <a href="{{ route('cabang.classes.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('cabang.classes.*') || request()->routeIs('cabang.sessions.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <span>Kelas Pelatihan</span>
            </a>

            <a href="{{ route('cabang.logs.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('cabang.logs.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Log Aktivitas</span>
            </a>
        </div>
    </div>
</div>
