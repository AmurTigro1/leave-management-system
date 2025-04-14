@extends('layouts.supervisor.sidebar-header')

@section('content')
<h2 class="text-xl font-bold mb-4">Recent Request recommended by HR</h2>

<!-- Desktop/Tablet View -->
<div class="hidden sm:flex animate-fade-in justify-between items-start gap-4">

  {{-- Flash Messages --}}
  @if(session('success'))
  <div id="success-alert" class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-3 rounded-md">
      <strong>Success!</strong> {{ session('success') }}
  </div>
  @endif

  @if(session('error'))
  <div id="error-alert" class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-3 rounded-md">
      <strong>Error!</strong> {{ session('error') }}
  </div>
  @endif

  <!-- Leave Applications -->
  <div class="w-full">
    @if($leaveApplications->isEmpty())
      <div class="text-gray-600 mt-4">No Leave Applications found.</div>
    @else
      <h1 class="font-semibold text-lg mb-3">Leave Applications</h1>
      <div class="gap-6 w-full">
        @foreach ($leaveApplications as $leave)
        <div class="bg-gray-100 p-4 rounded-lg shadow-md text-sm mb-4">
          <div class="flex justify-between items-center border-b pb-3 mb-3">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-300">
                @if ($leave->user->profile_image)
                                    @php
                                        $profileImage = null;

                                        if ($leave->user->profile_image) {
                                            $imagePath1 = 'storage/profile_images/' . $leave->user->profile_image;
                                            $imagePath2 = 'storage/profile_pictures/' . $leave->user->profile_image;

                                            if (file_exists(public_path($imagePath1))) {
                                                $profileImage = asset($imagePath1);
                                            } elseif (file_exists(public_path($imagePath2))) {
                                                $profileImage = asset($imagePath2);
                                            }
                                        }
                                    @endphp

                                    <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" 
                                        class="w-12 h-12 rounded-full object-cover" 
                                        alt="{{ $leave->user->name }}">
                                    @else
                                        <img src="{{ asset('img/default-avatar.png') }}" 
                                            alt="" 
                                            class="w-12 h-12 rounded-full object-cover">
                                    @endif
              </div>
              <div>
                <p class="text-md font-semibold text-gray-800">{{ $leave->user->first_name }} {{ $leave->user->last_name }}</p>
                <p class="text-xs text-gray-500">{{ $leave->leave_type }} - {{ $leave->days_applied }} Days</p>
              </div>
            </div>
            <p class="text-gray-500 text-sm">
              <span class="font-semibold">{{ \Carbon\Carbon::parse($leave->start_date)->format('F j, Y') }}</span> - 
              <span class="font-semibold">{{ \Carbon\Carbon::parse($leave->end_date)->format('F j, Y') }}</span>
            </p>
          </div>
          <div class="mb-4 flex justify-between items-center">
            <p class="text-gray-600"><strong>Status:</strong> 
              <span class="px-2 py-1 text-white text-xs rounded 
                {{ $leave->status === 'pending' ? 'bg-yellow-500' : ($leave->status === 'approved' ? 'bg-green-500' : 'bg-red-500') }}">
                {{ ucfirst($leave->status) }}
              </span>
            </p>
            <button onclick="openPdfModal('{{ route('supervisor.leave.viewPdf', $leave->id) }}')" 
              class="cursor-pointer text-center p-3 text-blue-500 font-semibold transition">
              Click to View PDF
            </button>
          </div>
        </div>
        @endforeach
      </div>
      <div class="mt-4">
        {{ $leaveApplications->links('vendor.pagination.tailwind') }}
      </div>
    @endif
  </div>

  <!-- CTO Applications -->
  <div class="w-full">
    @if($ctoApplications->isEmpty())
      <div class="text-gray-600">No CTO Applications found.</div>
    @else
      <h1 class="font-semibold text-lg mb-3">CTO Applications</h1>
      <div class="gap-6 w-full">
        @foreach ($ctoApplications as $cto)
        <div class="bg-gray-100 p-4 rounded-lg shadow-md text-sm mb-4">
          <div class="flex justify-between items-center border-b pb-3 mb-3">
            <div class="flex items-center gap-3">
              <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-300">
                @if ($cto->user->profile_image)
                                    @php
                                        $profileImage = null;

                                        if ($cto->user->profile_image) {
                                            $imagePath1 = 'storage/profile_images/' . $cto->user->profile_image;
                                            $imagePath2 = 'storage/profile_pictures/' . $cto->user->profile_image;

                                            if (file_exists(public_path($imagePath1))) {
                                                $profileImage = asset($imagePath1);
                                            } elseif (file_exists(public_path($imagePath2))) {
                                                $profileImage = asset($imagePath2);
                                            }
                                        }
                                    @endphp

                                    <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" 
                                        class="w-12 h-12 rounded-full object-cover" 
                                        alt="{{ $cto->user->name }}">
                                    @else
                                        <img src="{{ asset('img/default-avatar.png') }}" 
                                            alt="" 
                                            class="w-12 h-12 rounded-full object-cover">
                                    @endif
              </div>
              <div>
                <p class="text-md font-semibold text-gray-800">{{ $cto->user->first_name }} {{ $cto->user->last_name }}</p>
                <p class="text-xs text-gray-500">{{ $cto->cto_type }} - {{ $cto->working_hours_applied }} Hours</p>
              </div>
            </div>
            <p class="text-gray-500 text-sm">
              <span class="font-semibold">{{ \Carbon\Carbon::parse($cto->inclusive_date_start)->format('F j, Y') }}</span> - 
              <span class="font-semibold">{{ \Carbon\Carbon::parse($cto->inclusive_date_end)->format('F j, Y') }}</span>
            </p>
          </div>
          <div class="mb-4 flex justify-between items-center">
            <p class="text-gray-600"><strong>Status:</strong> 
              <span class="px-2 py-1 text-white text-xs rounded 
                {{ $cto->status === 'pending' ? 'bg-yellow-500' : ($cto->status === 'approved' ? 'bg-green-500' : 'bg-red-500') }}">
                {{ ucfirst($cto->status) }}
              </span>
            </p>
            <button onclick="openPdfModal('{{ route('supervisor.cto.viewPdf', $cto->id) }}')" 
              class="cursor-pointer text-center p-3 text-blue-500 font-semibold transition">
              Click to View PDF
            </button>
          </div>
        </div>
        @endforeach
      </div>
      <div class="mt-4">
        {{ $ctoApplications->links('vendor.pagination.tailwind') }}
      </div>
    @endif
  </div>
</div>

<!-- Mobile View -->
<div class="sm:hidden p-4">
  <h2 class="text-lg font-bold mb-4">Recent Requests (Mobile View)</h2>

  {{-- Leave Applications --}}
  @if($leaveApplications->isNotEmpty())
    <h3 class="font-semibold text-md mb-2">Leave Applications</h3>
    @foreach ($leaveApplications as $leave)
      <div class="bg-gray-100 p-3 rounded-lg shadow text-sm mb-3">
        <div class="flex items-center gap-2 mb-2">
          @if ($leave->user->profile_image)
                                    @php
                                        $profileImage = null;

                                        if ($leave->user->profile_image) {
                                            $imagePath1 = 'storage/profile_images/' . $leave->user->profile_image;
                                            $imagePath2 = 'storage/profile_pictures/' . $leave->user->profile_image;

                                            if (file_exists(public_path($imagePath1))) {
                                                $profileImage = asset($imagePath1);
                                            } elseif (file_exists(public_path($imagePath2))) {
                                                $profileImage = asset($imagePath2);
                                            }
                                        }
                                    @endphp

                                    <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" 
                                        class="w-10 h-10 rounded-full object-cover" 
                                        alt="{{ $leave->user->name }}">
                                    @else
                                        <img src="{{ asset('img/default-avatar.png') }}" 
                                            alt="" 
                                            class="w-10 h-10 rounded-full object-cover">
                                    @endif
          <div>
            <p class="font-semibold">{{ $leave->user->first_name }} {{ $leave->user->last_name }}</p>
            <p class="text-xs text-gray-500">{{ $leave->leave_type }} ({{ $leave->days_applied }} Days)</p>
          </div>
        </div>
        <p class="text-xs text-gray-500 mb-1"><strong>Dates:</strong> {{ \Carbon\Carbon::parse($leave->start_date)->format('M j') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('M j') }}</p>
        <p class="text-xs text-gray-500 mb-2"><strong>Status:</strong> 
          <span class="px-2 py-0.5 text-white rounded 
            {{ $leave->status === 'pending' ? 'bg-yellow-500' : ($leave->status === 'approved' ? 'bg-green-500' : 'bg-red-500') }}">
            {{ ucfirst($leave->status) }}
          </span>
        </p>
        <button onclick="openPdfModal('{{ route('supervisor.leave.viewPdf', $leave->id) }}')" class="text-blue-500 text-xs underline">View PDF</button>
      </div>
    @endforeach
  @endif

  {{-- CTO Applications --}}
  @if($ctoApplications->isNotEmpty())
    <h3 class="font-semibold text-md mt-4 mb-2">CTO Applications</h3>
    @foreach ($ctoApplications as $cto)
      <div class="bg-gray-100 p-3 rounded-lg shadow text-sm mb-3">
        <div class="flex items-center gap-2 mb-2">
          @if ($cto->user->profile_image)
                                    @php
                                        $profileImage = null;

                                        if ($cto->user->profile_image) {
                                            $imagePath1 = 'storage/profile_images/' . $cto->user->profile_image;
                                            $imagePath2 = 'storage/profile_pictures/' . $cto->user->profile_image;

                                            if (file_exists(public_path($imagePath1))) {
                                                $profileImage = asset($imagePath1);
                                            } elseif (file_exists(public_path($imagePath2))) {
                                                $profileImage = asset($imagePath2);
                                            }
                                        }
                                    @endphp

                                    <img src="{{ $profileImage ?? asset('img/default-avatar.png') }}" 
                                        class="w-10 h-10 rounded-full object-cover" 
                                        alt="{{ $cto->user->name }}">
                                    @else
                                        <img src="{{ asset('img/default-avatar.png') }}" 
                                            alt="" 
                                            class="w-10 h-10 rounded-full object-cover">
                                    @endif
          <div>
            <p class="font-semibold">{{ $cto->user->first_name }} {{ $cto->user->last_name }}</p>
            <p class="text-xs text-gray-500">{{ $cto->cto_type }} ({{ $cto->working_hours_applied }} Hours)</p>
          </div>
        </div>
        <p class="text-xs text-gray-500 mb-1"><strong>Dates:</strong> {{ \Carbon\Carbon::parse($cto->inclusive_date_start)->format('M j') }} - {{ \Carbon\Carbon::parse($cto->inclusive_date_end)->format('M j') }}</p>
        <p class="text-xs text-gray-500 mb-2"><strong>Status:</strong> 
          <span class="px-2 py-0.5 text-white rounded 
            {{ $cto->status === 'pending' ? 'bg-yellow-500' : ($cto->status === 'approved' ? 'bg-green-500' : 'bg-red-500') }}">
            {{ ucfirst($cto->status) }}
          </span>
        </p>
        <button onclick="openPdfModal('{{ route('supervisor.cto.viewPdf', $cto->id) }}')" class="text-blue-500 text-xs underline">View PDF</button>
      </div>
    @endforeach
  @endif
</div>

<!-- PDF Modal -->
<div id="pdfModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center hidden z-[9999]" onclick="closePdfModal(event)">
  <div id="pdfContent" class="bg-white w-3/4 h-3/4 rounded-lg shadow-lg relative" onclick="event.stopPropagation()">
    <iframe class="mt-[-60px]" id="pdfFrame" src="" width="100%" height="120%" frameborder="0"></iframe>
  </div>
</div>

<script>
  function openPdfModal(pdfUrl) {
    document.getElementById('pdfFrame').src = pdfUrl;
    document.getElementById('pdfModal').classList.remove('hidden');
    document.getElementById('pdfModal').classList.add('flex');
  }

  function closePdfModal(event) {
    if (event.target.id === 'pdfModal') {
      document.getElementById('pdfFrame').src = "";
      document.getElementById('pdfModal').classList.add('hidden');
    }
  }

  setTimeout(() => {
    document.getElementById('success-alert')?.remove();
    document.getElementById('error-alert')?.remove();
  }, 4000);
</script>

@endsection
