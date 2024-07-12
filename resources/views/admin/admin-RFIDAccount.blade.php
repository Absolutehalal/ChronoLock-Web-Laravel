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

  <title>ChronoLock Admin-RFID Account</title>

  @include('head')
</head>

<body class="navbar-fixed sidebar-fixed" id="body">
  <script>
    NProgress.configure({
      showSpinner: false
    });
    NProgress.start();
  </script>
  @include('admin.adminSideNav')
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
              <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="{{ route('RFIDManagement') }}">RFID Accounts</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>


        <div class="card card-default shadow">
          <div class="card-header">
            <h1>RFID Accounts</h1>
            <div class="row">
              <div class="col-xl-12 col-md-12 d-flex justify-content-end">
                <!-- Sort button -->
                <div class="dropdown d-inline-block ">
                  <button class="btn btn-primary btn-sm fw-bold" type="button" data-toggle="modal" data-target="#exampleModalForm">
                    <i class=" mdi mdi-plus-box"></i>
                    ADD RFID
                  </button>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body col-md-12">
            <table id="exampleTable" class="table table-bordered table-hover no-wrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>Name</th>
                  <th>RFID Code</th>
                  <th>User Type</th>
                  <th>Course</th>
                  <th>Year & Section</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td>Lorzano, Ralph H.</td>
                  <td>C21104744</td>
                  <td>Student</td>
                  <td>BSIS</td>
                  <td>1A</td>
                  <th>
                    <!-- Example single primary button -->
                    <div class="dropdown d-inline-block">
                      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                        Actions
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item" type="button" data-toggle="modal" data-target="#exampleModalForm">
                          <i class="mdi mdi-circle-edit-outline text-warning"></i>
                          Edit</button>
                        <button class="dropdown-item">
                          <i class="mdi mdi-trash-can text-danger"></i>
                          Delete</button>
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
          <h5 class="modal-title" id="exampleModalFormTitle">Add New User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        <form id="userForm">
        @csrf
            <div class="row">

              <div class="col-lg-6">
                <div class="form-group">
                  <label>Name</label>
                  <input type="text" class="form-control border border-dark" id="name" name="name" placeholder="ex. Sotto, Edward L.">
                  <ul id="nameList" class="list-group"></ul>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>RFID Code</label>
                  <input type="text" class="form-control border border-dark" id="rfidCode" name="rfidCode" placeholder="Enter RFID Code">
                </div>
              </div>


              <div class="col-lg-6">
                <div class="form-group">
                  <label for="exampleInputUserType">User Type</label>
                  <div>
                    <select class="form-select form-control border border-dark" aria-label="Default select example">
                      <option selected>Select User Type</option>
                      <option value="1">Student</option>
                      <option value="2">Student Aide</option>
                      <option value="3">Instructor</option>
                      <option value="3">Staff</option>
                      <option value="4">Lab-In_Charge</option>
                    </select>
                  </div>
                </div>
              </div>


              <div class="col-lg-6">
                <div class="form-group">
                  <label for="exampleInputUserType">Course</label>
                  <div>
                    <select class="form-select form-control border border-dark" aria-label="Default select example">
                      <option selected>Select Course</option>
                      <option value="1">BSIT</option>
                      <option value="2">BSCS</option>
                      <option value="3">BSIS</option>
                      <option value="3">BLIS</option>
                    </select>
                  </div>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>Year & Section</label>
                  <input type="text" class="form-control border border-dark" id="exampleInputUser" placeholder="Ex. 1A">
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

  <script>
    // RFID REGISTRATION
    $(document).ready(function() {
      $('#rfidCode').focus();
      // Restrict input to 10 digits integer only
      $('#rfidCode').on('input', function() {
        this.value = this.value.replace(/\D/g, '').substring(0, 10);
      });
    });
  </script>

  <script>
    $(document).ready(function() {
      $('#name').on('keyup', function() {
        var query = $(this).val();
        if (query.length > 0) {
          $.ajax({
            url: "{{ route('autocomplete') }}",
            type: "GET",
            data: {
              'query': query
            },
            success: function(data) {
              $('#nameList').empty();
              if (data.length > 0) {
                data.forEach(function(item) {
                  $('#nameList').append('<li class="list-group-item">' + item.name + '</li>');
                });
              } else {
                $('#nameList').append('<li class="list-group-item">No results found</li>');
              }
            }
          });
        } else {
          $('#nameList').empty();
        }
      });
      $(document).on('click', 'li', function() {
        $('#name').val($(this).text());
        $('#nameList').empty();
      });
    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      $('#exampleModalForm').on('hidden.bs.modal', function() {
        $('#userForm')[0].reset();
      });
    });
  </script>

  @include('footer')