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
        $cocLogs = CocLog::where('is_expired', false)
            ->join('users', 'coc_logs.user_id', '=', 'users.id') 
            ->orderBy('coc_logs.expires_at', 'asc') 
            ->with(['user', 'creator'])
            ->select('coc_logs.*')
            ->paginate(10);

        $users = User::get();

        return view('hr.CTO.coclog', compact('cocLogs', 'users'));
    }

    public function indexAdmin()
    {
        $cocLogs = CocLog::where('is_expired', false)
            ->join('users', 'coc_logs.user_id', '=', 'users.id') 
            ->orderBy('coc_logs.expires_at', 'asc') 
            ->with(['user', 'creator'])
            ->select('coc_logs.*')
            ->paginate(10);
    
        $users = User::get();
    
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

            // Send notification to the employee
            $user->notify(new CocLogCreatedNotification($cocLog));
        });

        notify()->success('COC Log created successfully and notification sent.');
        return redirect()->back();
    }

    public function update(Request $request, CocLog $cocLog)
    {
        // Get the original COC earned value before update
        $originalCoc = $cocLog->coc_earned;
        
        // Update the COC log with new values
        $cocLog->update($request->all());
        
        // Calculate the difference in COC earned
        $difference = $cocLog->coc_earned - $originalCoc;
        
        // Adjust the user's overtime balance
        if ($difference != 0) {
            $cocLog->user()->increment('overtime_balance', $difference);
        }
        
        notify()->success('COC Log for ' . $cocLog->user->last_name . ' updated successfully. ' . 
                        ($difference != 0 ? 'Balance adjusted by ' . $difference . ' hours.' : ''));
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
