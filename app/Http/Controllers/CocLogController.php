<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CocLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class CocLogController extends Controller
{
    public function indexHR()
    {
        $cocLogs = CocLog::with('user')->latest()->paginate(10);
        $users = User::orderBy('last_name', 'asc')->get();

        return view('hr.CTO.coclog', compact('cocLogs', 'users'));
    }

    public function indexAdmin()
    {
        $cocLogs = CocLog::with('user')->latest()->paginate(10);
        $users = User::orderBy('last_name', 'asc')->get();

        return view('admin.CTO.coclog', compact('cocLogs', 'users'));
    }

    public function showHRCocLogs($id)
    {
        $coc = CocLog::find($id);
        return view('hr.CTO.show_coc', compact('coc'));
    }

    public function showAdminCocLogs($id)
    {
        $coc = CocLog::find($id);
        return view('admin.CTO.show_coc', compact('coc'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'activity_name' => 'required|string|max:255',
            'activity_date' => 'required|string',
            'coc_earned' => 'required|integer|min:0',
            'issuance' => 'required|string|max:255',
        ]);

        $validated['created_by'] = auth()->id();

        DB::transaction(function () use ($validated) {
            // Create the COC log
            $cocLog = CocLog::create($validated);
            
            // Update the user's overtime balance
            $user = User::find($validated['user_id']);
            $user->increment('overtime_balance', $validated['coc_earned']);
        });

        notify()->success('COC Log created successfully and overtime balance updated.');
        return redirect()->back();
    }

    public function edit(CocLog $cocLog)
    {
        $users = User::all();
        return view('hr.coc_logs.edit', compact('cocLog', 'users'));
    }

    public function update(Request $request, CocLog $cocLog)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'activity_name' => 'required|string|max:255',
            'activity_date' => 'required|date',
            'coc_earned' => 'required|integer|min:0',
            'issuance' => 'required|string|max:255',
        ]);

        $cocLog->update($validated);
        notify()->success('COC Log updated successfully.');
        return redirect()->back();
    }

    public function destroy(CocLog $cocLog)
    {
        DB::transaction(function () use ($cocLog) {
            $cocEarned = $cocLog->coc_earned;
            $userId = $cocLog->user_id;
            
            // Delete the log
            $cocLog->delete();
            
            User::where('id', $userId)
                ->decrement('overtime_balance', $cocEarned);
        });
        
        notify()->success('COC Log deleted successfully and overtime balance adjusted.');
        return redirect()->back();
    }
}
