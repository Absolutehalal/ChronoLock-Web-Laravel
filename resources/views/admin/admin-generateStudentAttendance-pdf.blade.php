<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FAVICON -->
    <link href="{{asset('/images/chronolock-small.png' )}}" rel="shortcut icon" />
    <title>Student Attendance Report</title>
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

        .absent {
            background-color: red;
            /* Color for the text "EMPTY" */
        }

        .late {
            background-color: yellow;
            /* Color for the text "EMPTY" */
        }

        .present {
            background-color: #b0fc38;
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
    </style>
</head>

<body>
    <h1 class="header">Student Attendance Report</h1>
    <div class="CSPCLogo">
        <img src="data:image/png;base64,{{ $imageCSPC }}" alt="CSPCLogo">


    </div>
    <div class="CCSLogo">
        <img src="data:image/png;base64,{{ $imageCCS }}" alt="CCSLogo">
    </div>
    <br>

    <hr>
    <table style="margin-top: 2%;">
        <thead>
            <tr>


                <th>S. NO</th>
                <th>Name</th>
                <th>User ID</th>
                <th>Course Name</th>
                <th>Program</th>
                <th>Year & Section</th>
                <th>Date</th>
                <th>Time</th>
                <th>Remark</th>



            </tr>
        </thead>
        <tbody>
            @foreach($studentAttendances as $student)
            @csrf
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ ucwords($student->firstName) }} {{ ucwords($student->lastName) }}</td>
                <td>{{ ucwords($student->idNumber) }}</td>
                <td>{{ $student->courseName }}</td>
                <td>{{ $student->program }}</td>
                <td>{{ $student->year }}-{{ $student->section }}</td>
                <td>{{ $student->formatted_date }}</td>
                <td>{{ $student->formatted_time }}</td>
                @if($student->remark == 'Present')
                <td class="{{ 'present' }}" style="font-size: 15px;">Present</td>
                @elseif($student->remark == 'Absent')
                <td class="{{ 'absent' }}" style="font-size: 15px;">Absent</td>
                @elseif($student->remark == 'Late')
                <td class="{{ 'late' }}" style="font-size: 15px;">Late</td>
                @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


</body>

</html>