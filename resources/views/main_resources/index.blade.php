<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DILG - LMS')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/dilg-main.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @vite('resources/css/app.css')

</head>
<body class="font-poppins">
    @include('main_resources.header')
    


    <div class="content">
        @yield('content')
    </div>


    <!-- Footer -->
    <footer class="mt-12 p-3 text-center bg-transparent dark:border-gray-700">
        <p><span class="text-gray-500">&copy; {{ date('Y') }} DILG - Bohol. All rights reserved.</span></p>
    </footer>   
    
</body>
</html>
