<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CocLog;
use App\Models\User;

class CocLogController extends Controller
{
    public function index()
    {
        $cocLogs = CocLog::with('user')->latest()->paginate(10);
        $users = User::orderBy('last_name', 'asc')->get();

        return view('hr.CTO.coclog', compact('cocLogs', 'users'));
    }

    public function create()
    {
        $users = User::all();
        return view('hr.coc_logs.create', compact('users'));
    }

    public function showCocLogs($id)
    {
        $coc = CocLog::find($id);
        return view('hr.CTO.show_coc', compact('coc'));
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

        CocLog::create($validated);

        notify()->success('COC Log created successfully.');
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
        $cocLog->delete();
        notify()->success('COC Log deleted successfully.');
        return redirect()->back();
    }
}
