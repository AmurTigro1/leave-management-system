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
    <!-- Load Your Compiled JavaScript -->
    @vite('resources/js/app.js')
    @vite('resources/css/app.css')
</head>
<body class="font-poppins h-screen overflow-hidden">
    <div x-data="{ sidebarOpen: false }" class="flex h-screen">
  <!-- Sidebar -->
  @include('employee.partials.sidebar')

  <!-- Main Content -->
  <div class="flex-1 flex flex-col min-h-screen">
      <!-- Header -->
      @include('employee.partials.header')

      <!-- Page Content (Scrollable Area) -->
      <main class="flex-1 p-6 overflow-y-auto">
          @yield('content')
      </main>

      <!-- Footer (Placed inside the flex container) -->
      <footer class="p-6 text-center border-t dark:border-gray-700">
          <p>&copy; {{ date('Y') }} DILG - Bohol. All rights reserved.</p>
      </footer>
  </div>
</div> 
</body>
</html>
