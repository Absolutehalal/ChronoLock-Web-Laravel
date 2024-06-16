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

  <title>ChronoLock Admin-User Management</title>

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
              <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="admin-user-management.php">User Management</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>

        <div class="row">
          <div class="col-md-9 d-flex justify-content-start">
            <div class="dropdown d-inline-block mb-2 mr-3 rounded-2">
              <button class="btn btn-primary btn-md fw-bold" type="button" data-toggle="modal" data-target="#modal-add-event">
                <i class="mdi mdi-file-check"></i>
                Import
              </button>
            </div>
            <div class="dropdown d-inline-block mb-3">
              <div class="custom-file rounded">
                <input type="file" class="custom-file-input" required>
                <label class="custom-file-label" for="coverImage">Choose file...</label>
                <div class="invalid-feedback">Example invalid custom file feedback</div>
              </div>
            </div>
          </div>

          <div class="col-md-3 d-flex justify-content-end">
            <div class="dropdown d-inline-block mb-2 rounded-2">
              <button class="btn btn-warning fw-bold" type="button" data-toggle="modal" data-target="#addUserModal">
                <i class="mdi mdi-account-plus"></i>
                Add New User
              </button>
            </div>
          </div>
        </div>

        <!-- END -->

  <!-- table -->
  <div class="card card-default">
          <div class="card-header">
            <h1>User Management</h1>
          </div>
          <div class="card-body">
            <table id="example" class="table table-bordered table-hover nowrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th>#</th>
                  <th>FirstName</th>
                  <th>LastName</th>
                  <th>UserType</th>
                  <th>Email</th>
                  <th>Action</th>
                </tr>
              </thead>
                <tbody>
                  @foreach ($users as $user)
                  <tr>
                    <td> {{$user->id}} </td>
                    <td> {{$user->firstName}} </td>
                    <td> {{$user->lastName}} </td>
                    <td> {{$user->userType}} </td>
                    <td> {{$user->email}} </td>
                    <th>
                      <!-- Example single primary button -->
                      <div class="dropdown d-inline-block">
                        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                          Actions
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                          <button class="dropdown-item" type="button" data-toggle="modal" data-target="#updateUserModal" value="{{$user->id}}">
                            <i class="mdi mdi-circle-edit-outline text-warning"></i>
                            Edit</button>
                          <button class="dropdown-item">
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
  
 <!-- Add User Modal -->
 <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUser" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="addUser">Add New User</h5>
          <button type="button" class="close" id="upClose" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body"> 
          <form method="post" action="{{route('addUser')}}">
            @csrf
            @method('post')

              <div class="row">
                <div class="col-lg-6">
                <ul id="firstNameError"></ul>
                  <div class="form-group">
                    <label>First Name</label>
                    <input type="text" class="form-control border border-dark border border-dark" id="firstName" name="firstName" placeholder="Enter First Name"/>
                  </div>
                </div>

                <div class="col-lg-6">
                <ul id="lastNameError"></ul>
                  <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" class="form-control border border-dark border border-dark" id="lastName" name="lastName" placeholder="Enter Last Name"/>
                  </div>
                </div>

                <div class="col-lg-6">
                <ul id="userTypeError"></ul>
                  <div class="form-group">
                    <label>User Type</label>
                      <select class="form-select form-control border border-dark" aria-label="Default select example" id="userType" name="userType">
                        <option selected  value="" hidden>---Select User Type---</option>
                        <option value="Instructor">Instructor</option>
                        <option value="Student">Student</option>
                        <option value="Faculty">Faculty</option>
                        <option value="Staff">Staff</option>
                        <option value="Student Aide">Student Aide</option>
                      </select>
                  </div>
                </div>

                <div class="col-lg-6">
                <ul id="emailError"></ul>
                  <div class="form-group">
                    <label>Email</label>
                    <input type="text" class="form-control border border-dark border border-dark" id="email" name="email" placeholder="Enter Email"/>
                  </div>
                </div>

                <div class="col-lg-6">
                <ul id="passwordError"></ul>
                  <div class="form-group">
                    <label>Temporary Password</label>
                    <div class="input-group">
                      <input type="password" class="form-control border border-dark" id="password" name="password" placeholder="Generate Password" readonly="true">
                      <i class="fa fa-eye-slash" id="show-password"></i>
                      <div class="input-group-append">
                        <button class="btn btn-primary btn-md fw-bold" type="button" id="generate-password">Generate</button>
                      </div>
                    </div>
                  </div>
                </div>
              </div> <!-- Modal Boday End-->

              <!-- Modal Footer --> 
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary btn-pill addUser">Save</button>
            </div>

          </form>

        </div>
    </div>
  </div>
</div>      

    <!-- Update User Modal -->
    <div class="modal fade" id="updateUserModal" tabindex="-1" role="dialog" aria-labelledby="updateUser" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="updateUser">Edit User</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body"> 

        <form method="post" action="{{route('updateUser', ['user' =>$user])}}">
            @csrf
            @method('put')

            <div class="row">
              <div class="col-lg-6">
                <div class="form-group">
                  <label>First Name</label>
                  <input type="text" class="form-control border border-dark border border-dark" id="firstName" name="firstName"
                   placeholder="Enter New First Name" value="{{ $user->firstName }}">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>Last Name</label>
                  <input type="text" class="form-control border border-dark border border-dark" id="lastName" name="lastName"
                   placeholder="Enter New Last Name" value="{{ $user->lastName }}">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>User Type</label>
                    <select class="form-select form-control border border-dark" aria-label="Default select example" id="userType" name="userType">
                      <option selected  value="{{ $user->userType }}" hidden>{{ $user->userType }}</option>
                      <option value="Instructor">Instructor</option>
                      <option value="Student">Student</option>
                      <option value="Faculty">Faculty</option>
                      <option value="Staff">Staff</option>
                      <option value="Student Aide">Student Aide</option>
                    </select>
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>Email</label>
                  <input type="text" class="form-control border border-dark" id="email" name="email" 
                  placeholder="ex. chrono@my.cspc.edu.ph" value="{{ $user->email }}">
                </div>
              </div>

              <div class="col-lg-6">
                <div class="form-group">
                  <label>Student ID</label>"
                  <input type="text" class="form-control border border-dark" id="google_id" name="google_id"
                  placeholder="Enter User ID" value="{{$user->google_id}}">
                </div>
              </div>
            </div> <!-- Modal Boday End-->

           <!-- Modal Footer --> 
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary btn-pill">Save</button>
        </div>

      </form>

      </div>
    </div>
  </div>
  </div>
  <script>
    
const firstName = document.getElementById
("firstName");
const lastName = document.getElementById
("lastName");
const userType = document.getElementById
("userType");
const email = document.getElementById
("email");
const password = document.getElementById
("password");

 
   $(document).ready(function () {
    

function fetchUsers() {

    $.ajax({
        type: "GET",
        url: "/fetchUsers",
        dataType: "json",
        success: function (response) {
          $('tbody').html("");
                    $.each(response.users, function (key, item) {
                        $('tbody').append('<tr>\
                  <td class="dtr-control dt-type-numeric sorting_1" tabindex="0">' + item.id + '</td>\
                  <td>' + item.firstName + '</td>\
                  <td>' + item.lastName + '</td>\
                  <td>' + item.userType + '</td>\
                  <td>' + item.email + '</td>\
                  <th>\
                    <!-- Example single primary button -->\
                    <div class="dropdown d-inline-block">\
                      <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">Actions\
                      </button>\
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">\
                        <button class="dropdown-item" type="button" data-toggle="modal" data-target="#updateUserModal">\
                          <i class="mdi mdi-circle-edit-outline text-warning"></i>\
                          Edit</button>\
                        <button class="dropdown-item">\
                          <i class="mdi mdi-trash-can text-danger"></i>\
                          Delete</button>\
                      </div>\
                    </div>\
                  </th>\
                </tr>');
                    });
                  
                }
               
            });
            window.location.href = "{{route('userManagement')}}";
        }
        $(document).on('click', '.addUser', function (e) {
            e.preventDefault();
            $(this).text('Sending..');
            var data = {
                'firstName': $(firstName).val(),
                'lastName': $(lastName).val(),
                'userType': $(userType).val(),
                'email': $(email).val(),
                'password': $(password).val(),
            }
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                type: "POST",
                url: "/userManagementPage",
                data: data,
                dataType: "json",
                success: function (response) {
                    // console.log(response);
                    if (response.status == 400) {
                        $('#firstNameError').html("");
                        $('#firstNameError').addClass('error');
                        $('#lastNameError').html("");
                        $('#lastNameError').addClass('error');
                        $('#userTypeError').html("");
                        $('#userTypeError').addClass('error');
                        $('#emailError').html("");
                        $('#emailError').addClass('error');
                        $('#passwordError').html("");
                        $('#passwordError').addClass('error');
                        $.each(response.errors.firstName, function (key, err_value) {
                            $('#firstNameError').append('<li>' + err_value + '</li>');
                        });
                        $.each(response.errors.lastName, function (key, err_value) {
                            $('#lastNameError').append('<li>' + err_value + '</li>');
                        });
                        $.each(response.errors.userType, function (key, err_value) {
                            $('#userTypeError').append('<li>' + err_value + '</li>');
                        });
                        $.each(response.errors.email, function (key, err_value) {
                            $('#emailError').append('<li>' + err_value + '</li>');
                        });
                        $.each(response.errors.password, function (key, err_value) {
                            $('#passwordError').append('<li>' + err_value + '</li>');
                        });
                        $('.addUser').text('Save');
                    } else if(response.status == 200){
                        $('#firstNameError').html("");
                        $('#lastNameError').html("");
                        $('#userTypeError').html("");
                        $('#emailError').html("");
                        $('#passwordError').html("");
                        $('.addUser').text('Save');
                        $("#addUserModal .close").click()
                        fetchUsers();
                    }
                    else if(response.status == 300){
                        $('#firstNameError').html("");
                        $('#lastNameError').html("");
                        $('#userTypeError').html("");
                        $('#emailError').html("");
                        $('#passwordError').html("");
                        $('.addUser').text('Save');
                        Swal.fire({
                            icon: "error",
                            title: "Oops...",
                            text: "Invalid email. Please use a CSPC email.",
                          });
                        $("#addUserModal .close").click()
                    }
                }
            });

        });
    });
</script>
 
  @include('footer')
  