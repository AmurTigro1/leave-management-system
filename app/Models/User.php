<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'position',
        'last_name',
        'department',
        'email',
        'employee_code',
        'birthday',
        'password',
        'role', 
        'vacation_leave_balance',
        'special_privilege_leave',
        'sick_leave_balance',
        'leave_balance',
        'vacation_leave_balance',
        'sick_leave_balance',
        'maternity_leave',
        'paternity_leave',
        'solo_parent_leave',
        'study_leave',
        'vawc_leave',
        'rehabilitation_leave',
        'special_leave_benefit',
        'special_emergency_leave',
        'overtime_balance',
        'profile_image',
        'special_leave_taken',
        'solo_parent_leave_taken',
    ];
    
    public function leaves() {
        return $this->hasMany(Leave::class);
    }

    public function overtimeRequests()
    {
        return $this->hasMany(OvertimeRequest::class);
    }

    public function compensatoryTimeLogs()
    {
        return $this->hasMany(CompensatoryTimeLog::class);
    }

    public function timeManagement()
    {
        return $this->hasMany(TimeManagement::class);
    }

    public function redirectToDashboard()
    {
        $user = Auth::user();
        
        $dashboardRoute = match ($user->role) {
            'employee' => route('lms_cto.dashboard'),
            'supervisor' => route('supervisor.dashboard'),
            'hr' => route('hr.dashboard'),
            'admin' => route('admin.dashboard'),
            default => route('lms_cto.dashboard'),
        };
    
        notify()->success('Login Successful! Welcome Back.');
    
        return $dashboardRoute;
    }    

    public function cocLogs()
    {
        return $this->hasMany(CocLog::class);
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
