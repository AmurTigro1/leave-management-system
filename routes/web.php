<?php
use App\Http\Controllers\EmployeeBalanceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HrController;
use App\Http\Controllers\LeaveApplicationController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\HRSupervisorController;
use App\Http\Controllers\OvertimeRequestController;
use App\Http\Controllers\TimeManagementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CocLogController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthenticatedSessionController::class, 'landing'])->middleware('visitor');

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
        // Route::post('/supervisor/{leaveId}/approve', [SupervisorController::class, 'approve'])->name('supervisor.approve');
        Route::post('/supervisor/reject/{leave}', [SupervisorController::class, 'reject'])->name('supervisor.reject');
        Route::get('/supervisor-profile', [SupervisorController::class, 'profile'])->name('supervisor.profile.index');
        Route::get('/supervisor/profile-edit', [SupervisorController::class, 'profile_edit'])->name('supervisor.profile.partials.update-profile-information-form');
        Route::get('/supervisor/password-edit', [SupervisorController::class, 'password_edit'])->name('supervisor.profile.partials.update-password-form');
        Route::patch('/supervisor-profile/update-profile', [SupervisorController::class, 'updateProfile'])->name('supervisor-profile.update');
        Route::patch('/supervisor-profile/update-email', [SupervisorController::class, 'updateEmail'])->name('supervisor-email.update');
        Route::get('/supervisor/holidays', [SupervisorController::class, 'holiday'])->name('supervisor.holiday.calendar');

        Route::get('/supervisor-leave/view/{id}', [SupervisorController::class, 'viewPdf'])->name('supervisor.leave.viewPdf');
        Route::get('/supervisor-cto/view/{id}', [SupervisorController::class, 'ctoviewPdf'])->name('supervisor.cto.viewPdf');

        Route::post('/notifications/supervisor-mark-as-read', [SupervisorController::class, 'markAsRead'])->name('supervisor.notifications.markAsRead');
        Route::delete('/notifications/supervisor-delete/{id}', [SupervisorController::class, 'delete'])->name('supervisor.notifications.delete');
        Route::delete('/notifications/supervisor-delete-all', [SupervisorController::class, 'deleteAll'])->name('supervisor.notifications.deleteAll');

        Route::get('/supervisor/users/modal', [SupervisorController::class, 'showSupervisorModal'])->name('supervisor.users.modal');
        // Route::get('/supervisor/requests', [SupervisorController::class, 'requests'])->name('supervisor.requests');
    });

    //HR Officer Route
    Route::middleware('hr')->group(function () {
        Route::get('/hr/dashboard', [HrController::class, 'index'])->name('hr.dashboard');
        Route::get('/hr/employees', [HrController::class, 'onLeave'])->name('hr.on_leave');
        Route::get('/hr/users', [HrController::class, 'users'])->name('hr.users');
        Route::get('/hr/requests', [HrController::class, 'requests'])->name('hr.requests');
        Route::get('/hr/my-requests', [HrController::class, 'myRequests'])->name('hr.my_requests');
        Route::get('/hr/my-requests/edit/{id}', [HrController::class, 'editLeave'])->name('hr.leave_edit');
        Route::put('/hr/my-requests/update/{id}', [HrController::class, 'updateLeave'])->name('hr.leave_update');
        Route::post('/hr/leaves/{id}/cancel', [HrController::class, 'cancel'])->name('hr.leave_cancel');
        Route::post('/hr/leaves/{id}/restore', [HrController::class, 'restore'])->name('hr.leave_restore');
        Route::delete('/hr/my-requests/delete/{id}', [HrController::class, 'deleteLeave'])->name('hr.leave_delete');
        Route::get('/hr/details/{id}', [HrController::class, 'show'])->name('hr.leave_show');
        Route::get('/leave/details/{id}', [HrController::class, 'showLeave'])->name('hr.leave_details');
        Route::get('/hr-leave/view/{id}', [HrController::class, 'viewPdf'])->name('hr.leave.viewPdf');
        Route::get('/hr-cto/view/{id}', [HrController::class, 'viewCtoPdf'])->name('hr.cto.viewPdf');
        Route::get('/hr/cto/details/{id}', [HrController::class, 'showcto'])->name('hr.cto_details');
        Route::post('/cto/{cto}/hr-review', [HrController::class, 'ctoreview'])->name('cto.hr-review');
        Route::get('/hr-overtime/view/{id}', [HrController::class, 'viewCtoPdf'])->name('hr.overtime.viewPdf');
        Route::get('/hr/calendar', [HrController::class, 'calendar'])->name('hr.holiday.calendar');
        Route::get('hr/holidays', [HrController::class, 'holiday'])->name('hr.holidays.index');
        Route::get('hr/holidays/create', [HrController::class, 'create'])->name('hr.holidays.create');
        Route::post('hr/holidays', [HrController::class, 'store'])->name('hr.holidays.store');
        Route::get('hr/holidays/{holiday}/edit', [HrController::class, 'edit'])->name('hr.holidays.edit');
        Route::put('hr/holidays/{holiday}', [HrController::class, 'update'])->name('hr.holidays.update');
        Route::delete('hr/holidays/{holiday}', [HrController::class, 'destroy'])->name('hr.holidays.destroy');

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
        Route::put('/hr-supervisor-info/{id}', [HRSupervisorController::class, 'update'])->name('hr-supervisor-info.update');

        Route::get('/hr/leave-request', [HrController::class, 'makeLeaveRequest'])->name('hr.make_leave_request');
        Route::post('/hr/request-leave', [HrController::class, 'storeLeave'])->name('hr-request.leave');
        Route::get('/hr/cto-request', [HrController::class, 'makeCTORequest'])->name('hr.make_cto_request');
        Route::post('/hr/overtime-request/store', [HrController::class, 'storeCTO'])->name('hr_overtime_request.store');

        Route::post('/hr/users/store', [UserController::class, 'store'])->name('hr.users.store');
        Route::get('/hr/coc-logs/', [CocLogController::class, 'indexHR'])->name('coc_logs.hr');
        Route::put('/hr/user/{id}', [UserController::class, 'update'])->name('hr.users.update');
        Route::delete('/hr/user/{id}', [UserController::class, 'destroy'])->name('hr.users.destroy');

        Route::put('/hr/user/{id}', [UserController::class, 'update'])->name('hr.users.update');
        Route::delete('/hr/user/{id}', [UserController::class, 'destroy'])->name('hr.users.destroy');

        Route::get('/check-existing-roles', [UserController::class, 'checkExistingRoles'])->name('check.existing.roles');
        Route::post('/swap-roles', [UserController::class, 'swapRoles'])->name('swap.roles');
        Route::get('/hr/users/modal', [HrController::class, 'showHrModal'])->name('hr.users.modal');
        //Notification
        Route::post('/notifications/hr-mark-as-read', [HRController::class, 'markAsRead'])->name('hr.notifications.markAsRead');
        Route::delete('/notifications/hr-delete/{id}', [HRController::class, 'delete'])->name('hr.notifications.delete');
        Route::delete('/notifications/hr-delete-all', [HRController::class, 'deleteAll'])->name('hr.notifications.deleteAll');

        Route::get('hr/cto-requests', [HrController::class, 'ctoRequests'])->name('hr.cto_requests');
        Route::get('/hr/my-CTO-requests/{id}', [HrController::class, 'myCtoRequests'])->name('hr.my_cto_requests');
        Route::put('/hr/my-CTO-requests/update/{id}', [HrController::class, 'updateCTO'])->name('hr.cto_update');
        Route::post('/hr/CTO/{id}/cancel', [HrController::class, 'cancelCTO'])->name('hr.cto_cancel');
        Route::post('/hr/CTO/{id}/restore', [HrController::class, 'restoreCTO'])->name('hr.cto_restore');
        Route::delete('/hr/my-CTO-requests/delete/{id}', [HrController::class, 'deleteCTO'])->name('hr.cto_delete');
        // Route::get('/hr-CTO/view/{id}', [HrController::class, 'viewCtoPdf'])->name('hr.overtime.viewPdf');

        //Alter Employee Balances
        Route::get('hr/employee-balances', [EmployeeBalanceController::class, 'indexHr'])->name('hr.employee-balances.index');
        Route::put('hr/employee-balances/{user}', [EmployeeBalanceController::class, 'updateHr'])->name('hr.employee-balances.update');

        Route::get('/check-existing-roles', [UserController::class, 'checkExistingRoles'])->name('check.existing.roles');
        Route::post('/swap-roles', [UserController::class, 'swapRoles'])->name('swap.roles');

    });

    //Admin Assistant Route
    Route::middleware('admin')->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/admin/employees', [AdminController::class, 'onLeave'])->name('admin.on_leave');
        Route::get('/admin/leaderboard', [AdminController::class, 'leaderboard'])->name('admin.leaderboard');
        Route::get('admin/requests', [AdminController::class, 'requests'])->name('admin.requests');
        Route::get('/admin/my-leave-requests', [AdminController::class, 'myRequests'])->name('admin.my_requests');
        Route::get('/admin/my-leave-requests/edit/{id}', [AdminController::class, 'editLeave'])->name('admin.leave_edit');
        Route::put('/admin/my-leave-requests/update/{id}', [AdminController::class, 'updateLeave'])->name('admin.leave_update');
        Route::post('/admin/leaves/{id}/cancel', [AdminController::class, 'cancel'])->name('admin.leave_cancel');
        Route::post('/admin/leaves/{id}/restore', [AdminController::class, 'restore'])->name('admin.leave_restore');
        Route::delete('/admin/my-leave-requests/delete/{id}', [AdminController::class, 'deleteLeave'])->name('admin.leave_delete');

        Route::get('admin/cto-requests', [AdminController::class, 'ctoRequests'])->name('admin.cto_requests');
        Route::get('/admin/my-CTO-requests/{id}', [AdminController::class, 'myCtoRequests'])->name('admin.my_cto_requests');
        Route::put('/admin/my-CTO-requests/update/{id}', [AdminController::class, 'updateCTO'])->name('admin.cto_update');
        Route::post('/admin/CTO/{id}/cancel', [AdminController::class, 'cancelCTO'])->name('admin.cto_cancel');
        Route::post('/admin/CTO/{id}/restore', [AdminController::class, 'restoreCTO'])->name('admin.cto_restore');
        Route::delete('/admin/my-CTO-requests/delete/{id}', [AdminController::class, 'deleteCTO'])->name('admin.cto_delete');
        Route::get('/admin-CTO/view/{id}', [AdminController::class, 'viewCtoPdf'])->name('admin.overtime.viewPdf');

        Route::get('/admin/details/{id}', [AdminController::class, 'show'])->name('admin.leave_show');
        Route::get('/admin-leave/view/{id}', [AdminController::class, 'viewPdf'])->name('admin.leave.viewPdf');
        Route::get('/admin-cto/view/{id}', [AdminController::class, 'viewCtoPdf'])->name('admin.cto.viewPdf');
        Route::post('/leave/{leave}/admin-review', [AdminController::class, 'review'])->name('leave.admin-review');
        Route::post('/cto/{cto}/admin-review', [AdminController::class, 'ctoreview'])->name('cto.admin-review');
        Route::get('/admin/leave/details/{id}', [AdminController::class, 'showleave'])->name('admin.leave_details');
        Route::get('/admin/cto/details/{id}', [AdminController::class, 'showcto'])->name('admin.cto_details');
        Route::get('/admin-profile', [AdminController::class, 'profile'])->name('admin.profile.index');
        Route::get('/admin/profile-edit', [AdminController::class, 'profile_edit'])->name('admin.profile.partials.update-profile-information-form');
        Route::get('/admin/password-edit', [AdminController::class, 'password_edit'])->name('admin.profile.partials.update-password-form');
        Route::patch('/admin-profile/update-profile', [AdminController::class, 'updateProfile'])->name('admin-profile.update');
        Route::patch('/admin-profile/update-email', [AdminController::class, 'updateEmail'])->name('admin-email.update');
        Route::get('/admin/holidays', [AdminController::class, 'holiday'])->name('admin.holiday.calendar');

        Route::get('/admin/leave-request', [AdminController::class, 'makeLeaveRequest'])->name('admin.make_leave_request');
        Route::post('/admin/request-leave', [AdminController::class, 'storeLeave'])->name('admin-request.leave');
        Route::get('/admin/cto-request', [AdminController::class, 'makeCTORequest'])->name('admin.make_cto_request');
        Route::post('/admin/overtime-request/store', [AdminController::class, 'storeCTO'])->name('admin_overtime_request.store');
        Route::get('/admin/coc-logs/', [CocLogController::class, 'indexAdmin'])->name('coc_logs.admin');

        Route::get('/admins/modal', [AdminController::class, 'showAdminsModal'])->name('admins.modal');
        Route::get('/admin/employees', [AdminController::class, 'onLeave'])->name('admin.on_leave');

        Route::get('/admins/modal', [AdminController::class, 'showAdminsModal'])->name('admins.modal');
        Route::get('/admin/employees', [AdminController::class, 'onLeave'])->name('admin.on_leave');

        Route::post('/notifications/admin-mark-as-read', [AdminController::class, 'markAsRead'])->name('admin.notifications.markAsRead');
        Route::delete('/notifications/admin-delete/{id}', [AdminController::class, 'delete'])->name('admin.notifications.delete');
        Route::delete('/notifications/admin-delete-all', [AdminController::class, 'deleteAll'])->name('admin.notifications.deleteAll');

        //Alter Employee Balances
        Route::get('/employee-balances', [EmployeeBalanceController::class, 'index'])->name('employee-balances.index');
        Route::put('/employee-balances/{user}', [EmployeeBalanceController::class, 'update'])->name('employee-balances.update');
        
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
        Route::get('/employee/calendar', [EmployeeController::class, 'calendar'])->name('employee.holiday.calendar');

        //CTO
        Route::get('/cto/dashboard', [OvertimeRequestController::class, 'dashboard'])->name('cto.dashboard');
        Route::get('/overtime-request', [OvertimeRequestController::class, 'index'])->name('cto.overtime_request');
        Route::get('/overtime-list', [OvertimeRequestController::class, 'list'])->name('cto.overtime_list');
        // Route::get('/my-requests-overtime/edit/{id}', [OvertimeRequestController::class, 'editOvertime'])->name('cto.overtime_edit');
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

    Route::get('/coc-logs/{id}', [CocLogController::class, 'showHRCocLogs'])->name('coc-logs.show');
    Route::get('/coc-logs/{id}/pdf', [CocLogController::class, 'generateCocLogsPdf'])->name('coc-logs.pdf');

    Route::post('/coc-logs/store', [CocLogController::class, 'store'])->name('coc-logs.store');
    Route::get('/coc-logs/{cocLog}/edit', [CocLogController::class, 'edit'])->name('coc-logs.edit');
    Route::put('/coc-logs/{cocLog}', [CocLogController::class, 'update'])->name('coc-logs.update');
    Route::delete('/coc-logs/{cocLog}', [CocLogController::class, 'destroy'])->name('coc-logs.destroy');
});
require __DIR__.'/auth.php';
