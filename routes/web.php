<?php
use App\Http\Controllers\HrController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\HRSupervisorController;
use App\Http\Controllers\OvertimeRequestController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CocLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticatedSessionController::class, 'landing']);

Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/update-image', [EmployeeController::class, 'updateProfileImage'])->name('profile.update-image');
    Route::get('/holidays', [EmployeeController::class, 'holiday'])->name('holiday.calendar');


    //Supervisor Routes
    Route::middleware('supervisor')->group(function () {
        Route::get('/supervisor/dashboard', [SupervisorController::class, 'index'])->name('supervisor.dashboard');
        Route::get('/supervisor/leaderboard', [SupervisorController::class, 'leaderboard'])->name('supervisor.leaderboard');
        Route::get('/supervisor/employees', [SupervisorController::class, 'onLeave'])->name('supervisor.on_leave');
        Route::get('/supervisor/requests', [SupervisorController::class, 'requests'])->name('supervisor.requests');
        Route::post('/supervisor/{leaveId}/approve', [SupervisorController::class, 'approve'])->name('supervisor.approve');
        Route::post('/supervisor/reject/{leave}', [SupervisorController::class, 'reject'])->name('supervisor.reject');
        Route::get('/supervisor-profile', [SupervisorController::class, 'profile'])->name('supervisor.profile.index');
        Route::get('/supervisor/profile-edit', [SupervisorController::class, 'profile_edit'])->name('supervisor.profile.partials.update-profile-information-form');
        Route::get('/supervisor/password-edit', [SupervisorController::class, 'password_edit'])->name('supervisor.profile.partials.update-password-form');
        Route::patch('/supervisor-profile/update-profile', [SupervisorController::class, 'updateProfile'])->name('supervisor-profile.update');
        Route::patch('/supervisor-profile/update-email', [SupervisorController::class, 'updateEmail'])->name('supervisor-email.update'); 
        Route::get('/supervisor/holidays', [SupervisorController::class, 'holiday'])->name('supervisor.holiday.calendar');
        
        // Route::get('/supervisor/requests', [SupervisorController::class, 'requests'])->name('supervisor.requests');
    });

    //HR Officer Route
    Route::middleware('hr')->group(function () {
        Route::get('/hr/dashboard', [HrController::class, 'index'])->name('hr.dashboard');
        Route::get('/hr/employees', [HrController::class, 'onLeave'])->name('hr.on_leave');
        Route::get('/hr/requests', [HrController::class, 'requests'])->name('hr.requests');
        Route::get('/leave/details/{id}', [HrController::class, 'show'])->name('hr.leave_details');
        Route::get('/hr/calendar', [HrController::class, 'calendar'])->name('hr.holiday.calendar');
    Route::get('holidays', [HrController::class, 'holiday'])->name('hr.holidays.index');
    Route::get('holidays/create', [HrController::class, 'create'])->name('hr.holidays.create');
        Route::post('holidays', [HrController::class, 'store'])->name('hr.holidays.store');
    Route::get('holidays/{holiday}/edit', [HrController::class, 'edit'])->name('hr.holidays.edit');
    Route::put('holidays/{holiday}', [HrController::class, 'update'])->name('hr.holidays.update');
    Route::delete('holidays/{holiday}', [HrController::class, 'destroy'])->name('hr.holidays.destroy');

    Route::get('/hr/leave-certification/{leaveId}', [HrController::class, 'showLeaveCertification'])->name('hr.leave_certification');
        Route::post('/leave/{leave}/review', [HrController::class, 'review'])->name('leave.review');
        Route::get('/leave-report/{id}', [HrController::class, 'generateLeaveReport'])->name('leave.report');
        Route::get('/hr/leaderboard', [HrController::class, 'leaderboard'])->name('hr.leaderboard');
        Route::get('/hr-profile', [HrController::class, 'profile'])->name('hr.profile.index');
        Route::get('/hr/profile-edit', [HrController::class, 'profile_edit'])->name('hr.profile.partials.update-profile-information-form');
        Route::get('/hr/password-edit', [HrController::class, 'password_edit'])->name('hr.profile.partials.update-password-form');
        Route::patch('/hr-profile/update-profile', [HrController::class, 'updateProfile'])->name('hr-profile.update');
        Route::patch('/hr-profile/update-email', [HrController::class, 'updateEmail'])->name('hr-email.update'); 
        
        Route::put('/overtime/{id}/approve', [HrController::class, 'approve'])->name('overtime.approve');
        Route::put('/overtime/{id}/reject', [HrController::class, 'reject'])->name('overtime.reject');
        Route::get('/hr/overtime-requests', [HrController::class, 'overtimeRequests'])->name('hr.overtime_requests');
        Route::get('/overtime/details/{id}', [HrController::class, 'showOvertime'])->name('hr.overtime_details');

        Route::post('/update-officer', [EmployeeController::class, 'updateOfficer'])->name('update.officer');
    });

//Admin Assistant Route
Route::middleware(['auth', 'adminMiddleware'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/employees', [AdminController::class, 'onLeave'])->name('admin.on_leave');
    Route::get('/admin/leaderboard', [AdminController::class, 'leaderboard'])->name('admin.leaderboard');
    Route::get('admin/requests', [AdminController::class, 'requests'])->name('admin.requests');
    Route::get('/admin/leave/details/{id}', [AdminController::class, 'showleave'])->name('admin.leave_details');
    Route::get('/admin/cto/details/{id}', [AdminController::class, 'showcto'])->name('admin.cto_details');
    Route::get('/admin-profile', [AdminController::class, 'profile'])->name('admin.profile.index');
    Route::get('/admin/profile-edit', [AdminController::class, 'profile_edit'])->name('admin.profile.partials.update-profile-information-form');
    Route::get('/admin/password-edit', [AdminController::class, 'password_edit'])->name('admin.profile.partials.update-password-form');
    Route::patch('/admin-profile/update-profile', [AdminController::class, 'updateProfile'])->name('admin-profile.update');
    Route::patch('/admin-profile/update-email', [AdminController::class, 'updateEmail'])->name('admin-email.update'); 
    Route::get('/admin/holidays', [AdminController::class, 'holiday'])->name('admin.holiday.calendar');
});


    //Employee Route
    Route::middleware('employee')->group(function () {
        Route::get('/lms-cto/dashboard', [EmployeeController::class, 'indexLMS'])->name('lms_cto.dashboard');

        //LMS
        Route::get('/make-request', [EmployeeController::class, 'makeRequest'])->name('employee.make_request');
        Route::get('/my-requests', [EmployeeController::class, 'showRequests'])->name('employee.leave_request');
        Route::get('/my-requests/edit/{id}', [EmployeeController::class, 'editLeave'])->name('employee.leave_edit');
        Route::put('/my-requests/update/{id}', [EmployeeController::class, 'updateLeave'])->name('employee.leave_update');
        Route::post('/leaves/{id}/cancel', [EmployeeController::class, 'cancel'])->name('employee.leave_cancel');
        Route::post('/leaves/{id}/restore', [EmployeeController::class, 'restore'])->name('employee.leave_restore');
        Route::delete('/my-requests/delete/{id}', [EmployeeController::class, 'deleteLeave'])->name('employee.leave_delete');
        Route::get('/details/{id}', [EmployeeController::class, 'show'])->name('employee.leave_show');
        Route::post('/request-leave', [EmployeeController::class, 'store'])->name('request.leave');
        Route::get('/lms-profile', [EmployeeController::class, 'profile'])->name('employee.profile.index');
        Route::get('/profile-edit', [EmployeeController::class, 'profile_edit'])->name('employee.profile.partials.update-profile-information-form');
        Route::get('/password-edit', [EmployeeController::class, 'password_edit'])->name('employee.profile.partials.update-password-form');
        Route::patch('/lms-profile/update-profile', [EmployeeController::class, 'updateProfile'])->name('employee-profile.update');
        Route::patch('/lms-profile/update-email', [EmployeeController::class, 'updateEmail'])->name('employee-email.update');    
        Route::get('/leave/view/{id}', [EmployeeController::class, 'viewPdf'])->name('leave.viewPdf');
        Route::get('/leaderboard', [EmployeeController::class, 'leaderboard'])->name('employee.leaderboard');
        Route::get('/users/modal', [EmployeeController::class, 'showUsersModal'])->name('users.modal');
        Route::post('/notifications/mark-as-read', [EmployeeController::class, 'markAsRead'])->name('notifications.markAsRead');
        Route::delete('/notifications/delete/{id}', [EmployeeController::class, 'delete'])->name('notifications.delete');
        Route::delete('/notifications/delete-all', [EmployeeController::class, 'deleteAll'])->name('notifications.deleteAll');

        //CTO
        Route::get('/cto/dashboard', [OvertimeRequestController::class, 'dashboard'])->name('cto.dashboard');
        Route::get('/overtime-request', [OvertimeRequestController::class, 'index'])->name('cto.overtime_request');
        Route::get('/overtime-list', [OvertimeRequestController::class, 'list'])->name('cto.overtime_list');
        Route::get('/my-requests-overtime/edit/{id}', [OvertimeRequestController::class, 'editOvertime'])->name('cto.overtime_edit');
        Route::put('/my-requests-overtime/update/{id}', [OvertimeRequestController::class, 'updateOvertime'])->name('cto.overtime_update');
        Route::delete('/my-requests-overtime/delete/{id}', [OvertimeRequestController::class, 'deleteOvertime'])->name('cto.overtime_delete');
        Route::get('/details-overtime/{id}', [OvertimeRequestController::class, 'show'])->name('cto.overtime_show');
        Route::get('/overtime/view/{id}', [OvertimeRequestController::class, 'viewPdf'])->name('overtime.viewPdf');
        Route::post('/overtime-request/store', [OvertimeRequestController::class, 'store'])->name('overtime_request.store');
        Route::get('/cto-profile', [OvertimeRequestController::class, 'profile'])->name('cto.profile.index');
        Route::get('/cto-profile-edit', [OvertimeRequestController::class, 'edit'])->name('cto.profile.edit');
        Route::patch('/cto-profile', [OvertimeRequestController::class, 'update'])->name('cto-profile.update');
        Route::get('/cto-limit-warning', function () {
            notify()->warning('You can only select up to 5 consecutive days for CTO.');
            return back();
        });
        Route::post('/cto/{id}/cancel', [EmployeeController::class, 'cancelCTO'])->name('employee.cto_cancel');
        Route::post('/cto/{id}/restore', [EmployeeController::class, 'restoreCTO'])->name('employee.cto_restore');
    });

    Route::get('/leave-calendar', action: [EmployeeController::class, 'showCalendar'])->name('leave.calendar');
    Route::get('/api/leaves', [EmployeeController::class, 'getLeaves']); 
    Route::get('/api/overtimes', [EmployeeController::class, 'getOvertimes']);

    Route::middleware('role:hr,admin')->group(function () {
        Route::prefix('coc-logs')->group(function () {
            Route::get('/', [CocLogController::class, 'index'])->name('coc_logs');
            Route::get('/{id}', [CocLogController::class, 'showCocLogs'])->name('coc-logs.show');
            Route::get('/{id}/pdf', [CocLogController::class, 'generateCocLogsPdf'])->name('coc-logs.pdf');
            Route::post('/store', [CocLogController::class, 'store'])->name('coc-logs.store');
            Route::get('/{cocLog}/edit', [CocLogController::class, 'edit'])->name('coc-logs.edit');
            Route::put('/{cocLog}', [CocLogController::class, 'update'])->name('coc-logs.update');
            Route::delete('/{cocLog}', [CocLogController::class, 'destroy'])->name('coc-logs.destroy');
        });
    });
});
require __DIR__.'/auth.php';