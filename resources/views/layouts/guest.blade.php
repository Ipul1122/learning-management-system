@props(['fullPage' => false, 'title' => null])
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ $title ? $title . ' - ' . config('app.name', 'LMS') : config('app.name', 'LMS Multi-Cabang') }}</title>

        <!-- Fonts (Montserrat & Quicksand) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;0,600;0,700;0,800;1,700&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-quicksand antialiased text-[#1E1B18] bg-[#FAF8F5] min-h-screen">
        @if($fullPage)
            {{ $slot }}
        @else
            <div class="min-h-screen flex flex-col justify-center items-center py-10 px-4 sm:px-6">
                <div class="w-full max-w-md">
                    <!-- Brand Logo Header -->
                    <div class="text-center mb-6">
                        <a href="/" class="inline-flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-tr from-[#FF6B00] to-[#E11D48] flex items-center justify-center text-white shadow-md font-montserrat font-extrabold text-2xl">
                                L
                            </div>
                            <div class="text-left">
                                <div class="font-montserrat font-extrabold text-xl tracking-tight text-[#1E1B18]">
                                    LMS <span class="text-[#FF6B00]">Multi-Cabang</span>
                                </div>
                                <div class="font-quicksand text-xs font-semibold text-[#6E675F]">
                                    Sistem Manajemen Pelatihan Terpadu
                                </div>
                            </div>
                        </a>
                    </div>

                    <!-- Card Container -->
                    <div class="bg-white rounded-3xl border border-[#EBE5DF] shadow-xl p-6 sm:p-8">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        @endif
    </body>
</html>
