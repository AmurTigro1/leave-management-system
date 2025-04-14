<!DOCTYPE html>
<html>
<head>
    <title>Leave Request Approval</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style type="text/css" scoped>
        /* Base styles */
        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f7f9fc;
        }
        
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .email-header {
            background-color: #3498db;
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        
        .email-body {
            padding: 30px;
        }
        
        h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        
        h2 {
            color: #3498db;
            font-size: 20px;
            margin-top: 25px;
            margin-bottom: 15px;
            border-bottom: 2px solid #eaeaea;
            padding-bottom: 8px;
        }
        
        p {
            margin: 0 0 15px 0;
        }
        
        .status-badge {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin-left: 8px;
        }
        
        .approved {
            background-color: #2ecc71;
            color: white;
        }
        
        .rejected {
            background-color: #e74c3c;
            color: white;
        }
        
        .pending {
            background-color: #f39c12;
            color: white;
        }
        
        .details-list {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 20px 0;
        }
        
        .details-list li {
            margin-bottom: 10px;
            list-style-type: none;
            padding-left: 20px;
            position: relative;
        }
        
        .details-list li:before {
            content: "•";
            color: #3498db;
            font-weight: bold;
            position: absolute;
            left: 0;
        }
        
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
            font-size: 14px;
            color: #7f8c8d;
        }
        
        .signature {
            margin-top: 25px;
            font-style: italic;
            color: #555555;
        }
        
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-weight: 600;
            margin-top: 15px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>Leave Request Status Update</h1>
        </div>
        
        <div class="email-body">
            <p>Dear {{ $leave->user->name }},</p>
            
            <p>Your leave request has been processed. Status: 
                @if($status === 'approved')
                <p>Your leave request has been <strong class="py-2 px-2 bg-green-500 text-white">approved</strong>.</p>
            @else
                <p>Unfortunately, your leave request has been <strong class="py-2 px-2 bg-green-500 text-white">rejected</strong>.</p>
            @endif
                
            </p>
            
            <h2>Leave Details</h2>
            
            <div class="details-list">
                <ul>
                    <li><strong>Type:</strong> {{ $leave->leave_type }}</li>
                    <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($leave->start_date)->format('F j, Y') }}</li>
                    <li><strong>End Date:</strong> {{ \Carbon\Carbon::parse($leave->end_date)->format('F j, Y') }}</li>
                    <li><strong>Days Applied:</strong> {{ $leave->days_applied }}</li>
                    @if($leave->status == 'approved')
                        <li><strong>Approved Days with Pay:</strong> {{ $leave->approved_days_with_pay }}</li>
                        <li><strong>Approved Days without Pay:</strong> {{ $leave->approved_days_without_pay }}</li>
                    @endif
                    @if($leave->status == 'rejected' && $leave->disapproval_reason)
                        <li><strong>Reason for Rejection:</strong> {{ $leave->disapproval_reason }}</li>
                    @endif
                </ul>
            </div>
            
            @if($leave->status == 'approved')
            <p>Your leave request has been approved. Please make necessary arrangements for your absence.</p>
            @elseif($leave->status == 'rejected')
            <p>We regret to inform you that your leave request could not be approved at this time. Please contact HR if you have any questions.</p>
            @endif
            
            <div class="footer">
                <p>If you have any questions about your leave request, please contact the HR department.</p>
                
                <div class="signature">
                    <p>Best regards,</p>
                    <p><strong>{{ config('app.name') }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>