<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CocLog;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Notifications\CocLogCreatedNotification;

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

        $user = User::findOrFail($validated['user_id']);
        $cocLog = $user->cocLogs()->create($validated);
        $user->increment('overtime_balance', $validated['coc_earned']);

        \Log::info('Sending notification to user:', ['user_id' => $user->id]);
        $user->notifyNow(new CocLogCreatedNotification($cocLog));

        notify()->success('COC Log created successfully.');
        return redirect()->back();
    }

    public function update(Request $request, CocLog $cocLog)
    {
        $originalCoc = $cocLog->coc_earned;
        
        $cocLog->update($request->all());
        
        $difference = $cocLog->coc_earned - $originalCoc;
        
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
            $user = $cocLog->user;

            $cocLog->delete();

            $user->decrement('overtime_balance', $cocEarned);

            // $user->notify(new CocLogCreatedNotification($cocLog, "Your COC Log entry has been deleted, and your overtime balance has been adjusted."));
        });

        notify()->success('COC Log deleted successfully and overtime balance adjusted.');
        return redirect()->back();
    }
}
