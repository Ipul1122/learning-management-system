<!-- Desktop Sidebar (Trainer) -->
<aside class="hidden lg:flex lg:flex-col w-64 bg-[#1E1B18] text-white flex-shrink-0 border-r border-[#322E2B] transition-all duration-300">
    <!-- Brand Header -->
    <div class="h-16 px-6 flex items-center gap-3 border-b border-[#322E2B]">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] flex items-center justify-center text-white shadow-sm font-montserrat font-extrabold text-lg">
            L
        </div>
        <div class="flex flex-col min-w-0">
            <span class="font-montserrat font-extrabold text-sm tracking-tight text-white truncate">
                LMS <span class="text-[#FF6B00]">Instruktur</span>
            </span>
            <span class="text-[10px] font-mono uppercase tracking-widest text-[#FED7AA] truncate">
                {{ Auth::user()->branch?->name ?? 'Trainer' }}
            </span>
        </div>
    </div>

    <!-- Navigation Menu -->
    <div class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto font-quicksand">
        <div class="px-3 pb-2 text-[10px] font-montserrat font-bold uppercase tracking-wider text-[#6E675F]">
            Panel Instruktur
        </div>

        <!-- Dashboard -->
        <a href="{{ route('trainer.dashboard') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('trainer.dashboard') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            <span>Dashboard</span>
        </a>

        <!-- Kelas Diampu -->
        <a href="{{ route('trainer.classes.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('trainer.classes.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <span>Kelas Yang Diampu</span>
        </a>

        <!-- Bank Soal Setara (PRD 3.1) -->
        <a href="{{ route('trainer.questions.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('trainer.questions.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>Bank Soal Setara</span>
        </a>

        <!-- Paket Kuis (PRD 3.2) -->
        <a href="{{ route('trainer.quizzes.index') }}"
           class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs transition duration-150 {{ request()->routeIs('trainer.quizzes.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white shadow-md' : 'text-[#FAF8F5]/80 hover:text-white hover:bg-white/5' }}">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
            </svg>
            <span>Paket Kuis</span>
        </a>
    </div>

    <!-- User Footer Card -->
    <div class="p-4 border-t border-[#322E2B] bg-[#171412]">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-[#2D1B18] text-[#FF6B00] border border-[#FF6B00]/20 flex items-center justify-center font-montserrat font-bold text-xs">
                {{ strtoupper(substr(Auth::user()->name ?? 'T', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0">
                <div class="font-montserrat font-bold text-xs text-white truncate">{{ Auth::user()->name }}</div>
                <div class="text-[10px] text-[#FF6B00] truncate font-mono">Trainer Instruktur</div>
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

<!-- Mobile Sidebar Drawer (Trainer) -->
<div x-show="sidebarOpen"
     x-cloak
     class="fixed inset-0 z-50 lg:hidden flex"
     role="dialog"
     aria-modal="true">
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/60 backdrop-blur-xs"></div>

    <div x-show="sidebarOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="relative flex-1 flex flex-col max-w-xs w-full bg-[#1E1B18] text-white">

        <div class="absolute top-0 right-0 -mr-12 pt-4">
            <button type="button"
                    @click="sidebarOpen = false"
                    class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-white text-white">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="h-16 px-6 flex items-center gap-3 border-b border-[#322E2B]">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] flex items-center justify-center text-white font-montserrat font-extrabold text-lg">
                L
            </div>
            <span class="font-montserrat font-extrabold text-sm tracking-tight text-white">
                LMS Instruktur
            </span>
        </div>

        <div class="flex-1 py-6 px-4 space-y-1.5 overflow-y-auto">
            <a href="{{ route('trainer.dashboard') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('trainer.dashboard') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5 fill-none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('trainer.classes.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('trainer.classes.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5 fill-none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <span>Kelas Yang Diampu</span>
            </a>

            <a href="{{ route('trainer.questions.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('trainer.questions.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5 fill-none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>Bank Soal Setara</span>
            </a>

            <a href="{{ route('trainer.quizzes.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-xl font-montserrat font-bold text-xs {{ request()->routeIs('trainer.quizzes.*') ? 'bg-gradient-to-r from-[#FF6B00] to-[#E11D48] text-white' : 'text-white/80 hover:bg-white/5' }}">
                <svg class="w-5 h-5 fill-none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
                <span>Paket Kuis</span>
            </a>
        </div>
    </div>
</div>
