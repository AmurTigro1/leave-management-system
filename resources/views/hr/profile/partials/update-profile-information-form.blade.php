@extends('layouts.hr.sidebar-header')
@section('content')
<div class="fixed top-4 right-4 z-[9999]">
    <x-notify::notify />
</div>

<div class="w-full p-3 rounded-xl shadow-md">
    <!-- Banner Image -->
    <div class="bg-[url('/public/img/Background.png')] bg-cover bg-center bg-no-repeat min-h-[200px] sm:min-h-[300px] md:min-h-[400px] w-full rounded-lg overflow-hidden"></div>     
          

    <!-- Profile Section -->
    <div class="relative flex flex-col items-center md:flex-row md:items-start mt-[-60px] md:mt-[-100px] px-4">
        <!-- Profile Image & Upload -->
        <div class="relative w-24 h-24 md:w-32 md:h-32">
            <div class="relative group w-full h-full">
                <img id="profile-preview"
                    src="{{ auth()->user()->profile_image ? asset('storage/profile_images/' . auth()->user()->profile_image) : asset('img/default-avatar.png') }}"
                    class="w-full h-full rounded-full object-cover border-4 border-gray-300 shadow-md cursor-pointer">
                <label for="profile_image" 
                    class="absolute inset-0 bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 flex justify-center items-center transition-opacity duration-300 cursor-pointer">
                    <span class="text-white text-xs md:text-sm font-medium">Change Image</span>
                </label>
            </div>
        </div>

        <!-- Form for Image Upload -->
        <form action="{{ route('profile.update-image') }}" method="POST" enctype="multipart/form-data" class="relative mt-2 md:mt-0 md:ml-4">
            @csrf
            <input type="file" name="profile_image" id="profile_image" class="hidden">
            <button id="update-button" type="submit" 
                class="mt-2 bg-green-500 text-white px-3 py-1 text-xs md:text-sm rounded-md hidden hover:bg-green-600 transition">
                Update
            </button>
        </form>
    </div>

    <!-- Profile Header -->
    <div class="px-4 mt-4">
        <div class="flex flex-col md:flex-row justify-between items-center">
            <h2 class="text-xl md:text-2xl font-medium text-gray-900">
                {{ __('Profile') }}
            </h2>
            <a class="text-blue-600 mt-2 md:mt-0" href="{{route('hr.profile.index')}}">
                <i class="fa fa-arrow-left"></i> Go back
            </a>
        </div>
    </div>
</div>

<!-- Personal Information Section -->
<div class="p-3 rounded-xl mt-2 flex flex-col md:flex-row justify-center items-start animate-fade-in">
    <div class="flex-1 p-4">
        <h3 class="text-lg md:text-2xl font-semibold text-gray-700">Personal Information</h3>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Update your account's profile information and email address.") }}
        </p>
        
        <form method="post" action="{{ route('hr-profile.update') }}" class="mt-6 space-y-4">
            @csrf
            @method('patch')

            <div>
                <x-input-label for="last_name" :value="__('Last Name')" />
                <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" :value="old('last_name', $user->last_name)" required autofocus />
                <x-input-error class="mt-2" :messages="$errors->get('last_name')" />
            </div>

            <div>
                <x-input-label for="middle_name" :value="__('Middle Name')" />
                <x-text-input id="middle_name" name="middle_name" type="text" class="mt-1 block w-full" :value="old('middle_name', $user->middle_name)" required />
                <x-input-error class="mt-2" :messages="$errors->get('middle_name')" />
            </div>

            <div>
                <x-input-label for="first_name" :value="__('First Name')" />
                <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" :value="old('first_name', $user->first_name)" required />
                <x-input-error class="mt-2" :messages="$errors->get('first_name')" />
            </div>

            <div class="flex items-center gap-4">
                <x-primary-button>{{ __('Save') }}</x-primary-button>
                @if (session('status') === 'profile-updated')
                    <p class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                @endif
            </div>
        </form>
    </div>

    <div class="flex-1 p-4 flex justify-center">
        <img src="/img/profile.jpg" alt="" class="max-w-full h-auto rounded-lg">
    </div>
</div>

<!-- Email Information Section -->
<div class="p-3 rounded-xl mt-4">
    <div class="flex flex-col md:flex-row items-start">
        <div class="flex-1 p-4">
            <h3 class="text-lg md:text-2xl font-semibold text-gray-700">Email Information</h3>
            <p class="mt-1 text-sm text-gray-600">
                {{ __("Keep your email secure and safe.") }}
            </p>

            <form method="post" action="{{ route('hr-email.update') }}" class="space-y-4">
                @csrf
                @method('patch')

                <div>
                    <x-input-label for="name" :value="__('Name')" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('name')" />
                </div>

                <div>
                    <x-input-label for="email" :value="__('Email')" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required />
                    <x-input-error class="mt-2" :messages="$errors->get('email')" />
                </div>

                <div class="flex items-center gap-4">
                    <x-primary-button>{{ __('Save') }}</x-primary-button>
                    @if (session('status') === 'email-updated')
                        <p class="text-sm text-gray-600">{{ __('Saved.') }}</p>
                    @endif
                </div>
            </form>
        </div>

        <div class="flex-1 p-4 flex justify-center">
            <img src="/img/email.jpg" alt="" class="max-w-full h-auto rounded-lg">
        </div>
    </div>
</div>

@endsection

@notifyCss

<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
