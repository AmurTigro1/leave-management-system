@extends('layouts.admin.sidebar-header')

@section('content')
<div class="max-w-7xl mx-auto space-y-6 animate-fade-in">
    <div class="container mx-auto px-4 py-2">
        <!-- Page Title -->
      <h1 class="text-2xl font-bold text-gray-700">Number of Request to be verified</h1>
    </div>    
</div>

<style>
    .animate-fade-in {
        animation: fadeIn 0.8s ease-in-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
</style>
@endsection
