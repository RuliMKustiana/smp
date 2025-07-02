<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Dashboard')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Vite (Bootstrap JS untuk Dropdown) & CSS Kustom -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @livewireStyles
</head>

<body>
    <div class="dashboard-layout">
        @include('layouts.sidebars.sidebar')
        <!-- ===== MAIN CONTENT WRAPPER ===== -->
        <div class="main-content-wrapper">
            <!-- Top Header -->
            @include('layouts.navbars.nav')
            <!-- Page Content -->
            <main class="page-content">
                @yield('content')
            </main>
        </div>
    </div>
    @livewireScripts
    @stack('scripts')
</body>
@include('layouts.footers.footer')

</html>
