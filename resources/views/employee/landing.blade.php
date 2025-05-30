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

<div>
    @include('main_resources.header')
    <div class="fixed top-0 right-0 sm:top-4 sm:right-4 z-[9999]">
        <x-notify::notify />
    </div>
    @notifyJs

    <!-- DESKTOP & TABLET VIEW -->
    <div class="hidden sm:block">
        <div class="mt-12 flex justify-between items-start gap-6 px-6 sm:px-10 md:px-16 lg:px-24">
            
            <!-- Left Side -->
            <div class="lg:w-1/2 w-full flex flex-col mt-[65px] justify-center text-center lg:text-left">
                <img src="/img/dilg-main.png" alt="DILG Logo" class="h-[80px] w-[80px] mx-auto lg:mx-0 mb-4">
                <h1 class="text-3xl lg:text-5xl font-bold text-primary leading-tight">
                    DILG Compensatory <br> Time Off & Leave Management System
                </h1>
                <p class="text-gray-600 text-base lg:text-lg mt-4 text-justify">
                    A seamless and secure platform for managing leave requests and tracking employee records within the Department of the Interior and Local Government.
                </p>
            </div>

            <!-- Right Side -->
            <div class="lg:w-1/2 w-full flex mt-8 lg:mt-[60px] items-center justify-center relative">
                <img src="/img/landing-image.avif" 
                    alt="Office Illustration"
                    class="max-w-full lg:max-w-lg rounded-lg shadow-xl transform hover:scale-105 transition duration-500">
                
                <div class="absolute mt-[-90px] animate-bounce bg-primary text-white text-xs lg:text-sm px-4 py-2 rounded-lg shadow-lg">
                    📊 Efficient Tracking
                </div>
            </div>
        </div>
    </div>

    <!-- MOBILE VIEW ONLY -->
    <div class="block sm:hidden px-4 mt-10 text-center">
        <img src="/img/dilg-main.png" alt="DILG Logo" class="h-[60px] w-[60px] mx-auto mb-4">
        <h1 class="text-2xl font-bold text-primary leading-snug">
            DILG Compensatory Time Off & Leave Management
        </h1>
        <p class="text-gray-600 text-sm mt-4 text-justify">
            A seamless and secure platform for managing leave requests and tracking employee records within the Department of the Interior and Local Government.
        </p>

        <div class="mt-6 flex justify-center">
            <img src="/img/landing-image.avif" 
                 alt="Office Illustration"
                 class="w-full max-w-xs rounded-lg shadow-md">
        </div>

        <div class="mt-[-145px] ml-[140px] animate-bounce w-[25%] bg-primary text-white text-[9px] py-1 rounded-lg shadow-md">
            📊 Efficient Tracking
        </div>
    </div>

    <div class="bg"></div>
    <div class="bg bg2"></div>
    <div class="bg bg3"></div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", () => {
        // Profile Dropdown
        const profileDropdownBtn = document.getElementById("dropdown-btn");
        const profileDropdownMenu = document.getElementById("dropdown-menu");

        if (profileDropdownBtn) {
            profileDropdownBtn.addEventListener("click", (e) => {
                e.stopPropagation();
                profileDropdownMenu.classList.toggle("hidden");
            });

            document.addEventListener("click", (e) => {
                if (!profileDropdownMenu.contains(e.target) && !profileDropdownBtn.contains(e.target)) {
                    profileDropdownMenu.classList.add("hidden");
                }
            });
        }

        // Login Dropdown
        const loginDropdownBtn = document.getElementById("customDropdownBtn");
        const loginDropdownMenu = document.getElementById("customDropdownMenu");
        const loginDropdownArrow = document.getElementById("dropdownArrow");

        if (loginDropdownBtn) {
            loginDropdownBtn.addEventListener("mousedown", (e) => {
                e.stopPropagation();
                loginDropdownMenu.classList.toggle("hidden");
                loginDropdownArrow.classList.toggle("rotate-180");
            });

            document.addEventListener("click", (e) => {
                if (!loginDropdownMenu.contains(e.target) && !loginDropdownBtn.contains(e.target)) {
                    loginDropdownMenu.classList.add("hidden");
                    loginDropdownArrow.classList.remove("rotate-180");
                }
            });
        }
    });
</script>
@notifyCss
@endsection
