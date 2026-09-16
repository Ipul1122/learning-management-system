<!-- Desktop Sidebar -->
<aside class="hidden lg:flex lg:flex-col w-64 bg-[#1E1B18] text-white flex-shrink-0 border-r border-[#322E2B] transition-all duration-300">
    <!-- Brand Header -->
    <div class="h-16 px-6 flex items-center gap-3 border-b border-[#322E2B]">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] flex items-center justify-center text-white shadow-sm font-montserrat font-extrabold text-lg">
            L
        </div>
        <div class="flex flex-col">
            <span class="font-montserrat font-extrabold text-sm tracking-tight text-white">
                LMS <span class="text-[#FF6B00]">Multi-Cabang</span>
            </span>
            <span class="text-[10px] font-mono uppercase tracking-widest text-[#FF6B00]">
                Super Admin
            </span>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto font-quicksand">
        <div class="px-3 pb-2 text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#6E675F]">
            Menu Utama
        </div>

        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Master Cabang -->
        <a href="{{ route('admin.branches.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('admin.branches.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <span>Master Cabang</span>
        </a>

        <!-- Admin Cabang -->
        <a href="{{ route('admin.admins.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('admin.admins.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <span>Admin Cabang</span>
        </a>

        <!-- Log Aktivitas -->
        <a href="{{ route('admin.logs.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('admin.logs.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <span>Log Aktivitas</span>
        </a>

        <!-- Kelola Berita (Fase 6) -->
        <a href="{{ route('admin.news.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('admin.news.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
            <span>Kelola Berita</span>
        </a>
    </div>

    <!-- User Footer Card -->
    <div class="p-4 border-t border-[#322E2B] bg-[#171412]">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-[#2D1B18] text-[#FF6B00] border border-[#FF6B00]/20 flex items-center justify-center font-montserrat font-bold text-xs">
                {{ strtoupper(substr(Auth::user()->name ?? 'SA', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-montserrat font-bold text-xs text-white truncate">{{ Auth::user()->name }}</div>
                <div class="text-[10px] text-[#FF6B00] truncate font-mono">Super Admin</div>
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

<!-- Mobile Sidebar Drawer -->
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
            <span class="font-montserrat font-extrabold text-sm tracking-tight text-white">
                LMS <span class="text-[#FF6B00]">Super Admin</span>
            </span>
        </div>

        <!-- Mobile Navigation Menu -->
        <div class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('admin.branches.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('admin.branches.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
                <span>Master Cabang</span>
            </a>

            <a href="{{ route('admin.admins.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('admin.admins.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>Admin Cabang</span>
            </a>

            <a href="{{ route('admin.logs.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('admin.logs.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span>Log Aktivitas</span>
            </a>

            <!-- Kelola Berita (Fase 6) -->
            <a href="{{ route('admin.news.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('admin.news.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                </svg>
                <span>Kelola Berita</span>
            </a>
        </div>
    </div>
</div>
