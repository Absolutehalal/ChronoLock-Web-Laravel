<!-- Card Offcanvas -->
<div class="card card-offcanvas" id="contact-off">
    <div class="card-header">
        <h2>Contacts</h2>
        <a href="#" class="btn btn-primary btn-pill px-4">Add New</a>
    </div>
    <div class="card-body">
        <div class="mb-4">
            <input type="text" class="form-control form-control-lg form-control-secondary rounded-0" placeholder="Search contacts..." />
        </div>

        <div class="media media-sm">
            <div class="media-sm-wrapper">
                <a href="user-profile.php">
                    <img src="images/user/user-sm-01.jpg" alt="User Image" />
                    <span class="active bg-primary"></span>
                </a>
            </div>
            <div class="media-body">
                <a href="user-profile.php">
                    <span class="title">Selena Wagner</span>
                    <span class="discribe">Designer</span>
                </a>
            </div>
        </div>

        <div class="media media-sm">
            <div class="media-sm-wrapper">
                <a href="user-profile.php">
                    <img src="images/user/user-sm-02.jpg" alt="User Image" />
                    <span class="active bg-primary"></span>
                </a>
            </div>
            <div class="media-body">
                <a href="user-profile.php">
                    <span class="title">Walter Reuter</span>
                    <span>Developer</span>
                </a>
            </div>
        </div>

        <div class="media media-sm">
            <div class="media-sm-wrapper">
                <a href="user-profile.php">
                    <img src="images/user/user-sm-03.jpg" alt="User Image" />
                </a>
            </div>
            <div class="media-body">
                <a href="user-profile.php">
                    <span class="title">Larissa Gebhardt</span>
                    <span>Cyber Punk</span>
                </a>
            </div>
        </div>

        <div class="media media-sm">
            <div class="media-sm-wrapper">
                <a href="user-profile.php">
                    <img src="images/user/user-sm-04.jpg" alt="User Image" />
                </a>
            </div>
            <div class="media-body">
                <a href="user-profile.php">
                    <span class="title">Albrecht Straub</span>
                    <span>Photographer</span>
                </a>
            </div>
        </div>

        <div class="media media-sm">
            <div class="media-sm-wrapper">
                <a href="user-profile.php">
                    <img src="images/user/user-sm-05.jpg" alt="User Image" />
                    <span class="active bg-danger"></span>
                </a>
            </div>
            <div class="media-body">
                <a href="user-profile.php">
                    <span class="title">Leopold Ebert</span>
                    <span>Fashion Designer</span>
                </a>
            </div>
        </div>

        <div class="media media-sm">
            <div class="media-sm-wrapper">
                <a href="user-profile.php">
                    <img src="images/user/user-sm-06.jpg" alt="User Image" />
                    <span class="active bg-primary"></span>
                </a>
            </div>
            <div class="media-body">
                <a href="user-profile.php">
                    <span class="title">Selena Wagner</span>
                    <span>Photographer</span>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function() {
        jQuery('input[name="dateRange"]').daterangepicker({
            autoUpdateInput: false,
            singleDatePicker: true,
            locale: {
                cancelLabel: "Clear",
            },
        });
        jQuery('input[name="dateRange"]').on(
            "apply.daterangepicker",
            function(ev, picker) {
                jQuery(this).val(picker.startDate.format("MM/DD/YYYY"));
            }
        );
        jQuery('input[name="dateRange"]').on(
            "cancel.daterangepicker",
            function(ev, picker) {
                jQuery(this).val("");
            }
        );
    });
</script>

<script src="js/timedate.js"></script>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/simplebar/simplebar.min.js"></script>
<script src="https://unpkg.com/hotkeys-js/dist/hotkeys.min.js"></script>

<script src="plugins/apexcharts/apexcharts.js"></script>

<script src="plugins/jvectormap/jquery-jvectormap-2.0.3.min.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-world-mill.js"></script>
<script src="plugins/jvectormap/jquery-jvectormap-us-aea.js"></script>

<script src="plugins/daterangepicker/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>

<script src="js/mono.js"></script>
<script src="js/chart.js"></script>
<script src="js/map.js"></script>
<script src="js/custom.js"></script>

<script src="plugins/fullcalendar/core-4.3.1/main.min.js"></script>
<script src="plugins/fullcalendar/daygrid-4.3.0/main.min.js"></script>
<script src="js/calendar.js"></script>

<script src="plugins/prism/prism.js"></script>

<!-- DataTable-->
<script defer src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
<script defer src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script defer src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js"></script>
<script defer src="https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js"></script>
<script defer src="https://cdn.datatables.net/rowreorder/1.5.0/js/rowReorder.bootstrap5.js"></script>
<script defer src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
<script defer src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.js"></script>
<script defer src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>


<!--  -->
</body>

</html