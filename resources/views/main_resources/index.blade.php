<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DILG - Bohol')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/dilg-main.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css">
    <!-- Load Your Compiled JavaScript -->
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<body class="font-poppins min-h-screen flex flex-col">
    @include('main_resources.header')

    <div class="content flex-grow">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="py-2 px-4 text-center dark:border-gray-700 bg-transparent text-gray-600">
        <p>&copy; {{ date('Y') }} DILG - Bohol. All rights reserved.</p>
    </footer>   
</body>

</html>
