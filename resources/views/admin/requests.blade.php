@extends('layouts.admin.sidebar-header')

@section('content')
<div class="max-w-6xl bg-white rounded animate-fade-in">
    <h2 class="text-2xl font-bold text-gray-700 mb-6 flex items-center gap-2">
        <i class="lucide lucide-file-text"></i> Review Leave Applications
    </h2>

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