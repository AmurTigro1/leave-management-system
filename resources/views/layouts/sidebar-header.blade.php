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
    <script src="https://unpkg.com/htmx.org@1.9.6"></script>
    
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
    
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
<body class="font-poppins h-screen overflow-hidden" x-data="{ sidebarOpen: false }">
    <div class="flex h-screen">
        @include('employee.partials.sidebar')

        <div class="flex-1 flex flex-col min-h-screen overflow-hidden">
            @include('employee.partials.header')

            <main class="flex-1 p-2 sm:p-4 md:p-6 overflow-y-auto min-w-0">
                @yield('content')
            </main>
        </div>
    </div>
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