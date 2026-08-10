<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'B2B Wholesale Computer & IT Equipment Distributor')</title>
    <meta name="description" content="@yield('meta_description', 'Leading enterprise wholesale supplier of computers, laptops, components, servers, and IT equipment for businesses, resellers, and institutions.')">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- FontAwesome 6 CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- Custom B2B Styles -->
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

    @stack('styles')
</head>
<body>

    <!-- Header Navigation -->
    @include('frontend.layouts.header')

    <!-- Mobile Offcanvas Menu -->
    @include('frontend.layouts.mobile-menu')

    <!-- Toast Component -->
    @include('frontend.components.toast')

    <!-- Main Content Area -->
    <main class="py-4">
        @yield('content')
    </main>

    <!-- Footer -->
    @include('frontend.layouts.footer')

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- B2B App Scripts -->
    <script src="{{ asset('frontend/js/app.js') }}"></script>
    <script src="{{ asset('frontend/js/cart.js') }}"></script>
    <script src="{{ asset('frontend/js/wishlist.js') }}"></script>

    @stack('scripts')
</body>
</html>
