<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
      
        .empty {
            color: red; /* Color for the text "EMPTY" */
        }  
        .with-schedule {
            background-color: #b0fc38;
        }
        .CSPCLogo {
            margin-right: 20px;
            position: absolute;
            top: 8px;
            left: 16px;
        }
        .CSPCLogo img {
            max-width: 100px; /* Adjust the size of the logo as needed */
            height: auto;
        }
        .CCSLogo {
            margin-left: 20px;
            position: absolute;
            top: 8px;
            right: 16px;
        }
        .CCSLogo img {
            max-width: 100px; /* Adjust the size of the logo as needed */
            height: auto;
        }
        .header {
            position: 'absolute';
            text-align: center;
            margin-top: 3%;
        }
    </style>
</head>
<body>
 <h1 class="header">ERP Laboratory Schedules</h1>
<div class="CSPCLogo">
<img src="data:image/png;base64,{{ $imageCSPC }}" alt="CSPCLogo">

            
</div>
<div class="CCSLogo">
    <img src="data:image/png;base64,{{ $imageCCS }}" alt="CCSLogo">
</div>
     
       

    <table style="margin-top: 4%;">
        <thead>
            <tr>
                <th>Instructor</th>
                @foreach(['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'] as $day)
                    <th>{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($formattedSchedules as $instructor => $days)
                <tr>
                    <td>{{ $instructor }}</td>
                    @foreach(['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'] as $day)
                    <td class="{{ isset($days[$day]) ? 'with-schedule' : '' }}">
                            @if(isset($days[$day]))
                                @foreach($days[$day] as $schedule)
                                    <div>{{ $schedule['time'] }}</div>
                                    <div>{{ $schedule['course'] }}</div>
                                    <div>{{ $schedule['programYearSection'] }}</div>
                                    <br>
                                @endforeach
                            @else
                                <span class="empty">EMPTY</span>
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

    
</body>
</html>
