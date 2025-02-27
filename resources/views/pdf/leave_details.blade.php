<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Form</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .container { width: 100%; max-width: 800px; margin: auto; border: 2px solid black; padding: 20px; }
        .title { text-align: center; font-size: 22px; font-weight: bold; text-transform: uppercase; margin-bottom: 20px; }
        .form-group { margin-bottom: 15px; }
        .label { font-weight: bold; display: block; margin-bottom: 5px; }
        .underline { border-bottom: 1px solid black; display: inline-block; width: 100%; height: 20px; vertical-align: middle; }
        .status { text-align: center; font-size: 16px; font-weight: bold; padding: 10px; border: 2px solid black; width: 200px; margin: auto; }
        .signature-container { display: flex; justify-content: space-between; margin-top: 40px; }
        .signature-box { text-align: center; width: 45%; }
        .signature-line { border-top: 1px solid black; margin-top: 40px; width: 80%; margin-left: auto; margin-right: auto; }
        .small-text { font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Leave Request Form</div>

        <div class="form-group">
            <span class="label">Employee Name:</span>
            <span class="underline">{{ $leave->user->name ?? '________________________' }}</span>
        </div>


        <div class="form-group">
            <span class="label">Position:</span>
            <span class="underline">{{ $leave->position ?? '__________________' }}</span>
        </div>

        <div class="form-group">
            <span class="label">Leave Type:</span>
            <span class="underline">{{ $leave->leave_type ?? '____________________' }}</span>
        </div>

        <div class="form-group">
            <span class="label">Reason for Leave:</span>
            <span class="underline">{{ $leave->reason ?? '________________________________________' }}</span>
        </div>

        <div class="form-group">
            <span class="label">Start Date:</span>
            <span class="underline">{{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') ?? '____________' }}</span>
        </div>

        <div class="form-group">
            <span class="label">End Date:</span>
            <span class="underline">{{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') ?? '____________' }}</span>
        </div>
        <div class="form-group">
            <span class="label">Total Days:</span>
            <span class="ml-2">{{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }}</span>
        </div>
        <div class="form-group">
            <span class="label">Status:</span>
            <div class="status">
                {{ ucfirst($leave->status) }}
            </div>
        </div>

        <div class="signature-container">
            <div class="signature-box">
                <div class="signature-line"></div>
                <p class="small-text">Employee Signature</p>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <p class="small-text">Manager/HR Signature</p>
            </div>
        </div>
    </div>
</body>
</html>
