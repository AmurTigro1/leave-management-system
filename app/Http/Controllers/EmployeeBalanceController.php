<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Container\Attributes\Log;
use Illuminate\Http\Request;

class EmployeeBalanceController extends Controller
{
    public function index(Request $request)
    {
        $totalEmployees = User::count();
        $search = $request->input('search');
        $query = User::query();

        // Apply search filter
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // If it's an AJAX request, return a partial view
        if ($request->ajax()) {
            $employees = $query->paginate(10)->withQueryString();
            return view('admin.partials.employee-balances', compact('employees'))->render();
        }

        // Get employees with pagination
        $employees = $query->paginate(10)->withQueryString();

        $departments = User::select('department')
        ->whereNotNull('department')
        ->distinct()
        ->orderBy('department')  // Sorts alphabetically in ascending order
        ->pluck('department');


        // Return the full view with data
        return view('admin.employee-balances', compact('employees', 'departments', 'search', 'totalEmployees'));
    }



    public function update(Request $request, User $user)
    {

        $validated = $request->validate([
            'vacation_leave_balance' => 'required|numeric|min:0',
            'mandatory_leave_balance' => 'required|numeric|min:0',
            'sick_leave_balance' => 'required|numeric|min:0',
            'maternity_leave' => 'required|integer|min:0',
            'paternity_leave' => 'required|integer|min:0',
            'solo_parent_leave' => 'required|integer|min:0',
            'study_leave' => 'required|integer|min:0',
            'vawc_leave' => 'required|integer|min:0',
            'rehabilitation_leave' => 'required|integer|min:0',
            'special_leave_benefit' => 'required|integer|min:0',
            'special_privilege_leave' => 'required|integer|min:0',
            'special_emergency_leave' => 'required|integer|min:0',
            'wellness_leave_balance' => 'required|numeric|min:0',
            // 'overtime_balance' => 'required|integer|min:0',
        ]);

        $validated['certification_leave'] = now();

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Employee balances updated successfully.',
            'data' => $user->fresh()
        ]);
    }

    //HR
    public function indexHr(Request $request)
    {
        $totalEmployees = User::count();
        $search = $request->input('search');
        $query = User::query();

        // Apply search filter
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('position', 'like', "%{$search}%");
            });
        }

        // If it's an AJAX request, return a partial view
        if ($request->ajax()) {
            $employees = $query->paginate(10)->withQueryString();
            return view('hr.partials.employee-balances', compact('employees'))->render();
        }

        // Get employees with pagination
        $employees = $query->paginate(10)->withQueryString();

        $departments = User::select('department')
        ->whereNotNull('department')
        ->distinct()
        ->orderBy('department')  // Sorts alphabetically in ascending order
        ->pluck('department');


        // Return the full view with data
        return view('hr.employee-balances', compact('employees', 'departments', 'search', 'totalEmployees'));
    }



    public function updateHr(Request $request, User $user)
    {
        $validated = $request->validate([
            'vacation_leave_balance' => 'required|numeric|min:0',
            'mandatory_leave_balance' => 'required|numeric|min:0',
            'sick_leave_balance' => 'required|numeric|min:0',
            'maternity_leave' => 'required|integer|min:0',
            'paternity_leave' => 'required|integer|min:0',
            'solo_parent_leave' => 'required|integer|min:0',
            'study_leave' => 'required|integer|min:0',
            'vawc_leave' => 'required|integer|min:0',
            'rehabilitation_leave' => 'required|integer|min:0',
            'special_leave_benefit' => 'required|integer|min:0',
            'special_privilege_leave' => 'required|integer|min:0',
            'special_emergency_leave' => 'required|integer|min:0',
            // 'overtime_balance' => 'required|integer|min:0',
        ]);

        $validated['certification_leave'] = now();

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Employee balances updated successfully.',
            'data' => $user->fresh()
        ]);
    }
}
