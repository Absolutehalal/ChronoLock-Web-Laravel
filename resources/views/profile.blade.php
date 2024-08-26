<!DOCTYPE html>

<html lang="en" dir="ltr">

<head>
  <style>
    #show-password-profile {
      position: absolute;
      right: 10px;
      /* Adjust distance from the right as needed */
      top: 50%;
      transform: translateY(-50%);
      cursor: pointer;
      font-size: 1.25rem;
      /* Adjust icon size as needed */
      color: #000000;
      /* Optional: change icon color */
    }
  </style>

</head>

@include('sweetalert::alert')

@if(Auth::check())
<!-- VIEW PROFILE -->
<div class="modal fade" id="modal-profile" tabindex="-1" role="dialog" aria-labelledby="modal-profile" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <form>
        <div class="modal-header px-4">
          <h5 class="modal-title" id="update-profile">Profile Settings</h5>
          <button type="button" class="btn-close-icon" data-dismiss="modal" aria-label="Close">
            <i class="mdi mdi-close"></i>
          </button>
        </div>
        <div class="modal-body px-4">
          <div class="row mb-2">
            <div class="col-lg-6">
              <div class="form-group">
                <img src="{{ Auth::user()->avatar ?? asset('images/User Icon.png') }}" class="user-image rounded-circle" style="display: block; margin-left: auto; margin-right: auto; height: 110px; width: 110px" alt="User Image" />
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group mt-4">
                <label for="userType">User Type</label>
                <input type="text" class="profile-userType form-control border-dark" value="{{ Auth::user()->userType }}" id="profile-userType" name="userType" disabled>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group mb-4">
                <label for="firstName">First name</label>
                <input type="text" class="profile-firstName form-control border-dark" value="{{ Auth::user()->firstName }}" id="profile-firstName" name="firstName" disabled>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group mb-4">
                <label for="lastName">Last name</label>
                <input type="text" class="profile-lastName form-control border-dark" value="{{ Auth::user()->lastName }}" id="profile-lastName" name="lastName" disabled>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group mb-4">
                <label for="email">Email</label>
                <input type="email" class="profile-email form-control border-dark" value="{{ Auth::user()->email }}" id="profile-email" name="email" disabled autocomplete="true">
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group mb-4">
                <label for="idNumber">ID Number</label>
                <input type="text" class="profile-idNumber form-control border-dark" value="{{ Auth::user()->idNumber }}" id="profile-idNumber" name="idNumber" disabled>
              </div>
            </div>

          </div>
        </div>
        <div class="modal-footer px-4">
          <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary btn-pill editProfileBtn" value="{{ Auth::user()->id }}" data-dismiss="modal" data-toggle="modal" data-target="#update-modal-profile">Edit Profile</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif
<!-- UPDATE PROFILE -->
<div class="modal fade" id="update-modal-profile" tabindex="-1" role="dialog" aria-labelledby="update-modal-profile" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header px-4">
        <h5 class="modal-title" id="update-modal-profile">Update Profile Settings</h5>
        <button type="button" class="btn-close-icon" data-dismiss="modal" aria-label="Close">
          <i class="mdi mdi-close"></i>
        </button>
      </div>
      <div class="modal-body px-4">
        <form id="clearProfile" method="post" action="{{ route('profile.update', ['id' => Auth::id()]) }}">

          @csrf
          @method('put')

          <input type="hidden" id="profile_userID" class="form-control">
          <div class="row mb-2">
            <div class="col-lg-6">
              <div class="form-group">
                <img src="{{ Auth::user()->avatar ?? asset('images/User Icon.png') }}" id="" class="user-image rounded-circle" style="display: block; margin-left: auto; margin-right: auto; height: 110px; width: 110px" alt="User Image" />
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group mt-4">
                <label for="edit-userType">User Type</label>
                <input type="text" class="form-control border-dark" id="edit-userType" name="update-userType" placeholder="Input User Type" readonly>
              </div>
            </div>

            <div class="col-lg-6">
              <ul id="firstNameError"></ul>
              <div class="form-group">
                <label for="edit-firstName">First name</label>
                <input type="text" class="profile_firstName form-control border-dark" id="edit-firstName" name="update-firstName" placeholder="Input First Name">
              </div>
            </div>

            <div class="col-lg-6">
              <ul id="lastNameError"></ul>
              <div class="form-group">
                <label for="edit-lastName">Last name</label>
                <input type="text" class="profile_lastName form-control border-dark" id="edit-lastName" name="update-lastName" placeholder="Input Last Name">
              </div>
            </div>


            <div class="col-lg-4">
              <ul id="emailError"></ul>
              <div class="form-group mb-4">
                <label for="edit-email">Email</label>
                <input type="email" class="profile_email form-control border-dark" id="edit-email" name="update-email" placeholder="Input Email">
              </div>
            </div>

            <div class="col-lg-4">
              <ul id="idNumberError"></ul>
              <div class="form-group mb-4">
                <label for="edit-idNumber">ID Number</label>
                <input type="text" class="profile_idNumber form-control border-dark" id="edit-idNumber" name="update-idNumber" placeholder="Input ID Number">
              </div>
            </div>

            <div class="col-lg-4">
              <ul id="passwordError"></ul>
              <label for="edit-password" class="form-label fw-bold">Password</label>
              <div class="form-group position-relative">
                <input id="edit-password" name="update-password" type="password" class="profile_password form-control border-dark" placeholder="Password" oninput="validateFieldPassword()" maxlength="6" autocomplete="true">
                <i class="fa fa-eye-slash" id="show-password-profile"></i>
              </div>
            </div>

          </div>
          <div class="modal-footer px-4">
            <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal" data-toggle="modal" data-target="#modal-profile">Cancel</button>
            <button type="submit" class="btn btn-primary btn-pill update-profile">Update Profile</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    $('#update-modal-profile').on('hidden.bs.modal', function() {
      $('#clearProfile')[0].reset();
      clearProfileErrors();
    });
    function clearProfileErrors() {
      $('#emailError').empty();
      $('#idNumberError').empty();
    }
  });
</script>


<script>
  const profileShowPassword = document.querySelector("#show-password-profile");
  const profilePasswordField = document.querySelector("#edit-password");
  profileShowPassword.addEventListener("click", function() {
    this.classList.toggle("fa-eye");
    const type = profilePasswordField.getAttribute("type") === "password" ? "text" : "password";
    // Toggle password field visibility
    profilePasswordField.setAttribute("type", type);
  });
</script>

<script>
  function validateFieldPassword() {
    var passwordInput = document.getElementById("edit-password").value;
    if (!/^\d*$/.test(passwordInput)) {
      Swal.fire({
        timer: 5000,
        timerProgressBar: true,
        icon: 'error',
        title: 'Invalid Input',
        text: 'Only numerical values are allowed.',
        confirmButtonText: 'OK'
      });
      document.getElementById("edit-password").value = passwordInput.replace(/\D/g, ''); // Remove non-numeric characters
      return false; // Prevent form submission
    }
    return true; // Allow form submission
  }
</script>

</html>