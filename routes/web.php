<?php
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupervisorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('employee.landing');
});

//Login Route
Route::get('/cto_login', function (){
    return view('main_resources.logins.cto_login');
});
Route::get('/lms_login', function (){
    return view('main_resources.logins.lms_login');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-image', [EmployeeController::class, 'updateProfileImage'])->name('profile.update-image');
    Route::get('/holidays', [EmployeeController::class, 'holiday'])->name('holiday.calendar');
    Route::get('/leave', [LeaveApplicationController::class, 'index'])->name('leave.index');
    Route::post('/leave', [LeaveApplicationController::class, 'store'])->name('leave.store');
});

//Supervisor Routes
Route::middleware(['auth', 'SupervisorMiddleware'])->group(function () {
    Route::get('/supervisor/dashboard', [SupervisorController::class, 'index'])->name('supervisor.dashboard');
    Route::get('/supervisor/requests', [SupervisorController::class, 'requests'])->name('supervisor.requests');
    Route::post('/supervisor/{leave}/approve', [SupervisorController::class, 'approve'])->name('supervisor.approve');
    Route::post('/supervisor/reject/{leave}', [SupervisorController::class, 'reject'])->name('supervisor.reject');
    // Route::get('/supervisor/requests', [SupervisorController::class, 'requests'])->name('supervisor.requests');
});

//HR Officer Route
Route::middleware(['auth', 'hrMiddleware'])->group(function () {
    Route::get('/hr-dashboard', [LeaveApplicationController::class, 'hrDashboard'])->name('hr.dashboard');
    Route::get('/hr/leave-certification/{leaveId}', [LeaveApplicationController::class, 'showLeaveCertification'])->name('hr.leave_certification');
    Route::post('/leave/{leave}/review', [LeaveApplicationController::class, 'review'])->name('leave.review');
    Route::get('/leave-report/{id}', [LeaveApplicationController::class, 'generateLeaveReport'])->name('leave.report');

});

//Employee Route
Route::middleware(['auth', 'employeeMiddleware'])->group(function () {
    Route::get('/lms/dashboard', [EmployeeController::class, 'indexLMS'])->name('lms.dashboard');
    Route::get('/cto/dashboard', [EmployeeController::class, 'indexCTO'])->name('cto.dashboard');

    Route::get('/make-request', [EmployeeController::class, 'makeRequest'])->name('employee.make_request');
    Route::get('/my-requests', [EmployeeController::class, 'showRequests'])->name('employee.leave_request');
    Route::get('/my-requests/edit/{id}', [EmployeeController::class, 'editLeave'])->name('employee.leave_edit');
    Route::put('/my-requests/update/{id}', [EmployeeController::class, 'updateLeave'])->name('employee.leave_update');
    Route::delete('/my-requests/delete/{id}', [EmployeeController::class, 'deleteLeave'])->name('employee.leave_delete');
    Route::get('/details/{id}', [EmployeeController::class, 'show'])->name('employee.leave_show');
    Route::post('/request-leave', [EmployeeController::class, 'store'])->name('request.leave');
    Route::get('/profile', [EmployeeController::class, 'profile'])->name('employee.profile');
    Route::get('/leave/download/{id}', [EmployeeController::class, 'downloadPdf'])->name('leave.downloadPdf');


});

//Admin Route
// Route::middleware(['auth', 'SupervisorMiddleware'])->group(function () {
//     Route::get('/supervisor/dashboard', [AdminController::class, 'index'])->name('supervisor.dashboard');
//     Route::get('/supervisor/requests', [AdminController::class, 'requests'])->name('supervisor.requests');
//     Route::post('/supervisor/leave/update/{leave}', [AdminController::class, 'approve'])->name('supervisor.leave.update');
// });

Route::get('/leave-calendar', action: [EmployeeController::class, 'showCalendar'])->name('leave.calendar');
Route::get('/api/leaves', [EmployeeController::class, 'getLeaves']); 

// Route::get('/api/leaves', function () {
//     return response()->json(
//         Leave::where('status', 'approved') // Only show approved leaves
//              ->get(['id', 'start_date as start', 'end_date as end', 'reason as title'])
//     );
// });
require __DIR__.'/auth.php';