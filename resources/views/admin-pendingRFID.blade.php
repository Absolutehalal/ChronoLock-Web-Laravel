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

  <title>ChronoLock Admin-Pending RFID</title>

  @include('head')
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
  <script>
    NProgress.configure({
      showSpinner: false
    });
    NProgress.start();
  </script>
  @include('adminSideNav')
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
              <li class="breadcrumb-item">
                <a href="index.php">Dashboard</a>
              </li>
              <li class="breadcrumb-item active">
                <a href="admin-pendingrfid.php"> Pending RFID Request</a>
              </li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>


        <div class="card card-default shadow">
          <div class="card-header">
            <h1>RFID Pending Request</h1>

            <div class="row">
              <div class="col-xl-12 col-md-12 d-flex justify-content-end">
                <!-- Sort button -->
                <div class="dropdown d-inline-block ">
                  <button class="btn btn-primary btn-sm fw-bold" type="button" data-toggle="modal" data-target="#exampleModalForm">
                    <i class=" mdi mdi-calendar-plus"></i>
                    ADD RFID
                  </button>
                </div>
              </div>
            </div>
          </div>



          <div class="card-body">
            <table class="table table-bordered table-hover">
              <thead class="table-dark">
                <tr class="text-center">
                  <th scope="col">#</th>
                  <th scope="col">RFID Code</th>
                  <th scope="col">Time</th>
                  <th scope="col">Options</th>
                </tr>
              </thead>
              <tbody>
                <tr class="text-center">
                  <td scope="row">1</td>
                  <td>210102192301923</td>
                  <td>9:18 a.m.</td>
                  <th>
                    <!-- Example single primary button -->
                    <div class="dropdown d-inline-block">
                      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                        Options
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item" data-toggle="modal" data-target="#exampleModalForm">
                          <i class="mdi mdi-check text-info"></i>
                          Activate
                        </button>
                        <button class="dropdown-item">
                          <i class="mdi mdi-close text-danger"></i>
                          Deactivate
                        </button>
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

  <div class="modal fade" id="exampleModalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalFormTitle">Activate RFID</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form>
            <div class="row">

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="exampleInputPassword1">RFID Code</label>
                  <input type="text" class="form-control border border-dark border border-dark" id="exampleInputRFID" placeholder="RFID Code">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="exampleInputUserType">User Type</label>
                  <div>
                    <select class="form-select form-control border border-dark" aria-label="Default select example">
                      <option selected>Select User Type</option>
                      <option value="1">Student</option>
                      <option value="2">Instructor</option>
                      <option value="3">Faculty & Staff</option>
                      <option value="3">Student Aide</option>
                    </select>
                  </div>
                </div>
              </div>


              <div class="col-lg-6">
                <div class="form-group">
                  <label for="exampleInputPassword1">User ID</label>
                  <input type="text" class="form-control border border-dark" id="exampleInputUser" placeholder="Enter User ID">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label for="exampleInputPassword1">User Name</label>
                  <input type="text" class="form-control border border-dark" id="exampleInputUser" placeholder="Enter User Name">
                </div>
              </div>

            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary btn-pill">Save Changes</button>
        </div>
      </div>
    </div>
  </div>

 
  </div>
  </div>

  @include('footer')