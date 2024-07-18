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
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <script defer src="js/studentJoinClassSchedule.js"></script>
    <title>ChronoLock: Student View Schedule</title>

    @include('head')


</head>

<body class="navbar-fixed sidebar-fixed" id="body">
    <script>
        NProgress.configure({
            showSpinner: false
        });
        NProgress.start();
    </script>

    @include('sweetalert::alert')


    @include('student.studentSideNav')
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
                            <li class="breadcrumb-item"><a href="{{ route('studentIndex') }}">Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('studentViewSchedule') }}">View Schedule</a></li>
                        </ol>
                    </nav>

                    <!-- Live Date and Time -->
                    <div>
                        <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
                    </div>
                </div>




                    <div class="row">
                    @foreach($schedules as $schedule)
                        <div class="col-lg-6 col-xl-4 col-xxl-3">
                            <div class="card card-default shadow mt-7">
                                <div class="card-body text-center">
                                    <button class="editClassSchedule mb-2" href="javascript:void(0)" data-toggle="modal" data-target="#join-class-schedule-modal" value="{{$schedule->classID}}">
                                        <div class="image mb-3 d-inline-flex mt-n8">
                                            <img src="{{$schedule->avatar }}" class="img-fluid rounded-circle d-inline-block" alt="Avatar Image">
                                        </div>

                                        <h5 class="card-title">{{$schedule->instFirstName }} {{$schedule->instLastName }} </h5>
                                      

                                    <ul class="list-unstyled d-inline-block mb-5">
                                        <li class="d-flex mb-1">
                                            <i class="mdi mdi-email mr-1"></i>
                                            <span>{{$schedule->email}}</span>
                                        </li>

                                        <li class="d-flex">
                                        <i class="mdi mdi-group mr-1"></i>
                                        <label class="mr-1">Program:</label>
                                            <span>{{$schedule->program}}</span>
                                        </li>

                                        <li class="d-flex">
                                        <i class="mdi mdi-alpha-s-box mr-1"></i>
                                        <label class="mr-1">Year & Section:</label>
                                            <span>{{$schedule->year}}-{{$schedule->section}}</span>
                                        </li>
                                        
                                        <li class="d-flex">
                                        <i class="mdi mdi-calendar-clock  mr-1"></i>  
                                        <label class="mr-1">Schedule:</label>
                                            <span>{{ $schedule->startTime }}-{{ $schedule->endTime }}</span>
                                        </li>
                                    </ul>

                                    <div class="row justify-content-center">
                                        <div class="col-4 px-1">
                                            <div class=" circle" data-size="60" data-value="0.50" data-thickness="4" 
                                            data-fill="{ &quot;color&quot;: &quot;#35D00E&quot; }">
                                                <div class="circle-content">
                                                    <h6 class="text-uppercase">html</h6>
                                                    <h6>80%</h6>
                                                    <strong></strong>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-4 px-1">
                                            <div class=" circle" data-size="60" data-value="0.65" data-thickness="4" 
                                            data-fill="{
                &quot;color&quot;: &quot;#fec400&quot;
              }">
                                                <div class="circle-content">
                                                    <h6 class="text-uppercase">css</h6>
                                                    <h6>65%</h6>
                                                    <strong></strong>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-4 px-1">
                                            <div class=" circle" data-size="60" data-value="0.35" data-thickness="4" data-fill="{
                &quot;color&quot;: &quot;#fe5461&quot;
              }">
                                                <div class="circle-content">
                                                    <h6 class="text-uppercase">js</h6>
                                                    <h6>25%</h6>
                                                    <strong></strong>
                                                </div>
                                            </div>
                                        </div>
                                @php
                                    $classID=$schedule->classID;
                                    $link= mysqli_connect("localhost","root","");
                                    mysqli_select_db($link, "chronolock");
                                    $query = "SELECT * FROM student_masterlists WHERE userID ='$userID' AND classID ='$classID'";
                                    $result = mysqli_query($link,$query); 
                               @endphp
                             
                                    @if (mysqli_num_rows($result) === 1 ) 
                                       <div class="overlay" style="color: #31ce3c">Enrolled</div>
                                    @else
                                        <div class="overlay" style="color: #FF7F7F">Get Access</div>
                                    
                                    @endif
                                    
                                    
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>


                    <!-- Contact Modal -->
                    <div class="modal fade" id="join-class-schedule-modal" tabindex="-1" role="dialog" aria-labelledby="joinClass" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-md" role="document">
                            <div class="modal-content">
                                <div class="modal-header justify-content-end border-bottom-0">
                                    <button type="button" class="btn-edit-icon" data-dismiss="modal" aria-label="Close">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>

                                    <div class="dropdown">
                                        <button class="btn-dots-icon" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="mdi mdi-dots-vertical"></i>
                                        </button>

                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="javascript:void(0)">Action</a>
                                            <a class="dropdown-item" href="javascript:void(0)">Another action</a>
                                            <a class="dropdown-item" href="javascript:void(0)">Something else here</a>
                                        </div>
                                    </div>

                                    <button type="button" class="btn-close-icon" data-dismiss="modal" aria-label="Close">
                                        <i class="mdi mdi-close"></i>
                                    </button>
                                </div>

                                <div class="modal-body pt-0">
                                    <div class="row no-gutters">
                                        <div class="col-md-12">
                                            <div class="profile-content-left px-4">
                                                <div class="card text-center px-0 border-0">
                                                    <div class="card-img mx-auto">
                                                        <img class="rounded-circle" id="instructorAvatar" alt="Instructor Profile">
                                                    </div>

                                                    <div class="card-body">
                                                        <h4> <input type="text" class="input form-control" id="instFirstNameAndLastName" name="instFirstNameAndLastName" readonly> </h4>
                                                        <form method="post">
                                                        @csrf
                                                        @method('post')

                                                        <ul id="classIDError"></ul>
                                                        <input type="hidden" id="classID" name="classID" class="id form-control ">

                                                        <ul id="enrollmentKeyError"></ul>
                                                        <input type="text" class="form-control border border-dark border border-dark" placeholder="Enter Enrollment Key" id="enrollmentKey" name="enrollmentKey">
                                                        <button type="submit" class="btn btn-primary btn-pill btn-lg my-4 createMasterList">Enroll</button>
    </form>
                                                    </div>
                                                </div>

                                                <div class="d-flex justify-content-between ">
                                                <div class="text-center pb-4">
                
                                                        <h6 class="pb-2">Program</h6>
                                                        <div class="form-group">
                                                        <input type="text" class="input form-control" id="program" name="program" readonly>
                                                        </div>
                                                </div>

                                                <div class="text-center pb-4">
                                                  
                                                    <h6 class="pb-2">Year & Section</h6>
                                                    <div class="form-group">
                                                    <input type="text" class="input form-control" id="yearAndSection" name="yearAndSection" readonly>
                                                    </div>
                                                    </div>

                                                    <div class="text-center pb-4">
                                                    <ul id="sectionError"></ul>
                                                    <h6 class="pb-2">Schedule</h6>
                                                    <div class="form-group">
                                                    <input type="text" class="input form-control" id="startTimeAndEndTime" name="startTimeAndEndTime" readonly>
                                                    </div>
                                                                    </div>
                                                </div>
                                                </div>
                                                        </div>

                                 
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>





            </div>
        </div>


    </div>
    </div>
    </div>
    </div>


    </div>
    </div>
    </div>
    </div>
   
    @include('footer')