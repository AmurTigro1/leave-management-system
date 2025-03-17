@extends('layouts.sidebar-header')
@section('content')
<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>

<div class="w-full p-3 rounded-xl shadow-md">
        <!-- Back Button with Animation -->
        <div class="bg-[url('/public/img/office-image.jpg')] bg-cover bg-center bg-no-repeat min-h-[400px] md:min-h-[450px] w-full rounded-lg overflow-hidden">
        </div>      
          
    <!-- Profile Image & Upload -->
    <div class="relative w-32 h-32 ml-6 mt-[-100px]">
        <!-- Image Wrapper (Group for Hover) -->
        <div class="relative group w-full h-full ml-6">
            <!-- Profile Image -->
            <img id="profile-preview"
                src="{{ auth()->user()->profile_image ? asset('storage/profile_images/' . auth()->user()->profile_image) : asset('default-avatar.png') }}"
                class="w-full h-full rounded-full object-cover border-4 border-gray-300 shadow-md cursor-pointer">

            <!-- Hover Overlay (Only on Image Hover) -->
            <label for="profile_image" 
                class="absolute inset-0 bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 flex justify-center items-center transition-opacity duration-300 cursor-pointer pointer-events-none">
                <span class="text-white text-sm font-medium pointer-events-auto">Change Image</span>
            </label>
        </div>

        <!-- Hidden File Input & Update Button -->
        <form action="{{ route('profile.update-image') }}" method="POST" enctype="multipart/form-data" class="relative mt-1">
            @csrf
            <input type="file" name="profile_image" id="profile_image" class="hidden">
            
            <!-- Update Button (Moved Down & Right) -->
            <button id="update-button" type="submit" 
                class="absolute top-[-65px] -right-20 bg-green-500 text-white px-4 py-1 text-sm rounded-md hidden hover:bg-green-600 transition z-20 pointer-events-auto">
                Update
            </button>
        </form>
    </div>

    
    <!-- User Info -->
    <div class="ml-[25px] py-2">
        <div class="mt-3 space-y-3">
            <div class="flex justify-between items-center">
                <div class="ml-[50px]">
                    <h2 class="text-2xl font-medium text-gray-900">
                        {{ __('Profile') }}
                    </h2>
                </div>
                <div class="mr-3">
                    <a class="text-blue-600" href="/lms-profile"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go back</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="p-3 rounded-xl mt-2 flex justify-start items-center">
    <div class="mr-4">
        <h3 class="text-2xl font-semibold text-gray-700 ml-[25px]">Personal Information</h3>
        <div class="ml-[25px]">
            <header>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __("Update your account's profile information and email address.") }}
                </p>
            </header>
        
            <form method="post" action="{{ route('employee-profile.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('patch')
        
                <div>
                    <x-input-label for="last_name" :value="__('Last Name')" />
                    <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required autofocus autocomplete="last_name" />
                    <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
                </div>

                <div>
                    <x-input-label for="middle_name" :value="__('Middle Name')" />
                    <x-text-input id="middle_name" name="middle_name" type="text" class="mt-1 block w-full" :value="old('middle_name', $user->middle_name)" required autofocus autocomplete="middle_name" />
                    <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
                </div>

                <div>
                    <x-input-label for="first_name" :value="__('First Name')" />
                    <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required autofocus autocomplete="first_name" />
                    <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
                </div>
        
                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
        
                    @if (session('status') === 'profile-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600"
                        >{{ __('Saved.') }}</p>
                    @endif
                </div>
            </form>   
        </div>
    </div>
    <div class="w-[500px] h-[480px]">
        <img src="/img/profile.jpg" alt="">
    </div>
</div> 
<div class="p-3 rounded-xl">
    <div class="flex justify-start items-center">
        <div class="w-[45%]">
            <h3 class="text-2xl font-semibold text-gray-700 mt-4 ml-[25px]">Email Information</h3>
            <header class="ml-[25px]">
                <p class="mt-1 text-sm text-gray-600">
                    {{ __("Keep your email secure and safe.") }}
                </p>
            </header>
            <div class="ml-[25px]">
                <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                    @csrf
                </form>
            
                <form method="post" action="{{ route('employee-email.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')
            
                    <div>
                        <x-input-label for="name" :value="__('Name')" />
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>
            
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
            
                        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                            <div>
                                <p class="text-sm mt-2 text-gray-800">
                                    {{ __('Your email address is unverified.') }}
            
                                    <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        {{ __('Click here to re-send the verification email.') }}
                                    </button>
                                </p>
            
                                @if (session('status') === 'verification-link-sent')
                                    <p class="mt-2 font-medium text-sm text-green-600">
                                        {{ __('A new verification link has been sent to your email address.') }}
                                    </p>
                                @endif
                            </div>
                        @endif
                    </div>
            
                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Save') }}</x-primary-button>
            
                        @if (session('status') === 'email-updated')
                            <p
                                x-data="{ show: true }"
                                x-show="show"
                                x-transition
                                x-init="setTimeout(() => show = false, 2000)"
                                class="text-sm text-gray-600"
                            >{{ __('Saved.') }}</p>
                        @endif
                    </div>
                </form>   
            </div>
        </div>
        <div class="w-[65%] mr-[200px]">
            <img src="/img/email.jpg" alt="">
        </div>
    </div>
</div>
@endsection
@notifyCss