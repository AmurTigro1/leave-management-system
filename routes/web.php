<?php
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('employee.landing');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-image', [EmployeeController::class, 'updateProfileImage'])->name('profile.update-image');
    Route::get('/holidays', [EmployeeController::class, 'holiday'])->name('holiday.calendar');
    Route::get('/leave', [LeaveApplicationController::class, 'index'])->name('leave.index');
    Route::post('/leave', [LeaveApplicationController::class, 'store'])->name('leave.store');
});
//HR Officer Route
Route::middleware(['auth', 'hrMiddleware'])->group(function () {
    Route::get('/hr-dashboard', [LeaveApplicationController::class, 'hrDashboard'])->name('hr.dashboard');
    Route::get('/hr/leave-certification/{leaveId}', [LeaveApplicationController::class, 'showLeaveCertification'])->name('hr.leave_certification');
    Route::post('/leave/{leave}/review', [LeaveApplicationController::class, 'review'])->name('leave.review');
    Route::get('/leave-report/{id}', [LeaveApplicationController::class, 'generateLeaveReport'])->name('leave.report');

});
//Supervisor Routes
Route::middleware(['auth', 'SupervisorMiddleware'])->group(function () {
    Route::get('/supervisor/leaves', [LeaveApplicationController::class, 'supervisorDashboard'])->name('supervisor.dashboard');
    Route::post('/supervisor/{leave}/approve', [LeaveApplicationController::class, 'approve'])->name('supervisor.approve');  // Final approve
    Route::post('/supervisor/reject/{leave}', [LeaveApplicationController::class, 'reject'])->name('supervisor.reject');  // Final reject
});

//Employee Route
Route::middleware(['auth', 'employeeMiddleware'])->group(function () {
    Route::get('/dashboard', [EmployeeController::class, 'index'])->name('employee.dashboard');
    Route::get('/make-request', [EmployeeController::class, 'makeRequest'])->name('employee.make_request');
    Route::get('/my-requests', [EmployeeController::class, 'showRequests'])->name('employee.leave_request');
    Route::get('/details/{id}', [EmployeeController::class, 'show'])->name('employee.leave_show');
    Route::post('/request-leave', [EmployeeController::class, 'store'])->name('request.leave');
    Route::get('/profile', [EmployeeController::class, 'profile'])->name('employee.profile');
    Route::get('/leave/download/{id}', [EmployeeController::class, 'downloadPdf'])->name('leave.downloadPdf');


});

//Admin Route
Route::middleware(['auth', 'adminMiddleware'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/requests', [AdminController::class, 'requests'])->name('admin.requests');
    Route::post('/admin/leave/update/{leave}', [AdminController::class, 'approve'])->name('admin.leave.update');
});

Route::get('/leave-calendar', action: [EmployeeController::class, 'showCalendar'])->name('leave.calendar');
Route::get('/api/leaves', [EmployeeController::class, 'getLeaves']); 

// Route::get('/api/leaves', function () {
//     return response()->json(
//         Leave::where('status', 'approved') // Only show approved leaves
//              ->get(['id', 'start_date as start', 'end_date as end', 'reason as title'])
//     );
// });
require __DIR__.'/auth.php';