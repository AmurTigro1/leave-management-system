@extends('main_resources.index')
@include('main_resources.header')
@section('content')

<style>
          .bg {
            animation: slide 3s ease-in-out infinite alternate;
            background-image: linear-gradient(-60deg, rgb(226, 217, 217) 50%, white 50%);
            bottom: 0;
            left: -50%;
            opacity: .5;
            position: fixed;
            right: -50%;
            top: 0;
            z-index: -1;
        }

        .bg2 {
            animation-direction: alternate-reverse;
            animation-duration: 4s;
        }

        .bg3 {
            animation-duration: 5s;
        }

        @keyframes slide {
            0% {
                transform: translateX(-25%);
            }

            100% {
                transform: translateX(25%);
            }
        }
</style>

<div class="mt-[50px] flex">
    <!-- Left Side -->
    <div class="w-1/2 flex flex-col justify-center px-24">
        <img src="/img/dilg-main.png" alt="DILG Logo" class="h-[100px] w-[100px] mb-4">
        <h1 class="text-5xl font-bold text-primary leading-tight">
            DILG Compensatory <br> Time Off. & Leave Management System
        </h1>
        <p class="text-gray-600 text-lg mt-4">
            A seamless and secure platform for managing leave requests and tracking employee records within the Department of the Interior and Local Government.
        </p>
        <div class="mt-6">
            <div class="relative group">
                <p class="px-6 py-3 bg-primary flex justify-center items-center text-white text-lg rounded-lg shadow-lg transform hover:scale-105 transition duration-300">
                    Get Started
                </p>
                <div class="absolute top-full left-1/2 transform -translate-x-1/2 mt-2 space-x-2 opacity-0 group-hover:opacity-100 transition duration-300 flex">
                    <a href="/cto_login" class="px-4 py-2 bg-blue-500 text-white text-sm rounded-lg shadow-md transition hover:bg-blue-600">Go to CTO</a>
                    <a href="/lms_login" class="px-4 py-2 bg-green-500 text-white text-sm rounded-lg shadow-md transition hover:bg-green-600">Go to LMS</a>
                </div>
            </div>
            
        </div>
    </div>

    <!-- Right Side -->
    <div class="w-1/2 flex items-center justify-center relative">
        <img src="https://img.freepik.com/free-vector/flat-hand-drawn-coworking-space_52683-54823.jpg?ga=GA1.1.1609491871.1738904251&semt=ais_hybrid" alt="Office Illustration"
            class="max-w-lg rounded-lg shadow-xl transform hover:scale-105 transition duration-500">
        <div class="absolute mt-[-450px] right-28 animate-pulse bg-accent text-white text-sm px-4 py-2 rounded-lg shadow-lg">
            ✅ Secure & Reliable
        </div>
        <div class="absolute mt-[-85px] animate-bounce bg-primary text-white text-sm px-4 py-2 rounded-lg shadow-lg">
            📊 Efficient Tracking
        </div>
    </div>
</div>

</body>
</html>

<div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>
    
@endsection