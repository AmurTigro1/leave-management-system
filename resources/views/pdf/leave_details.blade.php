<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave Request Form</title>
    <link rel="stylesheet" href="{{ public_path('pdf/pdf_design.css') }}">
</head>
<body> 
    <div class="pdf-body">
        <div class="overall-header">
            <div class="text-header">
                <div class="header1">
                    <p class="uppertext">Civil Service Form No.6</p>
                    <p class="uppertext">Revised 2020</p>
                </div>
                <div class="header2">
                    <p class="uppertext2">ANNEX A</p>
                    <p class="uppertext3">Stamp of Date of Receipt</p>
                </div>
            </div>
            <div class="image-text-header">
                <div class="header-image">
                    <img src="{{ public_path('img/dilg-main.png') }}" style="width: 65px; height:auto" alt="">
                </div>
                <div class="header-text">
                    <p class="republic">Republic of the Philippines</p>
                    <p class="dilg-title"><strong>DEPARTMENT OF THE INTERIOR AND LOCAL GOVERNMENT</strong> </p>
                    <p class="region">Region VII</p>
                </div>
                <div class="last-header">
                    <p>APPLICATION FOR LEAVE</p>
                </div>
            </div>
        </div>
        <div class="overall-body">
            <div class="head-body">
                <p class="first">1. OFFICE/DEPARTMENT</p>
                <p class="second">2. NAME    (LAST)</p>
                <p class="third">(First)</p>
                <p class="fourth">(Middle)</p>
                <p class="input-first">{{ $leave->department }}</p>
                <p class="input-second">Salera</p>
                <p class="input-third">{{ $leave->user->name}}</p>
                <p class="input-fourth">Sab</p>
            </div>
            <div class="head-middle-body">
                <p class="first2">3. DATE OF FILING: <span class="underline">{{ \Carbon\Carbon::parse($leave->date_filing)->format('F d, Y') }}</span></p>
                <p class="second2">4. POSITION: <span class="underline">{{ $leave->position}}</span></p>
                <p class="third2">5. SALARY FILE: <span class="underline">{{ $leave->salary_file}}</span></p>
            </div>
            <div class="middle-body">
                <p class="first3">6. DETAILS OF APPLICATION</p>
            </div>
            <div class="middle-part">
                <p class="leave-info">6.A TYPE OF LEAVE TO BE AVAILED OF</p>
                <p class="info"><span class="checkbox">___</span>Vacation Leave <span class="info-1"> (Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></p>
                <p class="info2"><span class="checkbox">___</span>Mandatory/Forced Leave <span class="info-3"> (Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></p>
                <p class="info2"><span class="checkbox">___</span>Sick Leave <span class="info-1"> (Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></p>
                <p class="info2"><span class="checkbox">___</span>Maternity Leave<span class="info-1"> (R.A No. 11210/IRR Issued by CSC, DOLE and SSS)</span></p>
                <p class="info2"><span class="checkbox">___</span>Paternity Leave<span class="info-1"> (R.A 8187/CSC MC No. 71, s. 1998, as amended)</span></p>
                <p class="info3"><span class="checkbox">___</span>Special Privilege Leave<span class="info-3"> (Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></p>
                <p class="info2"><span class="checkbox">___</span>Solo Parent Leave<span class="info-1"> (R.A No. 8972/CSC MC No. 8, s. 2004)</span></p>
                <p class="info2"><span class="checkbox">___</span>Study Leave<span class="info-1"> (Sec. 68, Rule XVL, Omnibus Rules Implementing E.O. No. 292)</span></p>
                <p class="info2"><span class="checkbox">___</span>10-Day VAWC Leave<span class="info-1"> (R.A No. 9262/CSC MC No. 15, s. 2005)</span></p>
                <p class="info2"><span class="checkbox">___</span>Rehabilitation Privilege<span class="info-2"> (Sec. 55, Rule XVL, Omnibus Rules Implementing E.O. No. 292)</span></p>
                <p class="info2"><span class="checkbox">___</span>Special Leave Benefits for Women<span class="info-1"> (R.A No. 9710/CSC MC No. 25, s. 2010)</span></p>
                <p class="info2"><span class="checkbox">___</span>Special Emergency (Calamity) Leave<span class="info-1"> (CSC MC No. 2, s. 2012, as amended)</span></p>
                <p class="info2"><span class="checkbox">___</span>Adoption Leave<span class="info-1"> (R.A No. 8552)</span></p>
                <p class="other-option">Others:</p>
                <p class="other-opt">______________________________</p>
            </div>
            <div class="middle-part2">
                <p class="leave-info2">6.B DETAILS OF LEAVE</p>
                <p class="right-side">In case of Vacation/Special Privilege Leave:</p>
                <p class="info2"><span class="checkbox">___</span>Within the Philippines<span class="info-1">______________________________</span></p>
                <p class="info2"><span class="checkbox">___</span>Abroad (Specify)<span class="info-new">______________________________</span></p>
                <br>
                <p class="right-side">In case of Sick Leave:</p>
                <p class="info2"><span class="checkbox">___</span>In Hospital (Specify illness)<span class="info-1">________________________</span></p>
                <p class="info2"><span class="checkbox">___</span>Out Patient (Specify illness)<span class="info-1">________________________</span></p>
                <br>
                <p class="right-side">In case of Study Leave:</p>
                <p class="info2"><span class="checkbox">___</span>Completion of Master's Degree</p>
                <p class="info2"><span class="checkbox">___</span>BAR/Board Examination Review</p>
                <p class="right-side2">Other purpose:</p>
                <p class="info2"><span class="checkbox">___</span>Monetization of Leave Credits</p>
                <p class="info2"><span class="checkbox">___</span>Terminal Leave</p>
            </div>
            <div class="bottom-part">
                <p class="leave-info2">6.C NUMBER OF WORKING DAYS APPLIED FOR</p>
                <p class="fill-in">___________________________</p>
                <p class="inclusive">INCLUSIVE DATES</p>
                <p class="fill-in-2">___________________________</p>
            </div>
            <div class="middle-part2">
                <p class="leave-info2">6.D COMMUTATION</p>
                <p class="info2"><span class="checkbox">___</span>Not requested</p>
                <p class="info2"><span class="checkbox">___</span>Requested</p>
                <p class="fill-in2">________________________________</p>
                <p class="sign">(Signature of Application)</p>
            </div>
            <div class="bottom-part2">
                <p class="first3">7. DETAILS OF ACTION ON APPLICATION</p>
            </div>
            <div class="last-part">
                <p class="leave-info2">7.A CERTIFICATION OF LEAVE CREDITS</p>
                <p class="last-part-info">As of ________________________</p>
                <div class="last-table">
                    <div class="column">
                        <p class="row-first"></p>
                        <p class="row">Total Eearned</p>
                        <p class="row">Less this application</p>
                        <p class="row">Balance</p>
                    </div>
                    <div class="column2">
                        <p class="row1">Vacation Leave</p>
                        <p class="row-first"></p>
                        <p class="row-first"></p>
                        <p class="row-first"></p>
                    </div>
                    <div class="column3">
                        <p class="row1">Sick Leave</p>
                        <p class="row-first"></p>
                        <p class="row-first"></p>
                        <p class="row-first"></p>
                    </div>
                </div>
                <p class="last-sign">______________________________________________</p>
                <p class="text-last">HRMO</p>
            </div>
            <div class="last-part3">
                <p class="leave-info2">7.B RECOMMENDATION</p>
                <p class="info2"><span class="checkbox">___</span>For Approval</p>
                <p class="info2"><span class="checkbox">___</span>For disapproval due to<span class="info-1">________________________</span></p>
                <p class="last-line">____________________________________</p>
                <p class="last-line">____________________________________</p>
                <p class="last-line">____________________________________</p>
                <p class="last-sign2">__________________________________</p>
                <p class="text-last">Authorized Officer</p>
            </div>
            <div class="final-part">
                <p class="leave-info2">7.C APPROVED FOR</p>
                <p class="final-disapproval">7.D DISAPPROVED DUE TO:</p>
                <div class="final-list">
                    <p class="list01">_________ days with pay</p>
                    <p class="list01">_________ days without pay</p>
                    <p class="list01">_________ others (specify)</p>
                </div>
                <div class="final-list2">
                    <p class="list01">______________________________</p>
                    <p class="list01">______________________________</p>
                    <p class="list01">______________________________</p>
                </div>
                <p class="final-sign">______________________________________________</p>
                <p class="text-last">(Authorized Official)</p>
            </div>
        </div>
    </div>
</body>
</html>
