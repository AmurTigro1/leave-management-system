@extends('layouts.admin.sidebar-header')
@section('content')
<a class="text-blue-600" href="/supervisor-profile"><i class="fa fa-arrow-left" aria-hidden="true"></i> Go back</a>
    <div class="py-3">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('supervisor.profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('supervisor.profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
@endsection

