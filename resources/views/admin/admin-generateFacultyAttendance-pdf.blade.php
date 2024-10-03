<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Attendance Report</title>
    <!-- FAVICON -->
    <link href="{{asset('/images/chronolock-small.png' )}}" rel="shortcut icon" />
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
    <h1 class="header">Faculty Attendance Report</h1>
    <div class="CSPCLogo">
        <img src="data:image/png;base64,{{ $imageCSPC }}" alt="CSPCLogo">


    </div>
    <div class="CCSLogo">
        <img src="data:image/png;base64,{{ $imageCCS }}" alt="CCSLogo">
    </div>
    <br>

    <hr>
    <table style="margin-top: 4%;">
        <thead>
            <tr>


                <th>NO</th>
                <th>Name</th>
                <th>User ID</th>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>Program & Section</th>
                <th>Date</th>
                <th>Time</th>
                <th>Remark</th>



            </tr>
        </thead>
        <tbody>
            @foreach($facultyAttendances as $faculty)
            @csrf
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ ucwords($faculty->firstName) }} {{ ucwords($faculty->lastName ) }}</td>
                <td>{{ ucwords($faculty->idNumber) }}</td>
                <td>{{ strtoupper($faculty->courseCode) }}</td>
                <td>{{ ucwords($faculty->courseName) }}</td>
                <td>{{ $faculty->program }} - {{ $faculty->year }}{{ $faculty->section }}</td>
                <td>{{ $faculty->formatted_date }}</td>
                <td>{{ $faculty->formatted_time }}</td>
                @if($faculty->remark == 'Present')
                <td class="{{ 'present' }}" style="font-size: 15px;">Present</td>
                @elseif($faculty->remark == 'Absent')
                <td class="{{ 'absent' }}" style="font-size: 15px;">Absent</td>
                @elseif($faculty->remark == 'Late')
                <td class="{{ 'late' }}" style="font-size: 15px;">Late</td>
                @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>


</body>

</html>