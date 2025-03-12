<?php
use App\Http\Controllers\HrController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\OvertimeRequestController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('employee.landing');
});

//Login Route
Route::get('/lms-cto/login', [EmployeeController::class, 'loginLmsCto'])->name('lms_cto.login');

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-image', [EmployeeController::class, 'updateProfileImage'])->name('profile.update-image');
    Route::get('/holidays', [EmployeeController::class, 'holiday'])->name('holiday.calendar');
    // Route::get('/leave', [HrController::class, 'index'])->name('leave.index');
    // Route::post('/leave', [HrController::class, 'store'])->name('leave.store');
});

//Supervisor Routes
Route::middleware(['auth', 'SupervisorMiddleware'])->group(function () {
    Route::get('/supervisor/dashboard', [SupervisorController::class, 'index'])->name('supervisor.dashboard');
    Route::get('/supervisor/requests', [SupervisorController::class, 'requests'])->name('supervisor.requests');
    Route::post('/supervisor/{leave}/approve', [SupervisorController::class, 'approve'])->name('supervisor.approve');
    Route::post('/supervisor/reject/{leave}', [SupervisorController::class, 'reject'])->name('supervisor.reject');
    Route::get('/supervisor-profile', [SupervisorController::class, 'profile'])->name('supervisor.profile.index');
    Route::get('/supervisor-profile-edit', [SupervisorController::class, 'edit'])->name('supervisor.profile.edit');
    Route::patch('/supervisor-profile', [SupervisorController::class, 'update'])->name('supervisor-profile.update');
    // Route::get('/supervisor/requests', [SupervisorController::class, 'requests'])->name('supervisor.requests');
});

//HR Officer Route
Route::middleware(['auth', 'hrMiddleware'])->group(function () {
    Route::get('/hr/dashboard', [HrController::class, 'index'])->name('hr.dashboard');
    Route::get('/hr/requests', [HrController::class, 'requests'])->name('hr.leave_requests');
    Route::get('/leave/details/{id}', [HrController::class, 'show'])->name('hr.leave_details');
    Route::get('/hr/leave-certification/{leaveId}', [HrController::class, 'showLeaveCertification'])->name('hr.leave_certification');
    Route::post('/leave/{leave}/review', [HrController::class, 'review'])->name('leave.review');
    Route::get('/leave-report/{id}', [HrController::class, 'generateLeaveReport'])->name('leave.report');

});

//Employee Route
Route::middleware(['auth.redirect', 'employeeMiddleware'])->group(function () {
    Route::get('/lms-cto/dashboard', [EmployeeController::class, 'indexLMS'])->name('lms_cto.dashboard');

    //LMS
    Route::get('/make-request', [EmployeeController::class, 'makeRequest'])->name('employee.make_request');
    Route::get('/my-requests', [EmployeeController::class, 'showRequests'])->name('employee.leave_request');
    Route::get('/my-requests/edit/{id}', [EmployeeController::class, 'editLeave'])->name('employee.leave_edit');
    Route::put('/my-requests/update/{id}', [EmployeeController::class, 'updateLeave'])->name('employee.leave_update');
    Route::delete('/my-requests/delete/{id}', [EmployeeController::class, 'deleteLeave'])->name('employee.leave_delete');
    Route::get('/details/{id}', [EmployeeController::class, 'show'])->name('employee.leave_show');
    Route::post('/request-leave', [EmployeeController::class, 'store'])->name('request.leave');
    Route::get('/lms-profile', [EmployeeController::class, 'profile'])->name('employee.profile.index');
    Route::get('/lms-profile-edit', [EmployeeController::class, 'edit'])->name('employee.profile.edit');
    Route::patch('/lms-profile', [EmployeeController::class, 'update'])->name('employee-profile.update');
    Route::get('/leave/view/{id}', [EmployeeController::class, 'viewPdf'])->name('leave.viewPdf');
    Route::get('/leaderboard', [EmployeeController::class, 'leaderboard'])->name('employee.leaderboard');
    Route::get('/users/modal', [EmployeeController::class, 'showUsersModal'])->name('users.modal');


    //CTO
    Route::get('/cto/dashboard', [OvertimeRequestController::class, 'dashboard'])->name('cto.dashboard');
    Route::get('/overtime-request', [OvertimeRequestController::class, 'index'])->name('cto.overtime_request');
    Route::get('/overtime-list', [OvertimeRequestController::class, 'list'])->name('cto.overtime_list');
    Route::post('/overtime-request/store', [OvertimeRequestController::class, 'store'])->name('overtime_request.store');
    Route::get('/cto-profile', [OvertimeRequestController::class, 'profile'])->name('cto.profile.index');
    Route::get('/cto-profile-edit', [OvertimeRequestController::class, 'edit'])->name('cto.profile.edit');
    Route::patch('/cto-profile', [OvertimeRequestController::class, 'update'])->name('cto-profile.update');
});

Route::get('/leave-calendar', action: [EmployeeController::class, 'showCalendar'])->name('leave.calendar');
Route::get('/api/leaves', [EmployeeController::class, 'getLeaves']); 

require __DIR__.'/auth.php';

//Admin Route
// Route::middleware(['auth', 'SupervisorMiddleware'])->group(function () {
//     Route::get('/supervisor/dashboard', [AdminController::class, 'index'])->name('supervisor.dashboard');
//     Route::get('/supervisor/requests', [AdminController::class, 'requests'])->name('supervisor.requests');
//     Route::post('/supervisor/leave/update/{leave}', [AdminController::class, 'approve'])->name('supervisor.leave.update');
// });
