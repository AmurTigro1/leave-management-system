@extends('layouts.supervisor.sidebar-header')
@section('content')
<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>

<div class="w-full p-3 rounded-xl shadow-md">
        <!-- Back Button with Animation -->
        <div class="bg-[url('/public/img/Background.png')] bg-cover bg-center bg-no-repeat min-h-[200px] sm:min-h-[300px] md:min-h-[400px] w-full rounded-lg overflow-hidden"></div>     
         
          
    <!-- Profile Image & Upload -->
    <div class="relative w-32 h-32 ml-6 mt-[-100px]">
        <!-- Image Wrapper (Group for Hover) -->
        <div class="relative group w-full h-full ml-6">
            <!-- Profile Image -->
            <img id="profile-preview"
                src="{{ auth()->user()->profile_image ? asset('storage/profile_images/' . auth()->user()->profile_image) : asset('img/default-avatar.png') }}"
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
                <div class="ml-[35px]">
                    <h2 class="text-2xl font-medium text-gray-900">
                        {{ __('Password') }}
                    </h2>
                </div>
                <div class="mr-3">
                    <a class="text-blue-600" href="/supervisor-profile"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go back</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="p-3 rounded-xl mt-2 flex justify-start items-center">
    <div class="mr-4">
        <h3 class="text-2xl font-semibold text-gray-700 ml-[25px]">Update Password</h3>
        <div class="ml-[25px]">
            <header>
                <p class="mt-1 text-sm text-gray-600">
                    {{ __('Ensure your account is using a long, random password to stay secure.') }}
                </p>
            </header>
        
            <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                @csrf
                @method('put')
        
                <div>
                    <x-input-label for="update_password_current_password" :value="__('Current Password')" />
                    <x-text-input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>
        
                <div>
                    <x-input-label for="update_password_password" :value="__('New Password')" />
                    <x-text-input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                </div>
        
                <div>
                    <x-input-label for="update_password_password_confirmation" :value="__('Confirm Password')" />
                    <x-text-input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                    <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                </div>
        
                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
        
                    @if (session('status') === 'password-updated')
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
        <img src="/img/password.jpg" alt="">
    </div>
</div> 
@endsection
@notifyCss
