<!DOCTYPE html>
<html>
<head>
    <title>CTO Request Approval</title>
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
            background-color: #4a6fa5;
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
            color: #4a6fa5;
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
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 14px;
        }
        
        .approved {
            background-color: #2ecc71;
            color: white;
        }
        
        .rejected {
            background-color: #e74c3c;
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
            color: #4a6fa5;
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
        
        .action-message {
            padding: 15px;
            border-left: 4px solid;
            margin: 20px 0;
            background-color: #f8f9fa;
        }
        
        .approved-message {
            border-left-color: #2ecc71;
        }
        
        .rejected-message {
            border-left-color: #e74c3c;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <h1>CTO Request Status Update</h1>
        </div>
        
        <div class="email-body">
            <p>Dear {{ $cto->user->name }},</p>
            
            <p>Your CTO request has been 
                <span class="status-badge {{ $cto->status === 'approved' ? 'approved' : 'rejected' }}">
                    {{ $cto->status }}
                </span>
            </p>
            
            <h2>CTO Details</h2>
            
            <div class="details-list">
                <ul>
                    <li><strong>Date Filed:</strong> {{ \Carbon\Carbon::parse($cto->date_filed)->format('F j, Y') }}</li>
                    <li><strong>Working Hours Applied:</strong> {{ $cto->working_hours_applied }} hours</li>
                    <li><strong>Inclusive Dates:</strong> {{ $cto->inclusive_dates }}</li>
                    <li><strong>CTO Type:</strong> {{ ucfirst(str_replace('_', ' ', $cto->cto_type)) }}</li>
                    @if($cto->status == 'approved')
                        <li><strong>Approved Hours:</strong> {{ $cto->working_hours_applied }} hours</li>
                    @endif
                    @if($cto->status == 'rejected' && $cto->disapproval_reason)
                        <li><strong>Reason for Rejection:</strong> {{ $cto->disapproval_reason }}</li>
                    @endif
                </ul>
            </div>
            
            @if($cto->status == 'approved')
            <div class="action-message approved-message">
                <p>Your CTO request has been approved. Please make necessary arrangements for your absence.</p>
            </div>
            @elseif($cto->status == 'rejected')
            <div class="action-message rejected-message">
                <p>We regret to inform you that your CTO request could not be approved at this time. Please contact HR if you have any questions.</p>
            </div>
            @endif
            
            <div class="footer">
                <p>If you have any questions about your CTO request, please contact the HR department.</p>
                
                <div class="signature">
                    <p>Best regards,</p>
                    <p><strong>{{ config('app.name') }}</strong></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>