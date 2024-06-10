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
    @include('instructorSideNav')
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
                            <li class="breadcrumb-item"><a href="inst-dashboard.php">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="inst-class-record.php">Class Record</a></li>
                        </ol>
                    </nav>

                    <!-- Live Date and Time -->
                    <div>
                        <p class="text-center date-time mb-2" id="liveDateTime">Your Date and Time</p>
                    </div>
                </div>

                <div class="card card-default">
                    <div class="card-header">
                        <h1>My Class Record</h1>
                        <div class="row">
                            <div class="col-xl-12 col-md-12 d-flex justify-content-end">
                                <!-- Sort button -->
                                <div class="dropdown d-inline-block border border-dark rounded">
                                    <button class="btn btn-primary fw-bold" type="button"  data-toggle="modal" data-target="#exampleModalForm">
                                        <i class=" mdi mdi-file-plus"></i>
                                        Create Class Record
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body ">


                        <table id="example" class="table table-bordered table-hover" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Program</th>
                                    <th>Year & Section</th>
                                    <th>Course Code</th>
                                    <th>Course Name</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>BSIS</td>
                                    <td>1A</td>
                                    <td>ITA-315</td>
                                    <td>Advance Database</td>
                                    <th>
                                        <!-- Example single primary button -->
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item  type="button" data-toggle="modal" data-target="#exampleModalForm"">
                                                    <i class="mdi mdi-circle-edit-outline text-warning"></i>
                                                    Edit</a>
                                                <a class="dropdown-item">
                                                    <i class="mdi mdi-trash-can text-danger"></i>
                                                    Delete</a>
                                            </div>
                                        </div>
                                    </th>
                                </tr>


                            </tbody>
                        </table>

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

    <div class="modal fade" id="exampleModalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormTitle" aria-hidden="true">
    <div class="modal-dialog "  role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalFormTitle">Create Class Record</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">

              <div class="col-lg-6">
                <div class="form-group">
                  <label>Program</label>
                  <input type="text" class="form-control border border-dark" id=" " placeholder="Program">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>Year & Section</label>
                  <input type="text" class="form-control border border-dark" id=" " placeholder="Year & Section">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>Course</label>
                  <input type="text" class="form-control border border-dark" id=" " placeholder="Enter Course">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>Course Code</label>
                  <input type="text" class="form-control border border-dark" id=" " placeholder="Enter Course Code">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>Course Name</label>
                  <input type="text" class="form-control border border-dark" id=" " placeholder="Enter Course Name">
                </div>
              </div>

              
              <div class="col-lg-6">
                <div class="form-group">
                  <label>Instructor Name</label>
                  <input type="text" class="form-control border border-dark" id=" " placeholder="ex. Edward L. Sotto">
                </div>
              </div>


            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary btn-pill">Save</button>
        </div>
      </div>
    </div>
  </div>

  @include('footer')