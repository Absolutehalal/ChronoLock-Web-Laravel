<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERP Schedule PDF</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th,
        td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .empty {
            color: red;
            /* Color for the text "EMPTY" */
        }
        .with-schedule {
            background-color: #f2f2f2;
        }
        .CSPCLogo {
            margin-right: 20px;
            position: absolute;
            top: 8px;
            left: 16px;
        }
        .CSPCLogo img {
            max-width: 100px;
            /* Adjust the size of the logo as needed */
            height: auto;
        }
        .CCSLogo {
            margin-left: 20px;
            position: absolute;
            top: 8px;
            right: 16px;
        }
        .CCSLogo img {
            max-width: 100px;
            /* Adjust the size of the logo as needed */
            height: auto;
        }
        .header {
            position: 'absolute';
            text-align: center;
            margin-top: 3%;
        }
        .schedule-container {
            border: 1px solid #000;
            border-radius: 5px;
            padding: 3px;
            margin: 3px;
            background-color: #00ff12;
            box-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1);
        }
        .schedule-header {
            /* font-size: 20px; */
            font-weight: bold;
        }
        .schedule-container div {
            margin-bottom: 4px;
            font-size: 14px;
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
    <br>

    <hr>

    <table style="margin-top: 2%; width: 100%;">
        <thead>
            <tr>
                
                @foreach(['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'] as $day)
                <th style="background-color: #cccc;">{{ $day }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($formattedSchedules as $instructor => $days)
            <tr>
                
                @foreach(['MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT', 'SUN'] as $day)
                <td class="{{ isset($formattedSchedules[$day]) ? 'with-schedule' : '' }}" style="font-size: 12px;">
                    @if(isset($formattedSchedules[$day]))
                    @foreach($formattedSchedules[$day] as $schedules)
                    @foreach($schedules as $schedule)
                    <div class="schedule-container">
                        <div class="schedule-header">{{ $schedule['instructor']  }}</div>
                        <div>{{ $schedule['time'] }}</div>
                        <div>{{ $schedule['course'] }}</div>
                        <div>{{ $schedule['programYearSection'] }}</div>
                    </div>
                    @endforeach
                    @endforeach
                    @endif
                </td>
                @endforeach
            </tr>
            @endforeach
        </tbody>
    </table>


</body>

</html>