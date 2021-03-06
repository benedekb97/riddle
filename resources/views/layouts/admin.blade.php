<!DOCTYPE html>
<html lang="hu">
    <head>
        <title>Admin - @yield('title')</title>

        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}"><link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/dt-1.10.22/datatables.min.css"/>

        <script src="https://kit.fontawesome.com/492a2c0a6b.js" crossorigin="anonymous"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
    @include('admin.nav')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 col-sm-3 sidebar" id="sidebar">
                <div class="sidebar-show" id="sidebar-show">
                    <i class="fa fa-arrow-right"></i>
                </div>
                <ul class="nav nav-sidebar">
                    <li class="@yield('active.index')"><a href="{{ route('admin.index') }}">Áttekintés</a></li>
                    <li class="@yield('active.static_messages')"><a href="{{ route('admin.static_messages') }}">Statikus Üzenetek</a></li>
                    <li class="@yield('active.moderators')">
                        <a href="{{ route('admin.moderators.index') }}">Moderátorok</a>
                    </li>
                    <li class="@yield('active.functions')">
                        <a href="{{ route('admin.functions.index') }}">Speciális funkciók</a>
                    </li>
                    <li class="@yield('active.users')">
                        <a href="{{ route('admin.users.index') }}">Felhasználók</a>
                    </li>
                    <li class="@yield('active.logs')">
                        <a href="{{ route('admin.logs.index') }}">Logok</a>
                    </li>
                    <li class="@yield('active.api')">
                        <a href="{{ route('admin.api.index') }}">API</a>
                    </li>
                    <li class="@yield('active.riddles')">
                        <a href="{{ route('admin.riddles.index') }}">Riddles</a>
                    </li>
                </ul>
            </div>
            <div class="col-md-10 col-sm-9 main col-sm-offset-3 col-md-offset-2">
                @yield('content')
            </div>
        </div>
    </div>
    @yield('modals')
    </body>
<script src="{{ asset('js/jquery.js') }}"></script>
<script src="{{ asset('js/bootstrap.js') }}"></script>
<script src="{{ asset('js/admin.js') }}"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/v/dt/dt-1.10.22/datatables.min.js"></script>

    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@yield('scripts')
</html>
