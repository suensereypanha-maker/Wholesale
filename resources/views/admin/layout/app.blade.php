<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'B2B Wholesale Dashboard') - Wholesale Admin</title>

    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Chart.js for B2B Analytics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <!-- Alpine.js for interactive UI dropdowns & components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.5/dist/cdn.min.js"></script>

    <!-- Tailwind CSS / Vite -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <!-- Fallback Tailwind CSS CDN for instant rendering -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        fontFamily: {
                            sans: ['Inter', 'sans-serif'],
                        },
                        colors: {
                            brand: {
                                50: '#f0f7ff',
                                100: '#e0effe',
                                500: '#2563eb',
                                600: '#1d4ed8',
                                700: '#1e40af',
                                900: '#1e3a8a',
                            }
                        }
                    }
                }
            }
        </script>
    @endif

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc;
            color: #0f172a;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Smooth sidebar transition */
        .sidebar-transition {
            transition: transform 0.25s ease-in-out, width 0.25s ease-in-out;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full antialiased text-slate-800 bg-slate-50">

    <div class="min-h-screen flex flex-col bg-slate-50" x-data="{ sidebarOpen: false, sidebarCollapsed: false }">

        <!-- Mobile Sidebar Backdrop Overlay -->
        <div 
            id="sidebarBackdrop"
            class="fixed inset-0 z-40 bg-slate-900/40 backdrop-blur-xs lg:hidden hidden transition-opacity duration-300"
            onclick="toggleMobileSidebar()"
        ></div>

        <!-- Main Sidebar Component -->
        @include('admin.layout.sidebar')

        <!-- Main Wrapper Content (Header + Body + Footer) -->
        <div class="lg:pl-64 flex flex-col flex-1 min-h-screen transition-all duration-300" id="mainContentWrapper">

            <!-- Top Header Component -->
            @include('admin.layout.header')

            <!-- Main Page Content Slot -->
            <main class="flex-1 px-4 sm:px-6 lg:px-8 py-6 w-full">
                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-5 flex items-center justify-between p-4 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl shadow-xs">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-circle-check text-emerald-600 text-lg"></i>
                            <span class="text-sm font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" type="button" class="text-emerald-500 hover:text-emerald-700 p-1">
                            <i class="fas fa-xmark text-sm"></i>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-transition class="mb-5 flex items-center justify-between p-4 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl shadow-xs">
                        <div class="flex items-center gap-3">
                            <i class="fas fa-circle-exclamation text-rose-600 text-lg"></i>
                            <span class="text-sm font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" type="button" class="text-emerald-500 hover:text-rose-700 p-1">
                            <i class="fas fa-xmark text-sm"></i>
                        </button>
                    </div>
                @endif

                @yield('content')
            </main>


            <!-- Footer Component -->
            @include('admin.layout.footer')
        </div>
    </div>

    <!-- Toggle Sidebar Scripts -->
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const backdrop = document.getElementById('sidebarBackdrop');
            if (sidebar && backdrop) {
                sidebar.classList.toggle('-translate-x-full');
                backdrop.classList.toggle('hidden');
            }
        }

        function toggleDesktopSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const wrapper = document.getElementById('mainContentWrapper');
            const brandText = document.querySelectorAll('.sidebar-brand-text');
            const navText = document.querySelectorAll('.nav-item-text');

            if (sidebar && wrapper) {
                if (sidebar.classList.contains('lg:w-64')) {
                    // Collapse sidebar
                    sidebar.classList.remove('lg:w-64');
                    sidebar.classList.add('lg:w-20');
                    wrapper.classList.remove('lg:pl-64');
                    wrapper.classList.add('lg:pl-20');

                    brandText.forEach(el => el.classList.add('lg:hidden'));
                    navText.forEach(el => el.classList.add('lg:hidden'));
                } else {
                    // Expand sidebar
                    sidebar.classList.remove('lg:w-20');
                    sidebar.classList.add('lg:w-64');
                    wrapper.classList.remove('lg:pl-20');
                    wrapper.classList.add('lg:pl-64');

                    brandText.forEach(el => el.classList.remove('lg:hidden'));
                    navText.forEach(el => el.classList.remove('lg:hidden'));
                }
            }
        }
    </script>

    @stack('scripts')
</body>
</html>
