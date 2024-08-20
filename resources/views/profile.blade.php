<!DOCTYPE html>

<html lang="en" dir="ltr">

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
              <div class="form-group mt-3">
                <label for="userType">User Type</label>
                <input type="text" class="profile-userType form-control border-dark" value="{{ Auth::user()->userType }}" disabled>
              </div>
            </div>


            <div class="col-lg-6">
              <div class="form-group mb-4">
                <label for="email">Email</label>
                <input type="email" class="profile-email form-control border-dark" value="{{ Auth::user()->email }}" disabled>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group mb-4">
                <label for="idNumber">ID Number</label>
                <input type="text" class="profile-idNumber form-control border-dark" value="{{ Auth::user()->idNumber }}" disabled>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="firstName">First name</label>
                <input type="text" class="profile-firstName form-control border-dark" value="{{ Auth::user()->firstName }}" disabled>
              </div>
            </div>

            <div class="col-lg-6">
              <div class="form-group">
                <label for="lastName">Last name</label>
                <input type="text" class="profile-lastName form-control border-dark" value="{{ Auth::user()->lastName }}" disabled>
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
              <div class="form-group mt-3">
                <label for="edit-userType">User Type</label>
                <input type="text" class="form-control border-dark" id="edit-userType" name="update-userType" readonly>
              </div>
            </div>

            <div class="col-lg-6">
              <ul id="emailError"></ul>
              <div class="form-group mb-4">
                <label for="edit-email">Email</label>
                <input type="email" class="profile_email form-control border-dark" id="edit-email" name="update-email">
              </div>
            </div>

            <div class="col-lg-6">
              <ul id="idNumberError"></ul>
              <div class="form-group mb-4">
                <label for="edit-idNumber">ID Number</label>
                <input type="text" class="profile_idNumber form-control border-dark" id="edit-idNumber" name="update-idNumber">
              </div>
            </div>

            <div class="col-lg-6">
              <ul id="firstNameError"></ul>
              <div class="form-group">
                <label for="edit-firstName">First name</label>
                <input type="text" class="profile_firstName form-control border-dark" id="edit-firstName" name="update-firstName">
              </div>
            </div>

            <div class="col-lg-6">
              <ul id="lastNameError"></ul>
              <div class="form-group">
                <label for="edit-lastName">Last name</label>
                <input type="text" class="profile_lastName form-control border-dark" id="edit-lastName" name="update-lastName">
              </div>
            </div>

          </div>
          <div class="modal-footer px-4">
            <button type="button" class="btn btn-danger btn-pill" data-dismiss="modal">Cancel</button>
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


</html>