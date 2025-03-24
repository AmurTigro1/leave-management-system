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

@if (Auth::check())
<div class="py-4">
    <div class="container mx-auto flex justify-between items-center text-center mb-2">
        <div class="text-blue-600 font-bold text-2xl">DILG CTO & LMS</div>

        <div class="relative ml-[50px]">
            <button id="dropdown-btn" class="flex items-center text-gray-600 font-semibold px-2 py-1 rounded-lg hover:bg-gray-100 focus:outline-none">
                <div class="w-8 h-8 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center mr-2">
                    @if (auth()->user()->profile_image)
                        <img src="{{ asset('storage/profile_images/' . auth()->user()->profile_image) }}" alt="Profile Photo" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('img/default-avatar.png')}}" alt="default avatar" class="w-full h-full object-cover">
                    @endif
                </div>
                {{ Auth::user()->name }}
                <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M5.292 7.292a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 011.414 1.414l-4 4a1 1 0-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
            <!-- Dropdown Content -->
            <div id="dropdown-menu" class="absolute hidden bg-white shadow-md rounded-lg mt-2 w-[115px] z-50">
                <ul class="py-2 text-gray-600">
                    <li>
                        @if (Auth::user()->role === 'employee')
                            <a href="{{ route('lms_cto.dashboard') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Dashboard</a>
                        @elseif (Auth::user()->role === 'hr')
                            <a href="{{ route('hr.dashboard') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Dashboard</a>
                        @elseif (Auth::user()->role === 'supervisor')
                            <a href="{{ route('supervisor.dashboard') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Dashboard</a>
                        @elseif (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-blue-500">Dashboard</a>
                        @endif
                    </li>     
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 hover:text-red-500">
                                Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="flex justify-center items-center">
        <div class="bg-white p-4 w-[70%] rounded-lg mt-[50px]">
           <div class="flex justify-between items-start">
                <div class="w-full">
                    <div class="mt-[50px] flex justify-center">
                        <div class="relative bg-white border border-blue-600 rounded-lg p-4 shadow-md max-w-sm">
                            <p class="text-2xl text-blue-600 font-bold text-center">Hello, {{ Auth::user()->name }}!</p>
                            
                            <!-- Chat bubble tail -->
                            <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 w-4 h-4 bg-white border-l border-b border-blue-600 rotate-45"></div>
                        </div>
                    </div>
                    
                    <div>
                        <img src="/img/wave.gif" alt="">
                    </div>
                </div>
                <div class="w-full text-start mt-[50px]">
                    <p class="font-bold text-[35px] mb-4">Welcome to the Leave & Overtime Management System!</p>
                    <p>Effortlessly track your leave, manage overtime requests, and stay on top of your work schedule—all in one place.</p>
                    <p>Whether you're requesting time off or logging extra hours, our system makes it simple and efficient.</p>
                    <br>
                    <p>Get started today and take control of your work-life balance!</p>
                    <br>
                    <div class="mt-4 mb-8">
                        @if (Auth::user()->role === 'employee')
                        <a href="{{ route('lms_cto.dashboard') }}" class="border-2 border-blue-500 text-blue-500 py-3 px-8 rounded-full">Get Started!</a>
                        @elseif (Auth::user()->role === 'hr')
                            <a href="{{ route('hr.dashboard') }}" class="border-2 border-blue-500 text-blue-500 py-3 px-8 rounded-full">Get Started!</a>
                        @elseif (Auth::user()->role === 'supervisor')
                            <a href="{{ route('supervisor.dashboard') }}" class="border-2 border-blue-500 text-blue-500 py-3 px-8 rounded-full">Get Started!</a>
                        @elseif (Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="border-2 border-blue-500 text-blue-500 py-3 px-8 rounded-full">Get Started!</a>
                        @endif
                    </div>
                </div>
           </div>
        </div>
    </div>
</div>
    <div class="bg"></div>
    <div class="bg bg2"></div>
    <div class="bg bg3"></div>
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
    @else
        <div>
            @include('main_resources.header')
            <x-notify::notify />
            @notifyJs
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
        
            <div class="bg"></div>
            <div class="bg bg2"></div>
            <div class="bg bg3"></div>
        </div>
        

        @notifyCss
@endif

     @endsection