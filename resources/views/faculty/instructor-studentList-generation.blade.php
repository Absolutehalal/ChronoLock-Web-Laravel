<!DOCTYPE html>

<!--
 // WEBSITE: https://themefisher.com
 // TWITTER: https://twitter.com/themefisher
 // FACEBOOK: https://www.facebook.com/themefisher
 // GITHUB: https://github.com/themefisher/
-->

<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />

  <title>ChronoLock Faculty-Student List Generation</title>

  @include('head')
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
  <script>
    NProgress.configure({
      showSpinner: false
    });
    NProgress.start();
  </script>

  @include('faculty.instructorSideNav')
  <!-- ====================================
      ——— PAGE WRAPPER
      ===================================== -->

  <div class="page-wrapper">
    <!-- Header -->
    @include('header')

    <!-- ====================================
        ——— CONTENT WRAPPER
        ===================================== -->
    <div class="content-wrapper">
      <div class="content">


        <div class="d-flex justify-content-between align-items-center">
          <!-- Navigation -->
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item"><a href="{{ route('instructorIndex') }}">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="{{ route('facultyStudentListGeneration') }}">Report Generation</a></li>
              <li class="breadcrumb-item active"><a href="{{ route('facultyStudentListGeneration') }}">Student List</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>

        <!-- DROPRDOWN NAV -->

        <div class="row">
          <div class="col-xl-9 col-md-9">

            <form action="{{ url('/faculty-student-list-generation') }}" method="GET">

              <div class="dropdown d-inline-block">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="studentProgramsButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                  <i class="mdi mdi-alpha-c-box"></i> Program
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="studentProgramsButton">
                  @forelse ($studentPrograms as $studentPrograms)
                  <a class="dropdown-item course-item @if ($studentPrograms == $studentPrograms->program) active @endif" href="#" data-value="{{ $studentPrograms->program }}">
                    {{ $studentPrograms->program }}
                  </a>
                  @empty
                  <a class="dropdown-item" data-value="None" href="#">
                    None
                  </a>
                  @endforelse
                </div>
                <input type="hidden" name="selected_programs" id="selected_programs" value="">
              </div>

              <div class="dropdown d-inline-block">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="studentYearsButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                  <i class="mdi mdi-developer-board"></i> Year & Section
                </button>
                <div class="dropdown-menu" aria-labelledby="studentYearsButton">
                  @forelse ($studentYears as $studentYear)
                  <a class="dropdown-item year-item @if ($selected_years == $studentYear->year . '-' . $studentYear->section) active @endif" href="#" data-value="{{ $studentYear->year }}-{{ $studentYear->section }}">
                    {{ $studentYear->year }}-{{ $studentYear->section }}
                  </a>
                  @empty
                  <a class="dropdown-item" data-value="None" href="#">
                    None
                  </a>
                  @endforelse
                </div>
                <input type="hidden" name="selected_years" id="selected_year" value="">
              </div>

              <div class="dropdown d-inline-block">
                <button class="btn btn-primary btn-sm dropdown-toggle fw-bold" type="button" id="studentStatusDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                  <i class="mdi mdi-alpha-s-box"></i>
                  Status
                </button>
                <div class="dropdown-menu" aria-labelledby="studentStatusDropdown">
                  @forelse($studentStatus as $studentStatus)
                  @csrf
                  <a class="dropdown-item status-item @if ($student_status == $studentStatus->status) active @endif" data-value="{{ $studentStatus->status }}" href="#">
                    {{ $studentStatus->status }}
                  </a>
                  @empty
                  <button class="dropdown-item" data-value="NONE" type="button">
                    NONE
                  </button>
                  @endforelse
                </div>
                <input type="hidden" name="student_status" id="student_status">
              </div>


          </div>
          <!-- END -->



          <div class="col-xl-3 col-md-3 d-flex justify-content-end">

            <div class="dropdown d-inline-block mb-3 mr-1">
              <button class="btn btn-danger btn-sm fw-bold" type="submit">
                <i class="mdi mdi-sort"></i> Filter
              </button>
            </div>
            </form>

            <form action="{{ url('/faculty-student-list-generation')}}" method="GET">
              <div class="dropdown d-inline-block mb-3 ">
                <button class="btn btn-warning btn-sm fw-bold" type="submit">
                  <i class="mdi mdi-alpha-r-box"></i>
                  RESET
                </button>
              </div>
            </form>

          </div>
        </div>

        <!-- END -->


        <!-- Student List Table -->
        <div class="card card-default shadow">
          <div class="card-header">
            <h1>Student List</h1>
            <div class="justify-content-end">

              <div class="dropdown d-inline-block mb-3">
                <button data-toggle="tooltip" title="PDF"  class="btn btn-outline-dark btn-sm fw-bold"
                onclick='window.location = "{{ route("facultyPreviewStudentListPDF", ["selected_programs" => $selected_programs, "selected_years" => $selected_years, "student_status" => $student_status]) }}"' 
                  type="button">
                  <i class="mdi mdi-feature-search"></i>
                  PDF
                </button>
              </div>

            </div>
          </div>
          <div class="card-body ">
            <table id="studentListTable" class="table table-bordered table-hover no-wrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Student Name</th>
                  <th>Student ID</th>
                  <th>Program</th>
                  <th>Year & Section</th>
                  <th>Status</th>

                </tr>
              </thead>
              <tbody>
                @php $counter = 1; @endphp
                @foreach ($studentList as $student)
                <tr>
                  <td> {{$counter}} </td>
                  <td>{{ ucwords($student->firstName) }} {{ ucwords($student->lastName) }}</td>
                  <td>{{$student->idNumber}}</td>
                  <td>{{$student->program}}</td>
                  <td>{{$student->year}}-{{$student->section}}</td>
                  <td>
                    @if($student->status == 'Regular')
                    <span class="badge badge-success">Regular</span>
                    @elseif($student->status == 'Irregular')
                    <span class="badge badge-warning">Irregular</span>
                    @elseif($student->status == 'Drop')
                    <span class="badge badge-danger">Drop</span>
                    @endif
                  </td>
                </tr>
                @php $counter++; @endphp
                @endforeach
              </tbody>
            </table>

          </div>
        </div>
        <!-- END -->




      </div>
    </div>
  </div>


  </div>
  </div>

  </div>


  <script>
    document.addEventListener("DOMContentLoaded", function() {
      document.querySelectorAll('.course-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('studentProgramsButton').innerHTML = `<i class="mdi mdi-alpha-c-box"></i> ${this.textContent}`;
          document.getElementById('selected_programs').value = this.getAttribute('data-value');
        });
      });
      document.querySelectorAll('.year-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('studentYearsButton').innerHTML = `<i class="mdi mdi-developer-board"></i> ${this.textContent}`;
          document.getElementById('selected_year').value = this.getAttribute('data-value');
        });
      });
      document.querySelectorAll('.status-item').forEach(function(item) {
        item.addEventListener('click', function(e) {
          e.preventDefault();
          document.getElementById('studentStatusDropdown').innerHTML = `<i class="mdi mdi-alpha-s-box"></i> ${this.textContent}`;
          document.getElementById('student_status').value = this.getAttribute('data-value');
        });
      });
    });
  </script>

  @include('footer')