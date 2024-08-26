    <!-- DATA TABLE -->
    <link href="{{asset('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css')}}" rel="stylesheet">
    <link href="{{asset('https://cdn.datatables.net/rowreorder/1.5.0/css/rowReorder.bootstrap5.css')}}" rel="stylesheet">
    <link href="{{asset('https://cdn.datatables.net/responsive/3.0.2/css/responsive.dataTables.css')}}" rel="stylesheet">
    <link href="{{asset('https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css')}}" rel="stylesheet">
    <!-- <link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.0.2/css/buttons.dataTables.css"> -->
    <!-- Show password on Add user  -->
    <script defer src="{{asset('js/password.js')}}"></script>

    <!-- Auto Generate password -->
    <script defer src="{{asset('js/passwordGenerator.js')}}"></script>

    <!-- Active Page -->
    <script defer src="{{asset('js/dataTable.js')}}"></script>

    <!-- csrf-token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Font Awesome -->
    <script src="{{asset('https://kit.fontawesome.com/fc44043d19.js')}}" crossorigin="anonymous"></script>

    <!-- BOOTSTRAP 5.3.3 -->
    <link href="{{asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css')}}" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="{{asset('https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js')}}" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- GOOGLE FONTS -->
    <link href="{{asset('https://fonts.googleapis.com/css?family=Karla:400,700|Roboto')}}" rel="stylesheet" />
    <link href="{{asset('plugins/material/css/materialdesignicons.min.css')}}" rel="stylesheet" />
    <link href="{{asset('plugins/simplebar/simplebar.css')}}" rel="stylesheet" />

    <!-- PLUGINS CSS STYLE -->
    <link href="{{asset('plugins/nprogress/nprogress.css')}}" rel="stylesheet" />

    <link href="{{asset('plugins/jvectormap/jquery-jvectormap-2.0.3.css')}}" rel="stylesheet" />

    <!-- Date Picker-->
    <!-- <link href="plugins/daterangepicker/daterangepicker.css" rel="stylesheet" /> -->

    <!-- FLATPCK -->
    <link rel="stylesheet" href="{{asset('https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('https://npmcdn.com/flatpickr/dist/themes/material_blue.css')}}">
    <link rel="stylesheet" href="{{asset('https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css')}}">

    <!-- ChronoLock CSS -->
    <link id="main-css-href" rel="stylesheet" href="{{asset('css/style.css')}}" />

    <!-- FAVICON -->
    <link href="{{asset('/images/chronolock-small.png' )}}" rel="shortcut icon" />

    <!-- CALENDAR -->
    <link href="{{asset('plugins/fullcalendar/core-4.3.1/main.min.css')}}" rel="stylesheet">
    <link href="{{asset('plugins/fullcalendar/daygrid-4.3.0/main.min.css')}}" rel="stylesheet">

    <!-- SWEET ALERT -->
    <script src="{{asset('https://cdn.jsdelivr.net/npm/sweetalert2@11.11.1/dist/sweetalert2.all.min.js')}}"></script>
    <link href="{{asset('https://cdn.jsdelivr.net/npm/sweetalert2@11.11.1/dist/sweetalert2.min.css')}}" rel="stylesheet">

    <!-- EXCEL EXPORT EXTENSION-->
    <script src="{{asset('https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js')}}"></script>

    <script src="{{asset('plugins/nprogress/nprogress.js')}}"></script>
    <script src="{{asset('https://code.jquery.com/jquery-3.7.1.min.js')}}" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>


    @include('profile')
    @include('sweetalert::alert')


    <script defer src="{{asset('js/profile.js')}}"></script>
    <script defer src="{{asset('js/readonly.js')}}"></script>