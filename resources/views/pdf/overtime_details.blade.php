<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $overtime->user->name}} - Overtime Request</title>
    <link rel="stylesheet" href="{{ public_path('pdf/pdf_design2.css') }}">
</head>
<body> 
    <div class="pdf-body">
        <div class="overall-header">
            <div class="head-logo">
                <img src="{{ public_path('img/dilg-main.png') }}" style="width: 85px; height:auto" alt="">
            </div>
            <div class="head-title">
                <p class="repub">Republic of the Philippines</p>
                <p class="depart">DEPARTMENT OF THE INTERIOR AND LOCAL GOVERNMENT</p>
                <p class="rajah">Rajah Sikatuna Avenue, Dampas, City of Tagbilaran, Bohol</p>
                <p class="tel">Tel/Fax No. (308) 422-8038 Email: <span class="mail">info@bohol.dilgregion7.net</span></p>
            </div>
        </div>
        <div class="overall-body">
            <div class="right-side">
               <div class="align-left">
                    <div class="start">
                        <p>Name </p> <div class="info"><span class="underline">{{ $overtime->user->first_name}} {{ strtoupper(substr($overtime->user->middle_name, 0, 1)) }}. {{ $overtime->user->last_name}}</span>________________________</div>
                        <p>Signature </p> <div class="info2">______________________________________</div>
                        <p>Position </p> <div class="info3"><span class="underline">{{ $overtime->position}}</span>_________________________</div>
                        <p>Office/Division </p> </p> <div class="info4"><span class="underline">{{ $overtime->office_division}}</span>__________________________</div>
                        <p>Date of Filing </p> <div class="info5"><span class="underline">{{ \Carbon\Carbon::parse($overtime->date_filed)->format('F d, Y') }}</span>_______________________</div>
                        <p>No. of working hours applied for </p> <div class="info6">__________<span class="underline">{{ $overtime->working_hours_applied}}</span>___________</div>
                        <p>Inclusive Date/s </p> <div class="info7"><span class="underline">{{ \Carbon\Carbon::parse($overtime->inclusive_date_start)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($overtime->inclusive_date_end)->format('F d, Y') }}</span>_______</div>
                    </div>
                    <div class="middle">
                        <p class="cert">CERTIFICATION OF COMPENSATORY CREDITS (COC)</p>
                        <p class="as-of">As of _______<span class="underline">{{ \Carbon\Carbon::parse($overtime->date_filed)->format('F') }}</span>_______, 20<span class="underline">{{ \Carbon\Carbon::parse($overtime->date_filed)->format('y') }}</span>.</p>
                        <p class="sub">(Month)</p>
                        <p class="num">Number of hours earned: ____________<span class="underline">{{ $overtime->earned_hours}}</span>______________</p>
                    </div>
                    <div class="end">
                        <p class="certified">CERTIFIED BY:</p>
                        <p class="hr">____<span class="underline">{{ $hr->first_name }} {{ strtoupper(substr($hr->middle_name, 0, 1)) }}. {{ $hr->last_name }}</span>____</p>
                        <p class="official">(Authorized Official/Head Official)</p>
                        <p>Prepared by: _____________________________________</p>
                        <p class="admin">(Designated Admin/Attendance Officer)</p>
                        <p>Date Issued: _____________________________________</p>
                        <p>Valid Until: _______________________________________</p>
                    </div>
               </div>
            </div>
            <div class="left-side">
                <div class="align-left">
                    <p>ACTION ON APPLICATION</p>
                    <div class="checkbox"></div><p class="approved">Approved for</p> <div class="days">_______<span class="underline">{{ $overtime->approved_days}}</span>_______ day/s</div>
                    <div class="checkbox2"></div><p class="disapproved">Disapproved due to</p> <div class="reason">__________<span class="underline">{{ $overtime->disapproval_reason}}</span>_______ </div>
                    <div class="line">__________________________________</div>
                    <br>
                    
                </div>
            </div>
        </div>
    </div>
</body>
</html>
