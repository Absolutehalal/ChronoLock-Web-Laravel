<!DOCTYPE html>

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
              <!-- <i class="mdi mdi-home"></i> -->
              <li class="breadcrumb-item"><a href="{{ route('index') }}">Dashboard</a></li>
              <li class="breadcrumb-item active"><a href="{{ route('userManagement') }}">User Management</a></li>
            </ol>
          </nav>

          <!-- Live Date and Time -->
          <div>
            <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
          </div>
        </div>

        <div class="row">
          <div class="col-md-9 d-flex justify-content-start mb-2">
            <form action="{{ route('user.import') }}" method="post" enctype="multipart/form-data">
              @csrf

              <div class="dropdown d-inline-block mr-2">
                <button class="btn btn-primary btn-sm fw-bold" type="submit">
                  <i class="mdi mdi-file-check"></i>
                  Import
                </button>
              </div>

              <div class="dropdown d-inline-block">
                <div class="custom-file rounded">
                  <input type="file" class="custom-file-input" id="excel-file" name="excel-file" required>
                  <label class="custom-file-label" for="excel-file">Choose file</label>
                  <div class="invalid-feedback">Example invalid custom file feedback</div>
                </div>
              </div>
            </form>
          </div>

          <div class="col-md-3 d-flex justify-content-end mb-2">
            <div class="dropdown d-inline-block mb-2 rounded-2">
              <button class="btn btn-warning btn-sm fw-bold" type="button" data-toggle="modal" data-target="#addUserModal">
                <i class="mdi mdi-account-plus"></i>
                Add New User
              </button>
            </div>
          </div>
        </div>

        <!-- END -->


        <!-- table -->
        <form action="{{ route('deleteSelectedUsers') }}" method="POST" id="bulkDeleteForm">
          @csrf
          <div class="card card-default shadow">
            <div class="card-header">
              <h1>User Management</h1>
              <div class="row">
                <div class="col-xl-12 col-md-12 d-flex justify-content-between">
                  <div class="mb-3 mr-2">
                    <button type="button" class="btn btn-outline-dark btn-sm fw-bold" id="selectAllBtn">
                      <i class="mdi mdi-account"></i> Select All User
                      <input type="hidden" class="btn btn-danger btn-sm fw-bold" id="selectAll" />
                    </button>
                  </div>

                  <div class="mb-3 mr-2">
                    <button type="button" class="btn btn-outline-danger btn-sm fw-bold" id="deleteSelectedBtn">
                      <i class="mdi mdi-archive"></i> Delete Selected
                    </button>
                  </div>

                  <div class="mb-3">
                    <a class="btn btn-outline-info btn-sm fw-bold" href="archive">
                      <i class="mdi mdi-archive"></i> Archives
                    </a>
                  </div>
                </div>
              </div>

            </div>
        </form>


        <div class="card-body">
          <table id="exampleTable" class="table table-bordered table-hover nowrap" style="width:100%">
            <thead class="table-dark">
              <tr>
                <th>#</th>
                <th>No.</th>
                <th>FirstName</th>
                <th>LastName</th>
                <th>ID Number</th>
                <th>UserType</th>
                <th>Email</th>
                <th>Avatar</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              @php $counter = 1; @endphp

              @foreach ($users as $user)
              <tr>
                <td class="text-center">
                  <input type="checkbox" name="user_ids[]" class="userCheckbox" value="{{ $user->idNumber }}">
                </td>
                <td> {{$counter}} </td>
                <td> {{ ucwords($user->firstName) }} </td>
                <td> {{ ucwords($user->lastName) }} </td>
                <td> {{$user->idNumber}} </td>
                <td>
                  @if($user->userType == 'Admin')
                  <span class="badge badge-primary">Admin</span>
                  @elseif($user->userType == 'Technician')
                  <span class="badge badge-primary">Technician</span>
                  @elseif($user->userType == 'Lab-in-Charge')
                  <span class="badge badge-primary">Lab-in-Charge</span>
                  @elseif($user->userType == 'Faculty')
                  <span class="badge badge-danger">Faculty</span>
                  @elseif($user->userType == 'Student')
                  <span class="badge badge-warning">Student</span>
                  @endif
                </td>
                <td> {{$user->email}} </td>
                <td class="text-center">
                  <img src="{{ $user->avatar ?? asset('images/User Icon.png') }}" alt="Avatar" width="35" height="35" class="rounded">
                </td>
                <th>
                  <!-- Example single primary button -->
                  <div class="dropdown d-inline-block">
                    <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                      Actions
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                      <button class="dropdown-item btn-sm  editBtn" type="button" data-toggle="modal" data-target="#updateUserModal" value="{{$user->id}}">
                        <i class="mdi mdi-circle-edit-outline text-warning"></i>
                        Edit</button>
                      <button class="dropdown-item btn-sm deleteBtn" type="button" data-toggle="modal" data-target="#deleteUserModal" value="{{$user->id}}">
                        <i class="mdi mdi-archive text-info"></i>
                        Archive</button>

                      <button class="dropdown-item btn-sm deleteForceBtn" href="#" id="deleteUserBtn" value="{{$user->id}}">
                        <i class="mdi mdi-trash-can text-danger"></i>
                        Force Delete
                      </button>


                    </div>
                  </div>
                </th>
              </tr>

              @php $counter++; @endphp
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
          <form id="clearAddUser" method="post" action="{{route('addUser')}}">
            @csrf
            @method('post')

            <div class="row">
              <div class="col-lg-6">
                <ul id="firstNameError"></ul>
                <div class="form-group">
                  <label>First Name</label>
                  <input type="text" class="form-control border border-dark border border-dark" id="firstName" name="firstName" placeholder="Enter First Name" />
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="lastNameError"></ul>
                <div class="form-group">
                  <label>Last Name</label>
                  <input type="text" class="form-control border border-dark border border-dark" id="lastName" name="lastName" placeholder="Enter Last Name" />
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="userTypeError"></ul>
                <div class="form-group">
                  <label>User Type</label>
                  <select class="form-select form-control border border-dark" aria-label="Default select example" id="userType" name="userType">
                    <option selected value="" hidden>---Select User Type---</option>
                    <option value="Lab-in-Charge">Lab-in-Charge</option>
                    <option value="Technician">Technician</option>
                    <option value="Faculty">Faculty</option>
                    <option value="Student">Student</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="emailError"></ul>
                <div class="form-group">
                  <label>Email</label>
                  <input type="text" class="form-control border border-dark" id="email" name="email" placeholder="Enter Email" autocomplete="true" oninput="this.value = this.value.toLowerCase()" />
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="idNumberError"></ul>
                <div class="form-group">
                  <label>ID Number</label>
                  <input type="text" class="form-control border border-dark" id="idNumber" name="idNumber" placeholder="Enter ID Number" />
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="passwordError"></ul>
                <div class="form-group">
                  <label>Temporary Password</label>
                  <div class="input-group">
                    <input type="password" class="form-control border border-dark" id="password" name="password" placeholder="Generate Password" disabled autocomplete="true">
                    <i class="fa fa-eye-slash" id="show-password"></i>
                    <div class="input-group-append">
                      <button class="btn btn-primary btn-sm fw-bold" type="button" id="generate-password">Generate</button>
                    </div>
                  </div>
                </div>
              </div>
            </div> <!-- Modal Boday End-->

            <!-- Modal Footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-pill" id="downClose" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary btn-pill addUser">Send</button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>


  <!-- Delete User Modal -->
  <div class="modal fade" id="deleteUserModal" tabindex="-1" role="dialog" aria-labelledby="deleteUser" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deleteUser" style="text-align:center;">Archive User</h5>
          <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
            @csrf
            @method('delete')
            <input type="hidden" id="deleteID" class="id form-control ">
            <div class="row">
              <i class="fa-solid fa-trash-can text-danger" style="text-align:center; font-size:50px; padding:1rem;"></i>
            </div>
            <div class="row">
              <h4 style="text-align:center;"> Are you sure you want to archive this user?</h4>
            </div>
        </div> <!-- Modal Boday End-->

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-pill" id="deleteClose" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger btn-pill deleteUser">Archive</button>
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

          <ul id="userError"></ul>


          <form method="post">
            @csrf
            @method('put')

            <input type="hidden" id="user_ID" class="id form-control ">

            <div class="row">
              <div class="col-lg-6">
                <ul id="editFirstNameError"></ul>
                <div class="form-group">
                  <label>First Name</label>
                  <input type="text" class="updateFirstName form-control border border-dark border border-dark" id="edit_firstName" name="updateFirstName" placeholder="Enter New First Name">

                </div>
              </div>

              <div class="col-lg-6">
                <ul id="editLastNameError"></ul>
                <div class="form-group">
                  <label>Last Name</label>
                  <input type="text" class="updateLastName form-control border border-dark border border-dark" id="edit_lastName" name="updateLastName" placeholder="Enter New Last Name">

                </div>
              </div>

              <div class="col-lg-6">
                <ul id="editUserTypeError"></ul>
                <div class="form-group">
                  <label>User Type</label>
                  <select class="updateUserType form-select form-control border border-dark" aria-label="Default select example" id="edit_userType" name="updateUserType">

                    <option selected hidden></option>
                    <option value="Admin">Admin</option>
                    <option value="Lab-in-Charge">Lab-in-Charge</option>
                    <option value="Technician">Technician</option>
                    <option value="Faculty">Faculty</option>
                    <option value="Student">Student</option>
                  </select>
                </div>
              </div>

              <div class="col-lg-6">
                <ul id="editEmailError"></ul>
                <div class="form-group">
                  <label>Email</label>
                  <input type="text" class="userEmail form-control border border-dark" id="edit_email" name="userEmail" placeholder="ex. chronolock@my.cspc.edu.ph" oninput="this.value = this.value.toLowerCase()">

                </div>
              </div>

              <div class="col-lg-6">
                <ul id="editUserIdError"></ul>
                <div class="form-group">
                  <label>User ID Number</label>
                  <input type="text" class="userIdNumber form-control border border-dark" id="edit_userId_no" name="userIdNumber" placeholder="Enter User ID">

                </div>
              </div>
            </div> <!-- Modal Boday End-->

            <!-- Modal Footer -->
            <div class="modal-footer">
              <button type="button" class="btn btn-danger btn-pill" id="updateClose" data-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary btn-pill updateUser">Update</button>
            </div>

          </form>

        </div>
      </div>
    </div>
  </div>

  <script>
    // Add event listener to the delete buttons
    document.addEventListener('DOMContentLoaded', function() {
      document.querySelectorAll('.deleteForceBtn').forEach(button => {
        button.addEventListener('click', function(e) {
          e.preventDefault();

          const id = this.value;

          Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
          }).then((result) => {
            if (result.isConfirmed) {
              // Perform the deletion and redirect to the user management page
              window.location.href = "/forceDelete/" + id;
            } else {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: response.message,
                timer: 5000,
                timerProgressBar: true,
              });
            }
          });
        });
      });
    });
  </script>


  <script>
    // Function to handle both select/deselect and button click
    document.getElementById('selectAllBtn').addEventListener('click', function() {
      var checkBox = document.getElementById('selectAll');
      checkBox.checked = !checkBox.checked; // Toggle the checkbox state

      // Select/Deselect all checkboxes based on the state of 'selectAll'
      var checkboxes = document.querySelectorAll('.userCheckbox');
      for (var checkbox of checkboxes) {
        checkbox.checked = checkBox.checked;
      }
    });
  </script>

  <script>
    // Ensure that the script runs after the DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
      // Ensure check/uncheck of 'selectAll' directly affects other checkboxes
      document.getElementById('selectAll').addEventListener('click', function() {
        var checkboxes = document.querySelectorAll('.userCheckbox');
        for (var checkbox of checkboxes) {
          checkbox.checked = this.checked;
        }
      });
    });
  </script>

  <script>
    document.getElementById('deleteSelectedBtn').addEventListener('click', function(e) {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
      }).then((result) => {
        if (result.isConfirmed) {
          document.getElementById('bulkDeleteForm').submit();
        }
      });
    });
  </script>


  <script>
    const firstName = document.getElementById("firstName");
    const lastName = document.getElementById("lastName");
    const userType = document.getElementById("userType");
    const email = document.getElementById("email");
    const password = document.getElementById("password");


    $(document).ready(function() {
      // fetchUsers();
      //   function fetchUsers(){

      // $.ajax({
      //     type: "GET",
      //     url: "/fetchUsers",
      //     dataType: "json",
      //     success: function (response) {
      //       $('tbody').html("");
      //                 $.each(response.users, function (key, item) {

      //                     $('tbody').append('<tr>\
      //               <td>' + item.id + '</td>\
      //               <td>' + item.firstName + '</td>\
      //               <td>' + item.lastName + '</td>\
      //               <td>' + item.userType + '</td>\
      //               <td>' + item.email + '</td>\
      //               <th>\
      //                 <!-- Example single primary button -->\
      //                 <div class="dropdown d-inline-block">\
      //                   <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">Actions\
      //                   </button>\
      //                   <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">\
      //                     <button class="dropdown-item" type="button" data-toggle="modal" data-target="#updateUserModal">\
      //                       <i class="mdi mdi-circle-edit-outline text-warning"></i>\
      //                       Edit</button>\
      //                     <button class="dropdown-item">\
      //                       <i class="mdi mdi-trash-can text-danger"></i>\
      //                       Delete</button>\
      //                   </div>\
      //                 </div>\
      //               </th>\
      //             </tr>');
      //                 });

      //             }

      //         });
      //       }
      $(document).on('click', '.addUser', function(e) {
        e.preventDefault();
        $(this).text('Sending..');
        var data = {
          'firstName': $(firstName).val(),
          'lastName': $(lastName).val(),
          'userType': $(userType).val(),
          'email': $(email).val(),
          'password': $(password).val(),
          'idNumber': $(idNumber).val(),
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
          success: function(response) {
            // console.log(response);
            if (response.status == 400) {
              $('#firstNameError').html("");
              $('#firstNameError').addClass('error');
              $('#lastNameError').html("");
              $('#lastNameError').addClass('error');
              $('#userTypeError').html("");
              $('#userTypeError').addClass('error');
              $('#idNumberError').html("");
              $('#idNumberError').addClass('error');
              $('#emailError').html("");
              $('#emailError').addClass('error');
              $('#passwordError').html("");
              $('#passwordError').addClass('error');
              $.each(response.errors.firstName, function(key, err_value) {
                $('#firstNameError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.lastName, function(key, err_value) {
                $('#lastNameError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.userType, function(key, err_value) {
                $('#userTypeError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.idNumber, function(key, err_value) {
                $('#idNumberError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.email, function(key, err_value) {
                $('#emailError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.password, function(key, err_value) {
                $('#passwordError').append('<li>' + err_value + '</li>');
              });
              $('.addUser').text('Send');
            } else if (response.status == 200) {
              $('#firstNameError').html("");
              $('#lastNameError').html("");
              $('#userTypeError').html("");
              $('#idNumberError').html("");
              $('#emailError').html("");
              $('#passwordError').html("");
              Swal.fire({
                icon: "success",
                title: "Success",
                text: "User Created",
                confirmButtonText: "OK"
              }).then((result) => {
                if (result.isConfirmed) {
                  $('.addUser').text('Send');
                  $("#addUserModal .close").click()

                  // fetchUsers(); -----------reserve-------------
                  window.location.href = "{{route('userManagement')}}";
                }

                // $('.addUser').text('Save');
                //             $("#addUserModal .close").click()

                //             // fetchUsers(); -----------reserve-------------
                //             setTimeout(function () { location.reload(1); }, 1000);
              });

            } else if (response.status == 100) {
              $('#firstNameError').html("");
              $('#lastNameError').html("");
              $('#userTypeError').html("");
              $('#idNumberError').html("");
              $('#emailError').html("");
              $('#passwordError').html("");
              $('.addUser').text('Send');
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Email already Exist. Please use a another Email.",
              });

            } else if (response.status === 101) {
              $('#firstNameError').html("");
              $('#lastNameError').html("");
              $('#userTypeError').html("");
              $('#idNumberError').html("");
              $('#emailError').html("");
              $('#passwordError').html("");
              $('.addUser').text('Send');
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "ID already Exist. Please use a another ID.",
              });
            } else if (response.status == 300) {
              $('#firstNameError').html("");
              $('#lastNameError').html("");
              $('#userTypeError').html("");
              $('#idNumberError').html("");
              $('#emailError').html("");
              $('#passwordError').html("");
              $('.addUser').text('Send');
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: response.message,
                timer: 5000,
                timerProgressBar: true,
              });

            } else if (response.status === 409) {
              $('#firstNameError').html("");
              $('#lastNameError').html("");
              $('#userTypeError').html("");
              $('#idNumberError').html("");
              $('#emailError').html("");
              $('#passwordError').html("");
              $('.addUser').text('Send');
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: response.message,
                timer: 5000,
                timerProgressBar: true,
              });

              // $('.updateUser').text('Update');
              // $("#updateUserModal .close").click()
            }
          }
        });

      });

      $(document).on('click', '.editBtn', function(e) {
        e.preventDefault();
        var id = $(this).val();
        // console.log(userID);
        // // alert(userID);

        $.ajax({
          type: "GET",
          url: "/editUser/" + id,
          success: function(response) {
            // console.log(response);
            if (response.status == 404) {
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No User Found!!!",
              });
              $("#updateUserModal .close").click()
            } else {
              // console.log(response.student.name);
              $('#edit_firstName').val(response.user.firstName);
              $('#edit_lastName').val(response.user.lastName);
              $('#edit_userType').val(response.user.userType);
              $('#edit_email').val(response.user.email);
              $('#edit_userId_no').val(response.user.idNumber);
              $('#user_ID').val(response.user.id);
            }
          }
        });

      });



      $(document).on('click', '.updateUser', function(e) {
        e.preventDefault();

        $(this).text('Updating..');
        var id = $('#user_ID').val();
        // alert(id);

        var data = {
          'updateFirstName': $('.updateFirstName').val(),
          'updateLastName': $('.updateLastName').val(),
          'updateUserType': $('.updateUserType').val(),
          'userEmail': $('.userEmail').val(),
          'userIdNumber': $('.userIdNumber').val(),
        }
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type: "PUT",
          url: "/updateUser/" + id,
          data: data,
          dataType: "json",
          success: function(response) {
            // console.log(response);
            if (response.status == 400) {
              $('#editFirstNameError').html("");
              $('#editFirstNameError').addClass('error');
              $('#editLastNameError').html("");
              $('#editLastNameError').addClass('error');
              $('#editUserIdError').html("");
              $('#editUserIdError').addClass('error');
              $('#editUserTypeError').html("");
              $('#editUserTypeError').addClass('error');
              $('#editEmailError').html("");
              $('#editEmailError').addClass('error');
              $('#editUserIdError').html("");
              $('#editUserIdError').addClass('error');
              $.each(response.errors.updateFirstName, function(key, err_value) {
                $('#editFirstNameError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.updateLastName, function(key, err_value) {
                $('#editLastNameError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.updateUserType, function(key, err_value) {
                $('#editUserTypeError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.userEmail, function(key, err_value) {
                $('#editEmailError').append('<li>' + err_value + '</li>');
              });
              $.each(response.errors.userIdNumber, function(key, err_value) {
                $('#editUserIdError').append('<li>' + err_value + '</li>');
              });
              $('.updateUser').text('Update');
            } else if ((response.status == 200)) {
              $('#editFirstNameError').html("");
              $('#editLastNameError').html("");
              $('#editUserTypeError').html("");
              $('#editEmailError').html("");
              $('#editUserIdError').html("");
              Swal.fire({
                icon: "success",
                title: "Successful",
                text: "User Updated",
                buttons: false,
              });

              $('.updateUser').text('Update');
              $("#updateUserModal .close").click()

              // $('#updateUserModal').find('input').val('');

              window.location.href = "{{route('userManagement')}}", 4000;
            } else if ((response.status == 300)) {
              $('#editFirstNameError').html("");
              $('#editLastNameError').html("");
              $('#editUserTypeError').html("");
              $('#editEmailError').html("");
              $('#editUserIdError').html("");
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: response.message,
                timer: 5000,
                timerProgressBar: true,
              });
              $('.updateUser').text('Update');
              // $("#updateUserModal .close").click()
            } else if ((response.status == 500)) {
              $('#editFirstNameError').html("");
              $('#editLastNameError').html("");
              $('#editUserTypeError').html("");
              $('#editEmailError').html("");
              $('#editUserIdError').html("");
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "Email already exist. Please use another email.",
              });

              $("#updateUserModal .close").click()
            } else if (response.status === 409) {
              $('#editFirstNameError').html("");
              $('#editLastNameError').html("");
              $('#editUserTypeError').html("");
              $('#editEmailError').html("");
              $('#editUserIdError').html("");
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: response.message,
                timer: 5000,
                timerProgressBar: true,
              });

              $('.updateUser').text('Update');
              $("#updateUserModal .close").click()
            }
          }
        });
      });

      $(document).on('mouseover', '.deleteBtn', function() {
        var id = $(this).val();
        $('#deleteID').val(id);
      });

      $(document).on('click', '.deleteUser', function(e) {
        e.preventDefault();

        $(this).text('Archiving..');
        var id = $('#deleteID').val();

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        $.ajax({
          type: "DELETE",
          url: "/deleteUser/" + id,
          dataType: "json",
          success: function(response) {
            // console.log(response);
            if (response.status == 404) {
              $('.deleteUser').text('Delete');
              $("#deleteUserModal .close").click()
              Swal.fire({
                icon: "error",
                title: "Oops...",
                text: "No User Found",
              });

            } else {
              $('.deleteUser').text('Delete');
              $("#deleteUserModal .close").click()
              Swal.fire({
                icon: "success",
                title: "Successful",
                text: "User Archived",
                buttons: false,
              });

              window.location.href = "{{route('userManagement')}}", 4000;
            }
          }
        });
      });

    });
  </script>

  <script>
    document.addEventListener('DOMContentLoaded', function() {

      $('#addUserModal').on('hidden.bs.modal', function() {
        $('#clearAddUser')[0].reset();
        clearAddUserErrors();
      });


      function clearAddUserErrors() {
        $('#firstNameError').empty();
        $('#lastNameError').empty();
        $('#userTypeError').empty();
        $('#emailError').empty();
        $('#passwordError').empty();
      }
    });
  </script>



  @include('footer')