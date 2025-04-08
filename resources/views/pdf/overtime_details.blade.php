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
                        <p>Name </p> <div class="info"><span class="underline">{{ $overtime->user->first_name}} {{ strtoupper(substr($overtime->user->middle_name, 0, 1)) }}. {{ $overtime->user->last_name}}</span>_____________________</div>
                        <p>Signature </p> <div class="info2">___________________________________</div>
                        <p>Position </p> <div class="info3"><span class="underline">{{ $overtime->user->position}}</span>_____________________________</div>
                        <p>Office/Division </p> </p> <div class="info4"><span class="underline">{{ $overtime->user->department}}</span>___________________</div>
                        <p>Date of Filing </p> <div class="info5"><span class="underline">{{ \Carbon\Carbon::parse($overtime->date_filed)->format('F d, Y') }}</span>_____________________</div>
                        <p>No. of working hours applied for </p> <div class="info6">__<span class="underline">{{ $overtime->working_hours_applied}} hours</span>___________</div>
                        <p>Inclusive Date/s </p> <div class="info7"><span class="underline">{{ \Carbon\Carbon::parse($overtime->inclusive_date_start)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($overtime->inclusive_date_end)->format('F d, Y') }}</span>_______</div>
                    </div>
                    <div class="middle">
                        <p class="cert">CERTIFICATION OF COMPENSATORY CREDITS (COC)</p>
                        <p class="as-of">As of _______<span class="underline">{{ \Carbon\Carbon::parse($overtime->date_filed)->format('F') }}, {{ \Carbon\Carbon::parse($overtime->date_filed)->format('d') }}</span>_______, 20<span class="underline">{{ \Carbon\Carbon::parse($overtime->date_filed)->format('y') }}</span>.</p>
                        <p class="sub">(Month)</p>
                        <p class="num">Number of hours earned: _________<span class="underline">{{ $overtime->earned_hours}} hours</span>__________</p>
                    </div>
                    <div class="end">
                        <p class="certified">CERTIFIED BY:</p>
                        <p class="hr">____<span class="underline">{{ $hr->first_name }} {{ strtoupper(substr($hr->middle_name, 0, 1)) }}. {{ $hr->last_name }}</span>____</p>
                        <p class="official">(Authorized Official/Head of Office)</p>
                        <p>Prepared by: ___________________________________</p>
                        <p class="admin">(Designated Admin/Attendance Officer)</p>
                        <p>Date Issued: ___________________________________</p>
                        <p>Valid Until: _____________________________________</p>
                    </div>
               </div>
            </div>
            <div class="left-side">
                <div class="align-left">
                    <p>ACTION ON APPLICATION</p>
                    <div class="checkbox">
                        @if($overtime->status == 'approved')
                            <img src="{{ public_path('img/check.jpg') }}" width="23" height="15" class="image-check">  
                        @endif  
                    </div><p class="approved">Approved for</p> <div class="days">_______<span class="underline">{{ $overtime->approved_days}}</span>_______ day/s</div>
                    <div class="checkbox2">
                        @if($overtime->status == 'rejected')
                            <img src="{{ public_path('img/check.jpg') }}" width="23" height="15" class="image-check">  
                        @endif  
                    </div>
                        <p class="disapproved">Disapproved due to</p> <div class="reason">_________________</div>
                        <div class="line-empty">__________________________________</div>
                    <br>
                    <p class="supervisor"><span class="underline">{{ $supervisor->first_name }} {{ strtoupper(substr($supervisor->middle_name, 0, 1)) }}. {{ $supervisor->last_name }}</span></p>
                    <p class="official">(Authorized Official/Head of Office)</p>
                    <br>
                    <p class="note-list">Notes:</p>
                    <p class="notes">1. <span class="note-info">The CTO may be availed of in blocks of four (4) or <span class="note-1">eight (8) hours.</span></span></p>
                    <p class="notes">2. <span class="note-info">The employee may use the CTO continously up to a <span class="note-1">maximum of five (5) consecutive days per single <span class="note-1">availment, or on staggered basis within the year.</span></span></span></p>
                    <p class="notes">3. <span class="note-info">The employee must first obtain approval from the head <span class="note-1">of office/authorized official regarding the schedule of <span class="note-1">availment of CTO.</span></span></span></p>
                    <p class="notes">4. <span class="note-info">Attach supporting document (e.g, Department Order, <span class="note-1">Approved Request for Authority to render Overtime <span class="note-1">Service)</span></span></span></p>
                    <div class="last-part"></div>
                </div>
            </div>
            <div class="bottom-part">
                <table>
                    <thead>
                        <tr>
                            <th class="th-1">Total No. of Hours of <br>
                                Earned COCs <br>
                                <span class="including">(including COCs earned <br>
                                    in previous month/s)</span></th>
                            <th>Date of CTO</th>
                            <th>Used COCs</th>
                            <th>Remaining COCs</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $overtime->earned_hours}} hours</td>
                            <td>{{ \Carbon\Carbon::parse($overtime->date_filed)->format('F d, Y') }}</td>
                            <td>{{ $overtime->working_hours_applied}} hours</td>
                            <td>{{ $overtime->user->overtime_balance - $overtime->working_hours_applied}} hours</td>
                            <td></td>
                        </tr>
                        <tr>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                        </tr>
                        <tr>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                        </tr>
                        <tr>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                        </tr>
                        <tr>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                        </tr>
                        <tr>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                            <td><div class="empty"></div></td>
                        </tr>
                    </tbody>
                </table>
            </div>        
            <br>
            <div class="final-part">
                <p class="prep">Prepared by:</p>
                <p class="rec">Received by:</p> 
                <div class="left-sign">
                    <p>___________________________</p>
                    <p class="admins">Designated Admin/Attendance Officer</p> 
                </div>
                <div class="right-sign">
                    <p>___________________________</p>
                    <p class="admins2">Personnel Section/Division</p>
                </div>
            </div>    
        </div>
    </div>
</body>
</html>
