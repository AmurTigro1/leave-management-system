@extends('main_resources.index')
@section('content')

<div class="h-screen flex">
    <!-- Left Side -->
    <div class="w-1/2 flex flex-col justify-center px-24">
        <img src="https://toppng.com/uploads/preview/dilg-logo-11550728661mdmkpc6xag.png" alt="DILG Logo" class="h-16 w-16 mb-4">
        <h1 class="text-5xl font-bold text-primary leading-tight">
            DILG Leave <br> Management System
        </h1>
        <p class="text-gray-600 text-lg mt-4">
            A seamless and secure platform for managing leave requests and tracking employee records within the Department of the Interior and Local Government.
        </p>
        <div class="mt-6">
            <a href="{{ route('login') }}" class="px-6 py-3 bg-primary text-white text-lg rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                Get Started
            </a>
            <a href="{{ route('register') }}" class="ml-4 px-6 py-3 bg-gray-700 text-white text-lg rounded-lg shadow-lg hover:bg-gray-800 transition duration-300">
                Register
            </a>
        </div>
    </div>

    <!-- Right Side -->
    <div class="w-1/2 flex items-center justify-center relative">
        <img src="https://img.freepik.com/free-vector/flat-hand-drawn-coworking-space_52683-54823.jpg?ga=GA1.1.1609491871.1738904251&semt=ais_hybrid" alt="Office Illustration"
            class="max-w-lg rounded-lg shadow-xl transform hover:scale-105 transition duration-500">
        <div class="absolute top-10 right-28 animate-pulse bg-accent text-white text-sm px-4 py-2 rounded-lg shadow-lg">
            ✅ Secure & Reliable
        </div>
        <div class="absolute animate-bounce bg-primary text-white text-sm px-4 py-2 rounded-lg shadow-lg">
            📊 Efficient Tracking
        </div>
    </div>
</div>

</body>
</html>
    
@endsection