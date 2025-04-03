<div class="p-6 bg-white rounded-lg max-w-lg mx-auto">
    <!-- Modal Header -->
    <div class="flex justify-between items-center pb-3">
        <h2 class="text-lg font-semibold text-gray-800">Employee List</h2>
        <button onclick="document.getElementById('modal').classList.add('hidden')" class="text-gray-500 hover:text-gray-700">
            ✖
        </button>
    </div>

    <!-- User List -->
    <ul class="mt-4 space-y-3">
        @foreach($employees as $employee)
            <li class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg">
                <!-- Profile Image -->
                @php
                    $profileImage = null;

                    if ($employee->profile_image) {
                        $imagePath1 = 'storage/profile_images/' . $employee->profile_image;
                        $imagePath2 = 'storage/profile_pictures/' . $employee->profile_image;

                        if (file_exists(public_path($imagePath1))) {
                            $profileImage = asset($imagePath1);
                        } elseif (file_exists(public_path($imagePath2))) {
                            $profileImage = asset($imagePath2);
                        }
                    }
                @endphp

                <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" 
                    class="w-10 h-10 sm:w-12 sm:h-12 rounded-full object-cover flex-shrink-0" 
                    alt="{{ $employee->name }}">

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