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

    /* Mobile-first styles */
    .landing-container {
        display: flex;
        flex-direction: column;
        padding: 0 1.5rem;
        margin-top: 3rem;
    }

    .landing-left {
        width: 100%;
        margin-top: 4rem;
        text-align: center;
    }

    .landing-logo {
        height: 80px;
        width: 80px;
        margin: 0 auto 1rem;
    }

    .landing-title {
        font-size: 1.875rem;
        line-height: 2.25rem;
        font-weight: bold;
        color: #3b82f6; /* primary color */
    }

    .landing-description {
        color: #4b5563; /* gray-600 */
        font-size: 1rem;
        margin-top: 1rem;
        text-align: justify;
    }

    .landing-right {
        width: 100%;
        margin-top: 2rem;
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .landing-image {
        max-width: 100%;
        border-radius: 0.5rem;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        transition: transform 500ms;
    }

    .landing-image:hover {
        transform: scale(1.05);
    }

    .floating-tag {
        position: absolute;
        margin-top: -5.5rem;
        animation: bounce 2s infinite;
        background-color: #3b82f6; /* primary color */
        color: white;
        font-size: 0.75rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }

    @keyframes bounce {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    /* Desktop styles */
    @media (min-width: 1024px) {
        .landing-container {
            flex-direction: row;
            padding: 0 6rem;
            margin-top: 3rem;
        }

        .landing-left {
            width: 50%;
            margin-top: 4.5rem;
            text-align: left;
        }

        .landing-logo {
            margin: 0 0 1rem;
        }

        .landing-title {
            font-size: 3rem;
            line-height: 1;
        }

        .landing-description {
            font-size: 1.125rem;
        }

        .landing-right {
            width: 50%;
            margin-top: 3.75rem;
        }

        .landing-image {
            max-width: 32rem;
        }

        .floating-tag {
            font-size: 0.875rem;
        }
    }
</style>

<div>
    @include('main_resources.header')
    <div class="fixed top-0 right-0 sm:top-4 sm:right-4 z-[9999]">
        <x-notify::notify />
    </div>
    @notifyJs
    
    <div class="landing-container">
        <!-- Left Side -->
        <div class="landing-left">
            <img src="/img/dilg-main.png" alt="DILG Logo" class="landing-logo">
            <h1 class="landing-title">
                DILG Compensatory <br> Time Off & Leave Management System
            </h1>
            <p class="landing-description">
                A seamless and secure platform for managing leave requests and tracking employee records within the Department of the Interior and Local Government.
            </p>
        </div>

        <!-- Right Side -->
        <div class="landing-right">
            <img src="/img/landing-image.avif" 
                alt="Office Illustration"
                class="landing-image">
            
            <div class="floating-tag">
                📊 Efficient Tracking
            </div>
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