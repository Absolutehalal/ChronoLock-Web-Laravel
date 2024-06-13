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
                            <li class="breadcrumb-item active"><a href="admin-user-management.php">User Management</a>
                            </li>
                        </ol>
                    </nav>

                    <!-- Live Date and Time -->
                    <div>
                        <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
                    </div>
                </div>

                <div class="row">

                    <div class="col-md-10 d-flex justify-content-start">
                        <form action="{{ route('user.import') }}" method="post" enctype="multipart/form-data">
                            @csrf

                            <div class="dropdown d-inline-block mr-3">
                                <button class="btn btn-primary btn-md fw-bold" type="submit">
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


                    <div class="col-md-2 d-flex justify-content-end">
                        <div class="dropdown d-inline-block mb-2 rounded-2">
                            <button class="btn btn-warning fw-bold" type="button" data-toggle="modal" data-target="#createModal">
                                <i class="mdi mdi-account-plus"></i>
                                Add New User
                            </button>
                        </div>
                    </div>
                </div>

                <!-- END -->

                <div class="card card-default">
                    <div class="card-header">
                        <h1>User Management</h1>
                    </div>
                    <div class="card-body">
                        <table id="example" class="table table-bordered table-hover nowrap" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <!-- <th>Firstname</th>
                                    <th>Lastname</th> -->
                                    <th>User Email</th>
                                    <th>User ID</th>
                                    <th>User Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                <tr>
                                    <td> {{$user->id}} </td>
                                    <td> {{$user->name}} </td>
                                    <!-- <td> {{$user->firstname}} </td>
                                    <td> {{$user->lastname}} </td> -->
                                    <td> {{$user->email}} </td>
                                    <td> {{$user->uid}} </td>
                                    <td> {{$user->type}} </td>
                                    <th>
                                        <!-- Example single primary button -->
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <button class="dropdown-item" type="button" data-toggle="modal" data-target="#editModal">
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


    <!-- Create Modal -->
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalFormTitle" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalFormTitle">Add New User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="row">

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control border border-dark border border-dark" id="exampleInputRFID" placeholder="ex. Sotto, Edward L.">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>ID</label>
                                    <input type="text" class="form-control border border-dark" id="exampleInputUser" placeholder="Enter User ID">
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="text" class="form-control border border-dark" id="exampleInputUser" placeholder="ex. edsotto@my.cspc.edu.ph">
                                </div>
                            </div>


                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <div>
                                        <select class="form-select form-control border border-dark" aria-label="Default select example">
                                            <option selected disabled>Select Gender</option>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>
                                        </select>
                                    </div>
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