<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HRSupervisor;
use Illuminate\Support\Facades\Storage;

class HRSupervisorController extends Controller
{
    public function update(Request $request, $id)
    {
        $supervisor = HRSupervisor::findOrFail($id);
    
        $request->validate([
            'supervisor_name' => 'required|string|max:255',
            'hr_name' => 'required|string|max:255',
            'supervisor_signature' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
            'hr_signature' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);
    
        // Update text fields
        $supervisor->supervisor_name = $request->supervisor_name;
        $supervisor->hr_name = $request->hr_name;
    
        // Handle supervisor signature upload
        if ($request->hasFile('supervisor_signature')) {
            if ($supervisor->supervisor_signature) {
                Storage::disk('public')->delete($supervisor->supervisor_signature);
            }
            $supervisor->supervisor_signature = $request->file('supervisor_signature')->store('signatures', 'public');
        }
    
        // Handle HR signature upload
        if ($request->hasFile('hr_signature')) {
            if ($supervisor->hr_signature) {
                Storage::disk('public')->delete($supervisor->hr_signature);
            }
            $supervisor->hr_signature = $request->file('hr_signature')->store('signatures', 'public');
        }
    
        $supervisor->save();

        notify()->success('HR/Supervisor details updated successfully!');
    
        return back()->with('success', '');
    }
    

}
