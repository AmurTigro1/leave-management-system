@extends('main_resources.index')
@section('content')

<style>
    .input-container {
        position: relative;
    }

    .input-container input {
        width: 100%;
        padding-left: 40px; /* Prevents text from overlapping with the icon */
        height: 40px;
        border: 1px solid #ccc;
        border-radius: 5px;
        padding-right: 10px;
    }

    .icon {
        position: absolute;
        left: 10px;
        top: 50%;
        transform: translateY(-50%);
        width: 20px;
        height: 20px;
        color: gray;
    }

    .bg {
            animation: slide 1s ease-in-out infinite alternate;
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
            animation-duration: 2s;
        }

        .bg3 {
            animation-duration: 3s;
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
<x-notify::notify />
    @notifyJs
<a href="/" class="text-blue-600 ml-2 font-bold text-xl hover:text-gray-700">
    Back 
</a>
<div class="flex items-center justify-center mt-[80px]">
    <div class="bg-white shadow-lg rounded-lg px-8 py-6 w-full max-w-md">
        <div class="text-center">
            <img class="mx-auto mb-3 w-16" src="/img/dilg-main.png" alt="DILG Logo">
            <a href="/" class="font-bold text-2xl">Department of the Interior and Local Government</a>
        </div>

        <form method="POST" action="{{ route('login') }}" class="mt-6" onsubmit="document.getElementById('loading-screen').classList.remove('hidden'); this.querySelector('button').disabled = true;">
            @csrf
        
            <!-- Email Input -->
            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-medium mb-2">Email</label>
                <div class="input-container">
                    <input id="email" type="email" class="form-control"
                        placeholder="Enter your email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                    </svg>
                </div>
                @error('email')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        
            <!-- Password Input -->
            <div class="mb-4">
                <label for="password" class="block text-gray-700 font-medium mb-2">Password</label>
                <div class="input-container">
                    <input id="password" type="password" class="form-control"
                        placeholder="Enter your password" name="password" required autocomplete="current-password">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="icon">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                    </svg>
                </div>
                @error('password')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
        
            <!-- Remember Me -->
            <div class="mb-4 flex items-center justify-between">
                <div>
                    <input class="form-check-input mr-2" type="checkbox" name="remember" id="remember"
                        {{ old('remember') ? 'checked' : '' }}>
                    <label class="text-gray-700 text-sm" for="remember">
                        {{ __('Remember Me') }}
                    </label>
                </div>
        
                {{-- <div>
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Forgot your password?') }}
                        </a>
                    @endif
                </div> --}}
            </div>
        
            <input type="hidden" name="system" value="lms"> 
        
            <!-- Login Button -->
            <button type="submit"
                class="w-full bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded transition duration-200">
                {{ __('Login') }}
            </button>
        </form>
        
        <!-- Loading Screen -->
        <div id="loading-screen" class="fixed inset-0 flex items-center justify-center bg-gray-900 bg-opacity-50 hidden">
            <div class="flex flex-col items-center bg-white p-6 rounded-lg shadow-lg">
                <svg class="animate-spin h-10 w-10 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 12h16m-8-8v16" />
                </svg>
                <p class="text-gray-700">Logging in...</p>
            </div>
        </div>        
    </div>
</div>

<div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>

@endsection
@notifyCss