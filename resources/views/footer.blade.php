<script>
    document.addEventListener('DOMContentLoaded', function() {
        var input = document.getElementById('excel-file');
        var label = document.querySelector('label[for="excel-file"]');

        input.addEventListener('change', function(event) {
            var fileName = event.target.files[0] ? event.target.files[0].name : 'Choose file';
            label.textContent = fileName;
        });
    });
</script>
<!-- 
<script>
    flatpickr("input[datetime-local]", {});
</script> -->

<!--Flatpckr-->
<script src="{{asset('https://cdn.jsdelivr.net/npm/flatpickr')}}"></script>
<script src="{{asset('https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js')}}"></script>
<script src="{{asset('js/timedate.js')}}"></script>


<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<script src="{{asset('plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

<script src="{{asset('plugins/simplebar/simplebar.min.js')}}"></script>
<script src="{{asset('https://unpkg.com/hotkeys-js/dist/hotkeys.min.js')}}"></script>

<script src="{{asset('plugins/apexcharts/apexcharts.js')}}"></script>

<script src="{{asset('plugins/jvectormap/jquery-jvectormap-2.0.3.min.js')}}"></script>
<script src="{{asset('plugins/jvectormap/jquery-jvectormap-world-mill.js')}}"></script>
<script src="{{asset('plugins/jvectormap/jquery-jvectormap-us-aea.js')}}"></script>

<script src="plugins/circle-progress/circle-progress.js"></script>

<script src="{{asset('js/mono.js')}}"></script>
<script src="{{asset('js/chart.js')}}"></script>
<script src="{{asset('js/map.js')}}"></script>
<script src="{{asset('js/custom.js')}}"></script>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<!-- <script src="{{asset('plugins/fullcalendar/core-4.3.1/main.min.js')}}"></script>
<script src="{{asset('plugins/fullcalendar/daygrid-4.3.0/main.min.js')}}"></script> -->
<script src="{{asset('plugins/daterangepicker/moment.min.js')}}"></script>
<script src="{{asset('js/calendar.js')}}"></script>

<script src="{{asset('plugins/prism/prism.js')}}"></script>

<!-- DataTable-->
<script defer src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/js/bootstrap.bundle.min.js')}}"></script>
<script defer src="{{asset('https://cdn.datatables.net/2.0.8/js/dataTables.js')}}"></script>
<script defer src="{{asset('https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap5.js')}}"></script>
<script defer src="{{asset('https://cdn.datatables.net/rowreorder/1.5.0/js/dataTables.rowReorder.js')}}"></script>
<script defer src="{{asset('https://cdn.datatables.net/rowreorder/1.5.0/js/rowReorder.bootstrap5.js')}}"></script>
<script defer src="{{asset('https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js')}}"></script>
<script defer src="{{asset('https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.js')}}"></script>
<script defer src="{{asset('https://cdn.datatables.net/2.0.8/js/dataTables.js')}}"></script>

<!-- Include DataTables Search Highlight Plugin -->
<script src="{{asset('https://cdn.datatables.net/plug-ins/1.10.21/features/searchHighlight/dataTables.searchHighlight.min.js')}}"></script>
<!-- Include Mark.js -->
<script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/mark.min.js')}}"></script>
<script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/mark.js/8.11.1/jquery.mark.min.js')}}"></script>

<!--  -->
</body>

</html