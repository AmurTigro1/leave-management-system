<div class="p-6 bg-white rounded-lg max-w-lg mx-auto">
    <!-- Modal Header -->
    <div class="flex justify-between items-center pb-3">
        <h2 class="text-lg font-semibold text-gray-800">User List</h2>
        <button onclick="document.getElementById('modal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
            ✖
        </button>
    </div>

    <!-- User List -->
    <ul class="mt-4 space-y-3">
        @foreach($employees as $employee)
            <li class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <!-- Profile Image -->
                <img src="{{ $employee->profile_image ? asset('storage/profile_images/' . $employee->profile_image) : 'https://cdn-icons-png.freepik.com/256/12533/12533276.png?ga=GA1.1.1609491871.1738904251&semt=ais_hybrid' }}" alt="Profile" 
                     class="w-12 h-12 rounded-full border border-gray-300 object-cover">

                <!-- User Info -->
                <div>
                    <h3 class="text-sm font-semibold text-gray-800">{{ $employee->last_name }} {{ $employee->first_name }} </h3>
                    <p class="text-xs text-gray-500">{{ $employee->email }}</p>
                    <p class="text-xs text-gray-500">absences: {{ $employee->total_absences }} day(s)</p>
                </div>
            </li>
        @endforeach
    </ul>

    <!-- Close Button -->
    <div class="mt-4 text-center">
        <button onclick="document.getElementById('modal').classList.add('hidden')" 
                class="px-4 py-2 bg-gray-800 text-white rounded-lg text-sm font-semibold hover:bg-gray-900">
            Close
        </button>
    </div>
</div>

<script>
    document.getElementById('modal').classList.remove('hidden');
</script>
