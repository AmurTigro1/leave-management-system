<!DOCTYPE html>
<html>
<head>
    <style>
        .leave-category {
            margin-bottom: 15px;
            page-break-inside: avoid;
        }
        .leave-category h3 {
            background-color: #f8f9fa;
            padding: 5px;
            margin: 5px 0;
        }
        .leave-item {
            display: flex;
            justify-content: space-between;
            padding: 3px 0;
            border-bottom: 1px solid #eee;
        }
        .leave-name {
            font-weight: 500;
        }
        .leave-balance {
            font-weight: bold;
        }
        .summary-card {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 20px;
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>
    <div class="summary-card">
        <h2>Employee Leave Summary</h2>
        <p>Generated on: {{ now()->format('F j, Y') }}</p>
        <p>Total Employees: {{ count($users) }}</p>
    </div>

    @foreach($users as $user)
    <div style="page-break-inside: avoid; margin-bottom: 20px;">
        <h3>{{ $user->first_name }} {{ $user->last_name }} ({{ $user->employee_code }})</h3>
        
        <div class="leave-category">
            <h3>Regular Leaves</h3>
            <div class="leave-item">
                <span class="leave-name">Vacation Leave</span>
                <span class="leave-balance">{{ $user->vacation_leave_balance }} days</span>
            </div>
            <div class="leave-item">
                <span class="leave-name">Sick Leave</span>
                <span class="leave-balance">{{ $user->sick_leave_balance }} days</span>
            </div>
        </div>

        <div class="leave-category">
            <h3>Parental Leaves</h3>
            <div class="leave-item">
                <span class="leave-name">Maternity Leave</span>
                <span class="leave-balance">{{ $user->maternity_leave }} days</span>
            </div>
            <div class="leave-item">
                <span class="leave-name">Paternity Leave</span>
                <span class="leave-balance">{{ $user->paternity_leave }} days</span>
            </div>
            <div class="leave-item">
                <span class="leave-name">Solo Parent Leave</span>
                <span class="leave-balance">{{ $user->solo_parent_leave - $user->solo_parent_leave_taken }} days remaining</span>
            </div>
        </div>

        <div class="leave-category">
            <h3>Special Leaves</h3>
            <div class="leave-item">
                <span class="leave-name">Study Leave</span>
                <span class="leave-balance">{{ $user->study_leave }} days</span>
            </div>
            <div class="leave-item">
                <span class="leave-name">VAWC Leave</span>
                <span class="leave-balance">{{ $user->vawc_leave }} days</span>
            </div>
            <div class="leave-item">
                <span class="leave-name">Rehabilitation Leave</span>
                <span class="leave-balance">{{ $user->rehabilitation_leave }} days</span>
            </div>
            <div class="leave-item">
                <span class="leave-name">Special Leave Benefit</span>
                <span class="leave-balance">{{ $user->special_leave_benefit }} days</span>
            </div>
        </div>

        <div class="leave-category">
            <h3>Other Leaves</h3>
            <div class="leave-item">
                <span class="leave-name">Special Privilege Leave</span>
                <span class="leave-balance">{{ $user->special_privilege_leave - $user->special_leave_taken }} days remaining</span>
            </div>
            <div class="leave-item">
                <span class="leave-name">Special Emergency Leave</span>
                <span class="leave-balance">{{ $user->special_emergency_leave }} days</span>
            </div>
            <div class="leave-item">
                <span class="leave-name">Overtime Balance</span>
                <span class="leave-balance">{{ $user->overtime_balance }} hours</span>
            </div>
        </div>
    </div>
    @endforeach
</body>
</html>

<script>
    // Add this script to your view
document.addEventListener('DOMContentLoaded', function() {
    const exportBtn = document.querySelector('a[href*="export=pdf"]');
    if (exportBtn) {
        exportBtn.addEventListener('click', function(e) {
            if (!confirm('This will export all matching users to PDF. Continue?')) {
                e.preventDefault();
            }
        });
    }
});
</script>