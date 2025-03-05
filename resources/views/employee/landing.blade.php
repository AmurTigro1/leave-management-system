@extends('main_resources.index')

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

<div class="mt-12 flex flex-col lg:flex-row items-center px-6 sm:px-10 md:px-16 lg:px-24">
    <!-- Left Side -->
    <div class="lg:w-1/2 w-full flex flex-col justify-center text-center lg:text-left">
        <img src="/img/dilg-main.png" alt="DILG Logo" class="h-[80px] w-[80px] mx-auto lg:mx-0 mb-4">
        <h1 class="text-3xl lg:text-5xl font-bold text-primary leading-tight">
            DILG Compensatory <br> Time Off & Leave Management System
        </h1>
        <p class="text-gray-600 text-base lg:text-lg mt-4 text-justify">
            A seamless and secure platform for managing leave requests and tracking employee records within the Department of the Interior and Local Government.
        </p>
    </div>

    <!-- Right Side -->
    <div class="lg:w-1/2 w-full flex mt-8 lg:mt-12 items-center justify-center relative">
        <img src="/img/landing-image.avif" 
            alt="Office Illustration"
            class="max-w-full lg:max-w-lg rounded-lg shadow-xl transform hover:scale-105 transition duration-500">
        
        <div class="absolute mt-[-90px] animate-bounce bg-primary text-white text-xs lg:text-sm px-4 py-2 rounded-lg shadow-lg">
            📊 Efficient Tracking
        </div>
    </div>
</div>

<div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>

@endsection
