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

    <title>ChronoLock Admin-User Archive</title>

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
                            <li class="breadcrumb-item"><a href="{{ route('userManagement') }}">User Management</a></li>
                            <li class="breadcrumb-item active"><a href="{{ route('archive') }}">Archive</a></li>
                        </ol>
                    </nav>

                    <!-- Live Date and Time -->
                    <div>
                        <p class="text-center date-time mb-3" id="liveDateTime">Your Date and Time</p>
                    </div>
                </div>


                <div class="card card-default shadow">
                    <div class="card-header">
                        <h1>Archives</h1>
                        <div class="row">
                            <div class="col-xl-12 col-md-12 d-flex justify-content-end">

                                <div class="dropdown d-inline-block mb-3 mr-3">
                                    <a class="btn btn-primary btn-sm fw-bold" href="restore-all-users" id="restore-all-button">
                                        <i class=" mdi mdi-backup-restore"></i>
                                        Restore All
                                    </a>

                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-body">
                        <table id="exampleTable" class="table table-bordered table-hover no-wrap" style="width:100%">
                            <thead class="table-dark">
                                <tr>
                                    <!-- <th>#</th> -->
                                    <th>User ID</th>
                                    <th>Firstname</th>
                                    <th>Lastname</th>
                                    <th>User Type</th>
                                    <th>Email</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($archiveUsers as $user)
                                <tr>
                                    <td> {{$user->id}} </td>
                                    <td> {{$user->firstName}} </td>
                                    <td> {{$user->lastName}} </td>
                                    <td> {{$user->userType}} </td>
                                    <td> {{$user->email}} </td>
                                    <th>
                                        <!-- Example single primary button -->
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                                                Actions
                                            </button>
                                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <a class="dropdown-item restoreBtn" href="#" id="restoreUserBtn" value="{{$user->id}}">
                                                    <i class="mdi mdi-backup-restore text-info"></i>
                                                    Restore
                                                </a>

                                                <form id="restoreForm" action="" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('GET')
                                                </form>

                                                <a class="dropdown-item deleteBtn" href="#" id="deleteUserBtn" value="{{$user->id}}">
                                                    <i class="mdi mdi-trash-can text-danger"></i>
                                                    Force Delete
                                                </a>

                                                <form id="deleteForm" action="" method="POST" style="display: none;">
                                                    @csrf
                                                    @method('GET')
                                                </form>
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

    <!-- JavaScript for SweetAlert and form submission -->
    <script>
        // Add event listener to the delete buttons
        document.querySelectorAll('.deleteBtn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const userId = this.getAttribute('value');
                const deleteForm = document.getElementById('deleteForm');
                deleteForm.action = `/forceDelete/${userId}`;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteForm.submit();
                    }
                });
            });
        });
        // Add event listener to the restore buttons
        document.querySelectorAll('.restoreBtn').forEach(button => {
            button.addEventListener('click', function(event) {
                event.preventDefault();
                const userId = this.getAttribute('value');
                const restoreForm = document.getElementById('restoreForm');
                restoreForm.action = `/restore/${userId}`;
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You want to restore this user?",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        restoreForm.submit();
                    }
                });
            });
        });
        document.addEventListener('DOMContentLoaded', function() {
            const restoreAllButton = document.getElementById('restore-all-button');
            restoreAllButton.addEventListener('click', function(event) {
                event.preventDefault(); // Prevent the default action (navigation)
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You are about to restore all users.",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, restore them!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = restoreAllButton.href; // Proceed with the navigation if confirmed
                    }
                });
            });
        });
    </script>

    @include('footer')