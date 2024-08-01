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

  <!-- Ajax  Pending RFID -->
  <script defer src="js/adminPendingRFID.js"></script>

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
              <!-- <i class="mdi mdi-home"></i> -->
              <li class="breadcrumb-item">
                <a href="{{ route('index') }}">Dashboard</a>
              </li>
              <li class="breadcrumb-item active">
                <a href="{{ route('pendingRFID') }}"> Pending RFID Request</a>
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
                  <button class="btn btn-primary btn-sm fw-bold" type="button" data-toggle="modal" data-target="#pendingRFIDModal">
                    <i class=" mdi mdi-calendar-plus"></i>
                    ADD RFID
                  </button>
                </div>
              </div>
            </div>
          </div>



          <div class="card-body">
            <table id="exampleTable" class="table table-bordered table-hover nowrap" style="width:100%">
              <thead class="table-dark">
                <tr>
                  <th scope="col">#</th>
                  <th scope="col">RFID Code</th>
                  <th scope="col">Options</th>
                </tr>
              </thead>
              <tbody>
              @foreach($pendingRFID as $pendingRFID)
               @csrf
                  <td>{{ $pendingRFID->id }}</td>
                  <td>{{ $pendingRFID->RFID_Code }}</td>
                  <th>
                    <!-- Example single primary button -->
                    <div class="dropdown d-inline-block">
                      <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-display="static">
                        Options
                      </button>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <button class="dropdown-item activateBtn" data-toggle="modal" data-target="#pendingRFIDModal" value="{{$pendingRFID->id}}">
                          <i class="mdi mdi-check text-info"></i>
                          Activate
                        </button>
                        <button class="dropdown-item deleteBtn" data-toggle="modal" data-target="#deletePendingRFIDModal" value="{{$pendingRFID->id}}">
                        <i class="mdi mdi-trash-can text-danger"></i>
                         
                          Delete
                        </button>
                      </div>
                    </div>
                  </th>
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

  <!-- Pending RFID Modal -->
  <div class="modal fade" id="pendingRFIDModal" tabindex="-1" role="dialog" aria-labelledby="pendingRFIDModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="pendingRFIDModal">Activate RFID</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
              @csrf
              @method('put')
          
              <div class="row">

                <input type="hidden" id="pendingRFID_ID" class="form-control ">

                  <div class="col-lg-12">
                  <ul id="rfidError"></ul>
                    <div class="form-group">
                      <label for="rfid code">RFID Code</label>
                      <input type="text" class="Rfid_Code form-control border border-dark border border-dark" id="rfidCode" name="rfidCode" placeholder="RFID Code" readonly>
                    </div>
                  </div>


                  <div class="col-lg-12">
                  <ul id="userIDError"></ul>
                    <div class="form-group">
                      <label for="user id">User ID</label>
                      <input type="text" class="userId form-control border border-dark" id="userID" name="userID" placeholder="Enter User ID">
                      <ul id="idNumberList" class="list-group" style="max-height: 100px; overflow-y: auto; margin-top: 5px;"></ul>
                    </div>
                  </div>

        </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-danger btn-pill" class="close" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary btn-pill activateRFID">Activate</button>
          </div>
          </form>
      </div>
    </div>
  </div>
  </div>
  </div>




   <!-- Delete Pending RFID Modal -->
   <div class="modal fade" id="deletePendingRFIDModal" tabindex="-1" role="dialog" aria-labelledby="deletePendingRFID" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deletePendingRFID" style="text-align:center;">Delete Pending RFID</h5>
          <button type="button" class="close" id="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <form method="post">
            @csrf
            @method('delete')
            <input type="hidden" id="deletePendingRFID" class="id form-control ">
            <div class="row">
              <i class="fa-solid fa-trash-can text-danger" style="text-align:center; font-size:50px; padding:1rem;"></i>
            </div>
            <div class="row">
              <h4 style="text-align:center;"> Are you sure you want to delete this RFID?</h4>
            </div>
        </div> <!-- Modal Boday End-->

        <!-- Modal Footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-pill" id="close" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-danger btn-pill deleteRFID">Delete</button>
        </div>

        </form>

      </div>
    </div>
  </div>
  </div>


  <script>
    $(document).ready(function() {
      $('#userID').on('keyup', function() {

        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
        });

        var query = $(this).val();
        // console.log(query);

        if (query.length > 0) {
          $.ajax({
            url: "{{ route('autocomplete') }}",
            type: "GET",
            data: {
              'query': query
            },
            success: function(response) {
              $('#idNumberList').empty();

              if (response.number && response.number.length > 0) {
                response.number.forEach(function(item) {
                  $('#idNumberList').append('<li class="list-group-item" style="font-weight: bold; cursor:pointer; border: 1px solid #000; margin-bottom: 2px">' 
                  + item.idNumber + '</li>');
                });
              } else {
                $('#idNumberList').append('<li class="list-group-item" style="font-weight: bold; cursor:not-allowed; border: 1px solid #000; margin-bottom: 2px;pointer-events: none;">No results found</li>');
              }
            }
          });
        } else {
          $('#idNumberList').empty();
        }
      });

      $(document).on('click', 'li', function() {
        $('#userID').val($(this).text());
        $('#idNumberList').empty();
      });
    });
  </script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
      $('#pendingRFIDModal').on('hidden.bs.modal', function() {
        $('#clearForm')[0].reset();
        $('#idNumberList').empty();
      });
    });
  </script>

  @include('footer')