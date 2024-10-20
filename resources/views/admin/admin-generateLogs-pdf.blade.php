<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FAVICON -->
    <link href="{{asset('/images/chronolock-small.png' )}}" rel="shortcut icon" />
    <title>Logs Report</title>
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

        .Faculty {
            background-color: red;
            font-weight: bold;
            color: white;
            /* Color for the text "EMPTY" */
        }

        .Student {
            background-color: yellow;
            font-weight: bold;
            /* Color for the text "EMPTY" */
        }

        .Admin, .Dean, .ProgramChair, .Technician, .LabInCharge {
            background-color: #0f0e6b   ;
            color: white;
            font-weight: bold;
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
    <h1 class="header">User Logs Report</h1>
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
                <th>#</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Action</th>
                <th>Date</th>
                <th>Time</th>
                <th>UserType</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @forelse($userLogs as $userLog)
            <tr>
                <td> {{$counter}} </td>
                <td> {{ ucwords($userLog->userID) }} </td>
                <td> {{ ucwords($userLog->firstName)}} {{ucwords($userLog->lastName) }} </td>
                <td> {{$userLog->action}} </td>
                <td>{{ date('F j, Y', strtotime($userLog->date)) }}</td>
                <td>{{ date('h:i A', strtotime($userLog->time)) }}</td>

                @if($userLog->userType == 'Admin')
                <td class="Admin" style="font-size: 15px;">Admin</td>
                @elseif($userLog->userType == 'Dean')
                <td class="Dean" style="font-size: 15px;">Dean</td>
                @elseif($userLog->userType == 'Program Chair')
                <td class="ProgramChair" style="font-size: 15px;">Program Chair</td>
                @elseif($userLog->userType == 'Technician')
                <td class="Technician" style="font-size: 15px;">Technician</td>
                @elseif($userLog->userType == 'Lab-in-Charge')
                <td class="Lab-in-Charge" style="font-size: 15px;">Lab-in-Charge</td>
                @elseif($userLog->userType == 'Faculty')
                <td class="Faculty" style="font-size: 15px;">Faculty</td>
                @elseif($userLog->userType == 'Student')
                <td class="Student" style="font-size: 15px;">Student</td>
                @endif
            </tr>
            @php $counter++; @endphp
            @empty
            <tr>
                <td coltd="7" style="text-align: center;">No records found.</td>
            </tr>
            @endforelse
        </tbody>

    </table>


</body>

</html>