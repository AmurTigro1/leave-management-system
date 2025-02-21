<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $leaves = Leave::with('user')->latest()->get();
        return view('admin.dashboard', compact('leaves'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate(['status' => 'required|string|in:Approved,Rejected']);

        $leave = Leave::findOrFail($id);
        $leave->status = $request->status;
        $leave->save();

        return back()->with('success', "Leave request has been {$request->status}!");
    }
}
