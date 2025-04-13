<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'DILG - Bohol')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('img/dilg-main.png') }}" type="image/png">
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css"> --}}
    {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/5.11.3/main.min.css"> --}}
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <style>
        @media (max-width: 640px) {
            body {
                font-size: 14px;
            }
            main {
                padding: 0.75rem !important;
            }
        }
        
        html, body {
            max-width: 100%;
            overflow-x: hidden;
        }
    </style>
</head>

<body class="font-sans antialiased bg-white min-h-screen flex flex-col">
    <div class="content flex-grow">
        @yield('content')
    </div>

    <footer class="py-2 px-4 text-center dark:border-gray-700 bg-transparent text-gray-600">
        @include('layouts.developers')

        <button data-modal-target="developersModal" data-modal-toggle="developersModal" type="button" class="fixed bottom-4 left-4 z-50 p-3 bg-white text-black rounded-full shadow-lg hover:bg-blue-700 hover:text-white">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
        </button>
           
        <p>&copy; {{ date('Y') }} DILG - Bohol. All rights reserved.</p>
    </footer>   
</body>
<script>
    document.querySelectorAll('[data-modal-toggle]').forEach(button => {
        button.addEventListener('click', () => {
        const modalId = button.getAttribute('data-modal-toggle');
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
        });
    });
    
    document.querySelectorAll('[data-modal-hide]').forEach(button => {
        button.addEventListener('click', () => {
        const modalId = button.getAttribute('data-modal-hide');
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
        });
    });
    </script>
</html>
