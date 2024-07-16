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

  <!-- Ajax edit Class List -->
  <script defer src="{{asset('js/editClassList.js')}}"></script>

    <title>ChronoLock Instructor-Class Record</title>

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
                            <li class="breadcrumb-item active"><a href="{{ route('classListManagement') }}">Class List</a></li>
                        </ol>
                    </nav>

                    <!-- Live Date and Time -->
                    <div>
                        <p class="text-center date-time mb-2" id="liveDateTime">Your Date and Time</p>
                    </div>
                </div>

                

                <div class="card card-default">
                    <div class="card-header">
                        <h1>    Class List</h1>
                    </div>
                    <div class="card-body ">
                        <table id="exampleTable" class="table table-bordered table-hover" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Course</th>
                                    <th>Year & Section</th>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Semester</th>
                                    <th>Enrollment Key</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($classes as $classes)
                                <tr>
                                    <td>{{ $classes->classID }}</td>
                                    <td>{{ $classes->course }}</td>
                                    <td>{{ $classes->year }}-{{ $classes->section }}</td>
                                    <td>{{ $classes->courseCode }}</td>
                                    <td>{{ $classes->courseName }}</td>
                                    <td>{{ $classes->semester }}</td>
                                    <td>{{ $classes->enrollmentKey }}</td>
                                    <th>
                                        <!-- Example single primary button -->
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <button class="dropdown-item editClassListBtn" type="button" data-toggle="modal" data-target="#classListUpdateModal" value="{{$classes->classID}}">
                                                    <i class="mdi mdi-circle-edit-outline text-warning"></i>
                                                    Edit</button>
                                                <button class="dropdown-item deleteClassListBtn" type="button" data-toggle="modal" data-target="#classListDeleteModal" value="{{$classes->classID}}">
                                                    <i class="mdi mdi-trash-can text-danger"></i>
                                                    Delete</button>
                                            </div>
                                        </div>
                                    </th>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>

                    </div>
                </div>
            </div>


        </div>
    </div>
    </div>
    </div>


    <!-- Update Class List Modal -->
    <div class="modal fade" id="classListUpdateModal" tabindex="-1" role="dialog" aria-labelledby="updateClassList" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateClassList">Edit Class List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <ul id="ClassListError"></ul>
                    <form method="post">
                        @csrf
                        @method('put')
                        <input type="hidden" id="classListUpdateID" class="id form-control">

                        <div class="row">
                            <div class="col-lg-6">
                                <ul id="editCourseError"></ul>
                                <div class="form-group">
                                    <label>Course</label>
                                    <input type="text" class="updateCourse form-control border border-dark" id="edit_course" name="update_course" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ul id="editYearSectionError"></ul>
                                <div class="form-group">
                                    <label>Year & Section</label>
                                    <input type="text" class="updateYearSection form-control border border-dark" id="edit_yearSection" name="update_yearSection" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ul id="editCourseCodeError"></ul>
                                <div class="form-group">
                                    <label>Course Code</label>
                                    <input type="text" class="updateCourseCode form-control border border-dark" id="edit_courseCode" name="update_courseCode" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ul id="editCourseNameError"></ul>
                                <div class="form-group">
                                    <label>Course Name</label>
                                    <input type="text" class="updateCourseName form-control border border-dark" id="edit_courseName" name="update_courseName" readonly>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ul id="editSemesterError"></ul>
                                <div class="form-group">
                                    <label>Semester</label>
                                    <select class="updateSemester form-select form-control border border-dark" aria-label="Default select example" id="edit_semester" name="update_semester">
                                        <option selected hidden></option>
                                        <option value="1st Semester">1st Semester</option>
                                        <option value="2nd Semester">2nd Semester</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <ul id="editEnrollmentKeyError"></ul>
                                <div class="form-group">
                                    <label>Enrollment Key</label>
                                    <input type="text" class="updateEnrollmentKey form-control border border-dark" id="edit_enrollmentKey" name="update_enrollmentKey">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger btn-pill" id="updateClose" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary btn-pill updateClasslist">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <!-- Delete Class List Modal -->
    <div class="modal fade" id="classListDeleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteClassList" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteClassList" style="text-align:center;">Delete Student Attendance</h5>
                    <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form method="post">
                        @csrf
                        @method('delete')
                        <input type="hidden" id="deleteClassListID" class="id form-control ">
                        <div class="row">
                            <i class="fa-solid fa-trash-can text-danger" style="text-align:center; font-size:50px; padding:1rem;"></i>
                        </div>
                        <div class="row">
                            <h4 style="text-align:center;"> Are you sure you want to delete this in your Class List?</h4>
                        </div>
                </div> <!-- Modal Boday End-->

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary btn-pill" id="close" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger btn-pill deleteClassList">Delete</button>
                </div>

                </form>

            </div>
        </div>
    </div>
    @include('footer')



  @include('footer')