<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- FAVICON -->
    <link href="{{asset('/images/chronolock-small.png' )}}" rel="shortcut icon" />
    <title>Student List Report</title>
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

        .drop {
            background-color: red;
            font-weight: bold;
            /* Color for the text "EMPTY" */
        }

        .irregular {
            background-color: yellow;
            font-weight: bold;
            /* Color for the text "EMPTY" */
        }

        .regular {
            background-color: #b0fc38;
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
    <h1 class="header">Student List Report</h1>
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
                <th>No.</th>
                <th>Student Name</th>
                <th>Student ID</th>
                <th>Program</th>
                <th>Year & Section</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @php $counter = 1; @endphp
            @forelse($studentList as $student)
            <tr>
                <td> {{$counter}} </td>
                <td>{{ ucwords($student->firstName) }} {{ ucwords($student->lastName) }}</td>
                <td>{{$student->idNumber}}</td>
                <td>{{$student->program}}</td>
                <td>{{$student->year}}-{{$student->section}}</td>
                @if($student->status == 'Regular')
                <td class="regular" style="font-size: 15px;">Present</td>
                @elseif($student->status == 'Irregular')
                <td class="irregular" style="font-size: 15px;">Irregular</td>
                @elseif($student->status == 'Drop')
                <td class="drop" style="font-size: 15px;">Drop</td>
                @endif
            </tr>
            @php $counter++; @endphp
            @empty
            <tr>
                <td colspan="6" style="text-align: center;">No records found.</td>
            </tr>
            @endforelse
        </tbody>

    </table>


</body>

</html>