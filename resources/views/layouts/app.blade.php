<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts (Montserrat & Quicksand) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;0,600;0,700;0,800;1,700&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-quicksand antialiased text-[#1E1B18] bg-[#FAF8F5]">
        @auth
            <div class="min-h-screen bg-[#FAF8F5] flex" x-data="{ sidebarOpen: false }">
                <!-- Role-based Sidebar -->
                @if(Auth::user()->hasRole('super-admin'))
                    @include('layouts.superAdmin.sidebar')
                @elseif(Auth::user()->hasRole('admin-cabang'))
                    @include('layouts.admin.sidebar')
                @elseif(Auth::user()->hasRole('trainer'))
                    @include('layouts.trainer.sidebar')
                @else
                    @include('layouts.peserta.sidebar')
                @endif

                <div class="flex-1 flex flex-col min-w-0 min-h-screen">
                    <!-- Role-based Navbar -->
                    @if(Auth::user()->hasRole('super-admin'))
                        @include('layouts.superAdmin.navbar')
                    @elseif(Auth::user()->hasRole('admin-cabang'))
                        @include('layouts.admin.navbar')
                    @elseif(Auth::user()->hasRole('trainer'))
                        @include('layouts.trainer.navbar')
                    @else
                        @include('layouts.peserta.navbar')
                    @endif

                    <!-- Page Heading (Optional) -->
                    @isset($header)
                        <div class="bg-white border-b border-[#EBE5DF]">
                            <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </div>
                    @endisset

                    <!-- Page Content -->
                    <main class="flex-1">
                        {{ $slot ?? '' }}
                        @yield('content')
                    </main>
                </div>
            </div>
        @else
            <div class="min-h-screen bg-[#FAF8F5]">
                @include('layouts.navigation')

                @isset($header)
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <main>
                    {{ $slot ?? '' }}
                    @yield('content')
                </main>
            </div>
        @endauth

        @if(session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    if (window.Toast) {
                        window.Toast.fire({
                            icon: 'success',
                            title: {!! json_encode(session('success')) !!}
                        });
                    }
                });
            </script>
        @endif
        @if(session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    if (window.Toast) {
                        window.Toast.fire({
                            icon: 'error',
                            title: {!! json_encode(session('error')) !!}
                        });
                    }
                });
            </script>
        @endif
        @stack('scripts')
    </body>
</html>
