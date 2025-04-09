<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OvertimeRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User;
use App\Models\Holiday;
use PDF;

class OvertimeRequestController extends Controller
{
    public function index()
    {
        $overtimereq = OvertimeRequest::where('user_id', auth()->id())->get();

        $appliedDates = OvertimeRequest::where('user_id', auth()->id())
                    ->get('inclusive_dates');
        $holidays = Holiday::select('date')->get();
        
        return view('CTO.overtime_request', compact('overtimereq', 'appliedDates', 'holidays'));
    }

    public function dashboard()
    {
        $user_id = Auth::id();

        $overtimes = OvertimeRequest::where('user_id', $user_id)->latest()->get();

        $totalAppliedHours = OvertimeRequest::where('user_id', $user_id)->sum('working_hours_applied');
        $totalEarnedHours = OvertimeRequest::where('user_id', $user_id)->sum('earned_hours');

        $pendingRequests = OvertimeRequest::where('user_id', $user_id)->where('earned_hours', 0)->count();

        return view('CTO.dashboard', compact('overtimes', 'totalAppliedHours', 'totalEarnedHours', 'pendingRequests'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $overtimeBalance = $user->overtime_balance;

        $ctoHoursMap = [
            'halfday_morning' => 4,
            'halfday_afternoon' => 4,
            'wholeday' => 8,
        ];

        if ($request->cto_type !== 'none') {
            $request->merge(['working_hours_applied' => $ctoHoursMap[$request->cto_type]]);
        }

        $request->validate([
            'inclusive_dates' => 'required|string',
            'cto_type' => 'nullable|in:none,halfday_morning,halfday_afternoon,wholeday',
            'working_hours_applied' => [
                'required_without:cto_type',
                'integer',
                'min:4',
                'multiple_of:4',
                function ($attribute, $value, $fail) {
                    if ($value % 4 !== 0) {
                        $fail("The $attribute must be a multiple of 4.");
                    }
                },
                function ($attribute, $value, $fail) use ($overtimeBalance) {
                    if ($value > $overtimeBalance) {
                        $fail("You cannot apply more than your available COC balance.");
                    }
                }
            ],
        ]);

        $datesArray = explode(', ', $request->inclusive_dates);
        foreach ($datesArray as $date) {
            if (!strtotime($date)) {
                return back()->withErrors(['inclusive_dates' => 'Invalid date format detected']);
            }
        }

        OvertimeRequest::create([
            'user_id' => auth()->id(),
            'date_filed' => now(),
            'working_hours_applied' => $request->working_hours_applied,
            'inclusive_dates' => $request->inclusive_dates,
            'admin_status' => 'pending', 
            'hr_status' => 'pending', 
        ]);
        
        $user->decrement('overtime_balance', $request->working_hours_applied);

        $remaining = $request->working_hours_applied;

        $logs = $user->cocLogs()
            ->where('coc_earned', '>', 0)
            ->orderBy('expires_at', 'asc')
            ->get();
        
        foreach ($logs as $log) {
            if ($remaining <= 0) break;
        
            if ($log->coc_earned >= $remaining) {
                $log->decrement('coc_earned', $remaining);
                $remaining = 0;
            } else {
                $remaining -= $log->coc_earned;
                $log->decrement('coc_earned', $log->coc_earned);
            }
        }
        
        notify()->success('Overtime request submitted successfully! Pending admin review.');
        return redirect()->back();
    }

    public function list()
    {
        $overtimereq = OvertimeRequest::where('user_id', Auth::id())->latest()->paginate(10);
        $overtime = OvertimeRequest::where('user_id', Auth::id())->first();
        return view('CTO.overtime_list', compact('overtimereq', 'overtime'));
    }

    public function show($id) {
        $overtime = OvertimeRequest::findOrFail($id);
    
        return view('CTO.overtime_show', compact('overtime'));
    }

    public function viewPdf($id)
    {
        $overtime = OvertimeRequest::findOrFail($id);

        $supervisor = User::where('role', 'supervisor')->first();
        $hr = User::where('role', 'hr')->first();
        
        $pdf = PDF::loadView('pdf.overtime_details', compact('overtime', 'supervisor', 'hr'));
        
        return $pdf->stream('overtime_request_' . $overtime->id . '.pdf');
    }

    public function profile() {
        $user = Auth::user();
    
        return view('CTO.profile.index', [
            'user' => $user,
        ]);
    }

    public function edit(Request $request): View
    {
        return view('CTO.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('cto.profile.edit')->with('status', 'profile-updated');
    }

    public function editOvertime($id) {
        $overtime = OvertimeRequest::findOrFail($id);
        return view('CTO.edit', compact('id', 'overtime'));
    }

    public function updateOvertime(Request $request, $id)
    {
        $request->validate([
            'inclusive_dates' => 'required|string',
            'working_hours_applied' => 'required|integer|min:0|multiple_of:4',
        ]);

        $overtime = OvertimeRequest::findOrFail($id);
        $user = Auth::user();

        $oldHours = $overtime->working_hours_applied;
        $newHours = $request->working_hours_applied;
        $diff = $newHours - $oldHours;

        if ($diff > 0 && $diff > $user->overtime_balance) {
            notify()->error('You do not have enough available COC balance to increase the hours.');
            return redirect()->back()->withInput();
        }

        if ($diff !== 0) {
            if ($diff > 0) {
                $user->decrement('overtime_balance', $diff);
            } else {
                $user->increment('overtime_balance', abs($diff));
            }
        }

        $overtime->update([
            'inclusive_dates' => $request->inclusive_dates,
            'working_hours_applied' => $newHours,
        ]);

        notify()->success('Overtime request updated successfully.');
        return redirect()->back();
    }

    public function deleteOvertime($id)
    {
        $request = OvertimeRequest::findOrFail($id);
    
        $user = $request->user; 
        $user->overtime_balance += $request->working_hours_applied; 
        $user->save();
    
        $request->delete();
    
        return redirect()->back()->with('success', 'CTO request deleted and balance restored.');
    } 
}
