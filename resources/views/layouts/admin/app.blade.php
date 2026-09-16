<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Admin Cabang</title>

        <!-- Fonts (Montserrat & Quicksand) -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,500;0,600;0,700;0,800;1,700&family=Quicksand:wght@400;500;600;700&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-quicksand antialiased text-[#1E1B18] bg-[#FAF8F5]">
        <div class="min-h-screen bg-[#FAF8F5] flex" x-data="{ sidebarOpen: false }">
            <!-- Admin Cabang Dedicated Sidebar -->
            @include('layouts.admin.sidebar')

            <div class="flex-1 flex flex-col min-w-0 min-h-screen">
                <!-- Admin Cabang Dedicated Navbar -->
                @include('layouts.admin.navbar')

                <!-- Page Heading -->
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
