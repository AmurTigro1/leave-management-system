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
            
        </div>
    </div>
</body>
</html>
