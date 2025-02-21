<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DILG - Bohol')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('public/logo.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.js"></script>

    @vite('resources/css/app.css')



</head>
<body class="font-poppins">
    @include('main_resources.header')
    


    <div class="content">
        @yield('content')
    </div>


    <!-- Footer -->
    <footer class="mt-12 p-6 text-center border-t dark:border-gray-700">
        <p>&copy; {{ date('Y') }} DILG - Bohol. All rights reserved.</p>
    </footer>   
    
</body>
</html>
