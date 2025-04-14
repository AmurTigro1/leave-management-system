<!DOCTYPE html>
<html>
<head>
    <title>CTO Request Approval</title>
    <style scoped>
        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .details-table th, .details-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .details-table th {
            background-color: #f2f2f2;
        }
        .approved {
            color: #28a745;
            font-weight: bold;
        }
        .rejected {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>CTO Request Status Update</h2>
    
    <p>Dear {{ $cto->user->name }},</p>
    
    <p>Your CTO request has been <span class="{{ $cto->status === 'approved' ? 'approved' : 'rejected' }}">{{ $cto->status }}</span>.</p>
    
    <h3>CTO Details:</h3>
    <table class="details-table">
        <tr>
            <th>Date Filed</th>
            <td>{{ $cto->date_filed }}</td>
        </tr>
        <tr>
            <th>Working Hours Applied</th>
            <td>{{ $cto->working_hours_applied }} hours</td>
        </tr>
        <tr>
            <th>Inclusive Dates</th>
            <td>{{ $cto->inclusive_dates }}</td>
        </tr>
        <tr>
            <th>CTO Type</th>
            <td>{{ ucfirst(str_replace('_', ' ', $cto->cto_type)) }}</td>
        </tr>
        {{-- @if($cto->status == 'approved')
        <tr>
            <th>Approved Hours</th>
            <td>{{ $cto->working_hours_applied }} hours</td>
        </tr>
        @endif
        @if($cto->status == 'rejected' && $cto->disapproval_reason)
        <tr>
            <th>Reason for Rejection</th>
            <td>{{ $cto->disapproval_reason }}</td>
        </tr>
        @endif --}}
        <th>Approved Hours</th>
        <td>{{ $cto->working_hours_applied }} hours</td>
        
        @if($cto->status == 'approved')
        <p>Your CTO request has been approved. Please make necessary arrangements for your absence.</p>
        @elseif($cto->status == 'rejected')
        <p>We regret to inform you that your CTO request could not be approved at this time. Please contact HR if you have any questions.</p>
        @endif
    </table>
    
    <p>Thank you,</p>
    <p>{{ config('app.name') }}</p>
</body>
</html>