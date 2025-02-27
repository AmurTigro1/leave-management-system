<!DOCTYPE html>
<html>
<head>
    <title>Leave Credit Certification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        .signature {
            margin-top: 20px;
            text-align: center;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h3>A CERTIFICATION OF LEAVE CREDITS</h3>
    <p>As of {{ $date }}</p>

    <table>
        <tr>
            <th></th>
            <th>Vacation Leave</th>
            <th>Sick Leave</th>
        </tr>
        <tr>
            <td>Total Earned</td>
            <td>{{ $user->vacation_leave_balance }}</td>
            <td>{{ $user->sick_leave_balance }}</td>
        </tr>
        <tr>
            <td>Less this application</td>
            <td>{{ $leave->leave_type == 'Vacation Leave' ? $daysRequested : 0 }}</td>
            <td>{{ $leave->leave_type == 'Sick Leave' ? $daysRequested : 0 }}</td>
        </tr>
        <tr>
            <td>Balance</td>
            <td>{{ $vacationBalance }}</td>
            <td>{{ $sickBalance }}</td>
        </tr>
    </table>

    <div class="signature">
        <p>_________________________</p>
        <p>MYLOVE C. FLOOD</p>
        <p>HRMO</p>
    </div>
</body>
</html>
