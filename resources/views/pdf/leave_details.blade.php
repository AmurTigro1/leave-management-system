<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $leave->user->name}} - Leave Request</title>
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
                <p class="input-first">{{ $leave->user->department }}</p>
                <p class="input-second">{{ $leave->user->last_name}}</p>
                <p class="input-third">{{ $leave->user->first_name}}</p>
                <p class="input-fourth">
                    @if($leave->user->middle_name)
                        {{ $leave->user->middle_name}}
                        @else
                        None
                    @endif
                </p>
            </div>
            <div class="head-middle-body">
                <p class="first2">3. DATE OF FILING: <span class="underline">{{ \Carbon\Carbon::parse($leave->date_filing)->format('F d, Y') }}</span></p>
                <p class="second2">4. POSITION: <span class="underline">{{ $leave->user->position}}</span></p>
                <p class="third2">5. SALARY FILE: <span class="underline">{{ $leave->salary_file}}</span></p>
            </div>
            <div class="middle-body">
                <p class="first3">6. DETAILS OF APPLICATION</p>
            </div>
            <div class="new-class2">
                <div class="middle-part">
                    <p class="leave-info">6.A TYPE OF LEAVE TO BE AVAILED OF</p>
                    <p class="info"><span class="leave-type">
                        @if($leave->leave_type == 'Vacation Leave') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif</span>Vacation Leave <span class="additional-detail"> (Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></p>
                    <p class="info2"><span class="leave-type">
                        @if($leave->leave_type == 'Mandatory Leave') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif</span>Mandatory/Forced Leave <span class="info-3"> (Sec. 51, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></p>
                    <p class="info2"><span class="leave-type">
                        @if($leave->leave_type == 'Sick Leave') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10">
                      
                        @endif</span>Sick Leave <span class="additional-detail"> (Sec. 43, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></p>
                    <p class="info2"><span class="leave-type">
                        @if($leave->leave_type == 'Maternity Leave') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif</span>Maternity Leave<span class="additional-detail"> (R.A No. 11210/IRR Issued by CSC, DOLE and SSS)</span></p>
                    <p class="info2"><span class="leave-type">
                        @if($leave->leave_type == 'Paternity Leave') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif</span>Paternity Leave<span class="additional-detail"> (R.A 8187/CSC MC No. 71, s. 1998, as amended)</span></p>
                    <p class="info3"><span class="leave-type">
                        @if($leave->leave_type == 'Special Privilege Leave') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif</span>Special Privilege Leave<span class="info-3"> (Sec. 21, Rule XVI, Omnibus Rules Implementing E.O. No. 292)</span></p>
                    <p class="info2"><span class="leave-type">
                        @if($leave->leave_type == 'Solo Parent Leave') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif</span>Solo Parent Leave<span class="additional-detail"> (R.A No. 8972/CSC MC No. 8, s. 2004)</span></p>
                    <p class="info2"><span class="leave-type">
                        @if($leave->leave_type == 'Study Leave') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif</span>Study Leave<span class="additional-detail"> (Sec. 68, Rule XVL, Omnibus Rules Implementing E.O. No. 292)</span></p>
                    <p class="info2"><span class="leave-type">
                        @if($leave->leave_type == '10-Day VAWC Leave') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif</span>10-Day VAWC Leave<span class="additional-detail"> (R.A No. 9262/CSC MC No. 15, s. 2005)</span></p>
                    <p class="info2"><span class="leave-type">
                        @if($leave->leave_type == 'Rehabilitation Privilege') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif</span>Rehabilitation Privilege<span class="info-2"> (Sec. 55, Rule XVL, Omnibus Rules Implementing E.O. No. 292)</span></p>
                    <p class="info2"><span class="leave-type">
                        @if($leave->leave_type == 'Special Leave Benefits for Women Leave') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif</span>Special Leave Benefits for Women<span class="additional-detail"> (R.A No. 9710/CSC MC No. 25, s. 2010)</span></p>
                    <p class="info2"><span class="leave-type">
                        @if($leave->leave_type == 'Special Emergency Leave') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif</span>Special Emergency (Calamity) Leave<span class="additional-detail"> (CSC MC No. 2, s. 2012, as amended)</span></p>
                    <p class="info2"><span class="leave-type">
                        @if($leave->leave_type == 'Adoption Leave ') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif</span>Adoption Leave<span class="additional-detail"> (R.A No. 8552)</span></p>
                    <p class="other-option">Others:</p>
                    <p class="other-opt">
                        @if($leave->leave_type == 'Others')
                            @php
                                $details = json_decode($leave->leave_details, true);
                            @endphp
                            <span class="underline">{{ Str::limit($details[1], 65, '...') }}</span>
                        @elseif($leave->leave_type === 'Other Purposes')
                            <span class="underline">Other Purposes</span>
                        @else
                            ______________________________
                        @endif
                    </p>                
                </div>
                <div class="middle-part2">
                    <p class="leave-info2">6.B DETAILS OF LEAVE</p>
                        @php
                            //decodes the js data to display the key and value
                            $leave_details = json_decode($leave->leave_details, true);
                            $key = key($leave_details); 
                            $value = reset($leave_details);
    
                            //for limiting words in display to avoid overlapping other information
                            use Illuminate\Support\Str;
                        @endphp
                    <p class="right-side">In case of Vacation/Special Privilege Leave:</p>
                    <p class="info2">
                        <span class="leave-type">
                            @if($key == "Within the Philippines")
                                <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                            @endif
                        </span>
                        Within the Philippines:
                        <span class="info-1">
                            @if($key == "Within the Philippines")
                                <span class="underline">
                                    {{ Str::limit($value, 34, '...') }} 
                                </span>
                            @else
                                ______________________________
                            @endif
                        </span>
                    </p>
    
                    <p class="info2"><span class="leave-type">
                        @if($key == "Abroad")
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif
                        </span>Abroad (Specify):<span class="info-1">
                        @if($key == "Abroad")
                            <span class="underline">
                                {{ Str::limit($value, 41, '...') }} 
                            </span>
                        @else
                        ___________________________________
                        @endif
                        </span>
                    </p>
                    <br>
                    <p class="right-side">In case of Sick Leave:</p>
                    <p class="info2"><span class="leave-type">
                        @if($key == "In Hospital")
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif
                        </span>In Hospital (Specify Illness):<span class="info-1">
                        @if($key == "In Hospital")
                            <span class="underline">
                                {{ Str::limit($value, 27, '...') }} 
                            </span>
                        @else
                        _________________________
                        @endif
                        </span>
                    </p>
                    <p class="info2"><span class="leave-type">
                        @if($key == "Out Patient")
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                        @endif
                        </span>Out Patient (Specify Illness):<span class="info-1">
                        @if($key == "Out Patient")
                            <span class="underline">
                                {{ Str::limit($value, 31, '...') }} 
                            </span>
                        @else
                        _________________________
                        @endif
                        </span>
                    </p>
                    <br>
                    <p class="right-side">In case of Study Leave:</p>
                    @php
                        $details = json_decode($leave->leave_details, true);
                    @endphp
                    
                    <p class="info2-study">
                        <span class="study-leave">
                            @if($leave->leave_type == "Study Leave" && is_array($details) && in_array("Completion of Master's Degree", $details))
                                <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                            @endif
                        </span> Completion of Master's Degree
                    </p>
                    
                    <p class="info2">
                        <span class="study-leave">
                            @if($leave->leave_type == "Study Leave" && is_array($details) && in_array("BAR Review", $details))
                                <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                            @endif
                        </span> BAR/Board Examination Review
                    </p>
                    <p class="right-side2">Other purpose:</p>
                    <p class="info2-study">
                        <span class="study-leave">
                            @if($leave->leave_type == "Other Purposes" && is_array($details) && in_array("Monetization of Leave Credits", $details))
                                <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                            @endif
                        </span> Monetization of Leave Credits
                    </p>
                    
                    <p class="info2">
                        <span class="study-leave">
                            @if($leave->leave_type == "Other Purposes" && is_array($details) && in_array("Terminal Leave", $details))
                                <img src="{{ public_path('img/check.jpg') }}" width="18" height="10"> 
                            @endif
                        </span> Terminal Leave
                    </p>
                    <span></span>
                </div>
            </div>
            <div class="bottom-part">
                <p class="leave-info2">6.C NUMBER OF WORKING DAYS APPLIED FOR</p>
                <p class="fill-in">_________<span class="underline">{{ $leave->days_applied }}</span>_________</p> 
                <p class="inclusive">INCLUSIVE DATES</p>
                <p class="fill-in-2"><span class="capitalize">{{ \Carbon\Carbon::parse($leave->start_date)->format('F d, Y') }} - {{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}</span></p>
            </div>
            <div class="middle-part2">
                <p class="commutation-part">6.D COMMUTATION</p>
                    <p class="info2">
                        <span class="commutation-checkbox">
                            @if($leave->commutation == 0) 
                                <img src="{{ public_path('img/check.jpg') }}" width="18" height="10">
                            @endif
                        </span>Not requested
                    </p>
                    <p class="info2">
                        <span class="commutation-checkbox">
                            @if($leave->commutation == 1) 
                                <img src="{{ public_path('img/check.jpg') }}" width="18" height="10">
                            @endif
                        </span>Requested
                    </p>

                    <p class="fill-in2">
                      @if ($leave->signature && file_exists(storage_path('app/public/' . $leave->signature)))
                        <div class="image-display">
                            <img src="{{ storage_path('app/public/' . $leave->signature) }}" alt="Signature"
                                style="width: 120px; height: 80px; margin-top: -70px;">
                        </div>
                    @else
                        ______________________________
                    @endif
                    </p>
                    <p class="sign">(Signature of Applicant)</p>                    
                <span></span>
            </div>
            <div class="bottom-part2">
                <p class="first3">7. DETAILS OF ACTION ON APPLICATION</p>
            </div>
            <div class="last-part">
                <p class="leave-info2">7.A CERTIFICATION OF LEAVE CREDITS</p>
                <p class="last-part-info">As of ________<span class="underline">{{ \Carbon\Carbon::parse($leave->user->certification_leave)->format('F  Y') }}</span>________</p>
                <div class="last-table">
                    <table>
                        <thead>
                            <tr>
                                <th></th>
                                <th>Vacation Leave</th>
                                <th>Sick Leave</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Total Earned</td>
                                <td>{{ $leave->vacation_balance_before }}</td>
                                {{-- <td> {{ $leave->vacation_balance_before + $leave->days_applied }} </td> --}}
                                <td>{{ $leave->sick_balance_before }}</td>
                            </tr>
                            <tr>
                                <td>Less this application</td>
                                @if(in_array($leave->leave_type, ['Vacation Leave', 'Mandatory Leave','Special Privilege Leave']))
                                    <td>{{ $leave->days_applied }}</td>
                                @else
                                    <td>0</td>
                                @endif  
                
                                @if($leave->leave_type == 'Sick Leave')
                                    <td>{{ $leave->days_applied }}</td>
                                @else
                                    <td>0</td>
                                @endif
                            </tr>
                            <tr>
                                <td>Balance</td>
                                <td>
                                    @if(in_array($leave->leave_type, ['Vacation Leave', 'Mandatory Leave','Special Privilege Leave']))
                                        {{ $leave->vacation_balance_before - $leave->days_applied }}
                                       
                                    @else
                                        {{ $leave->vacation_balance_before }}
                                    @endif
                                </td>
                                <td>
                                    @if($leave->leave_type == 'Sick Leave')
                                        {{-- {{ $leave->sick_balance_before - $leave->days_applied }} --}}
                                        {{ $leave->sick_balance_before  - $leave->days_applied }}
                                    @else
                                        {{ $leave->sick_balance_before }}
                                    @endif
                                </td>
                            </tr>
                        </tbody>                        
                    </table>                    
                </div>                
                @if($leave->days_applied > 10)              
                    @foreach ($officials as $official)
                        <p class="last-sign">________<span class="underline">{{ $official->hr_name }}</span>________</p>
                    @endforeach
                @else
                        <p class="last-sign">________<span class="underline">{{ $hr->first_name}} {{ strtoupper(substr($hr->middle_name, 0, 1)) }}. {{ $hr->last_name}}</span>________</p>
                @endif

                <p class="text-last">HRMO</p>
            </div>
            <div class="last-part3">
                <p class="leave-info2">7.B RECOMMENDATION</p>
                <p class="disapproval-reason">
                    <span class="commutation-checkbox">
                        @if($leave->hr_status == 'approved') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10">
                        @endif
                    </span>For Approval
                </p>
                <p class="info2">
                    <span class="commutation-checkbox">
                        @if($leave->hr_status == 'rejected') 
                            <img src="{{ public_path('img/check.jpg') }}" width="18" height="10">
                        @endif
                    </span>For disapproval due to ____________________
                </p>
                <p class="last-line">____________________________________</p>
                <p class="last-line">____________________________________</p>
                <p class="last-line">____________________________________</p>
                 
                @if($leave->days_applied > 10)
                    @foreach ($officials as $official)       
                        <p class="last-sign2">________<span class="underline">{{ $official->hr_name}}</span>________</p>
                    @endforeach
                @else
                    <p class="last-sign2">________<span class="underline">{{ $hr->first_name}} {{ strtoupper(substr($hr->middle_name, 0, 1)) }}. {{ $hr->last_name}}</span>________</p>
                @endif
            
                <p class="text-last">Authorized Officer</p>
                <span></span>
            </div>
            <div class="final-part">
                <p class="leave-info2">7.C APPROVED FOR</p>
                <p class="final-disapproval">7.D DISAPPROVED DUE TO:</p>
                <div class="final-list">
                    <p class="list01">____<span class="underline">{{ $leave->approved_days_with_pay}}</span>____ days with pay</p>
                    <p class="list01">____<span class="underline">{{ $leave->approved_days_without_pay}}</span>____ days without pay</p>
                    <p class="list01">_________ others (specify)</p>
                </div>
                <div class="final-list2">
                    <p class="list01">______________________________</p>
                    <p class="list01">______________________________</p>
                    <p class="list01">______________________________</p>
                </div>
                @if($leave->days_applied > 10)
                    @foreach ($officials as $official)
                        <p class="final-sign">____________<span class="underline">{{ $official->supervisor_name}}</span>____________</p>
                    @endforeach
                @else
                    <p class="final-sign">____________<span class="underline">{{ $supervisor->first_name}} {{ strtoupper(substr($supervisor->middle_name, 0, 1)) }}. {{ $supervisor->last_name}}</span>____________</p>
                @endif
                <p class="text-last">(Authorized Official)</p> 
            </div>
        </div>
    </div>
</body>
</html>
