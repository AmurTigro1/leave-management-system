@extends('layouts.hr.sidebar-header')
@section('content')
    @include('admin.CTO.modal.coclog_view', ['cocLogs' => $cocLogs])
    @include('admin.CTO.modal.coclog_create', ['users' => $users])
    @foreach ($cocLogs as $log)
        @include('admin.CTO.modal.coclog_delete', ['log' => $log])
        @include('admin.CTO.modal.coclog_update', ['log' => $log])
    @endforeach

    <div class="fixed top-4 right-4 z-[9999] sm:top-6 sm:right-6">
        <x-notify::notify />
    </div>

    <div class="container max-w-7xl mx-auto px-2 sm:px-4 lg:px-6 py-3 sm:py-5 animate-fade-in">
        @notifyJs
        <div class="bg-white rounded-lg shadow-sm sm:shadow-md overflow-hidden">
            <!-- Header Section -->
            <div class="p-3 sm:p-4 border-b border-gray-200">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                    <h2 class="text-lg font-bold flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd"
                                d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z"
                                clip-rule="evenodd"></path>
                        </svg>
                        COC Logs Management
                    </h2>
                    <button onclick="opencocCreateLogsModal()"
                        class="inline-flex items-center justify-center px-3 py-2 bg-blue-600 text-white text-xs sm:text-sm rounded-md font-medium hover:bg-blue-700 transition">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Add New
                    </button>
                </div>
                <div class="w-full md:w-auto order-0 md:order-none mt-5">
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2" x-data="{ search: '{{ $search ?? '' }}' }">
                        <input type="text" x-model="search" @input.debounce.500ms="fetchResults(search)"
                            placeholder="Search employees..."
                            class="flex-grow border border-gray-300 rounded-md px-3 sm:px-4 py-2 text-sm sm:text-base focus:ring-blue-500 focus:border-blue-500">
                        <button @click="search = ''; fetchResults('')"
                            class="bg-blue-500 text-white px-3 sm:px-4 py-2 rounded-md hover:bg-blue-600 transition text-sm sm:text-base">
                            Clear
                        </button>
                    </div>
                </div>
            </div>

            <!-- Content Section - This will be updated via AJAX -->
            <div id="coclog-content">
                @include('admin.CTO.partials.coclog_table', ['cocLogs' => $cocLogs])
            </div>
        </div>
    </div>
    <script>
        function fetchResults(searchTerm) {
            console.log('RUNNING')
            fetch(`{{ route('coc_logs.hr') }}?search=${searchTerm}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.text())
                .then(html => {
                    document.getElementById('coclog-content').innerHTML = html;
                })
                .catch(error => console.error('Error:', error));
        }
    </script>
@endsection

<style>
    .animate-fade-in {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @media (max-width: 640px) {
        .container {
            padding-left: 0.5rem;
            padding-right: 0.5rem;
        }

        .bg-white.rounded-lg {
            border-radius: 0.5rem;
        }
    }

    /* Loading spinner animation */
    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }

        100% {
            transform: rotate(360deg);
        }
    }

    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>
@notifyCss
