<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Collapsible sidebar using Bootstrap 3</title>

    <!-- Bootstrap CSS CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.0/css/all.css">

    <!-- Our Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    {{-- Select 2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    @yield('style')
</head>

<body>

    @section('body')
        <div class="wrapper">
            <!-- Sidebar Holder -->
            <nav id="sidebar">
                <div class="sidebar-header">
                    <h3>MEDIA KH LOCAL</h3>
                </div>
                @include('includes.roles.admin')

            </nav>

            <!-- Page Content Holder -->
            <div id="content" style="max-width: 100%;min-width: 70%;width: 100%;padding: 0px;margin: 0px;">

                <nav class="navbar navbar-default" style="margin-bottom: 0px;">
                    <div class="container-fluid">

                        <div class="navbar-header">
                            <button data-mdb-toggle="sidenav" id="sidebarCollapse" data-mdb-target="#sidenav-1"
                                class="btn btn-default border-info" aria-controls="#sidenav-1" aria-haspopup="true">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                    </div>
                </nav>

                <!-- Main Content Area Start -->
                @yield('content')
                <!-- Main Content Area End -->

            </div>
        </div>
    @show

    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/js/bootstrap.min.js"></script>

    {{-- Select 2 --}}
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    {{-- <script src="{{ asset('js/tag-it.js') }}"></script> --}}


    <script type="text/javascript">
        $(document).ready(function() {

            // collapse and uncollapse drop down menu
            $('#sidebarCollapse').on('click', function() {
                $('#sidebar').toggleClass('active');
            });

            $('ul.navbar-nav > li')
                .click(function(e) {
                    $('ul.navbar-nav > li')
                        .removeClass('active');
                    $(this).addClass('active');
                });
        });
    </script>

    @yield('scripts')
</body>

</html>
