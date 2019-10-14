<!DOCTYPE html>
<html lang="hu">
    <head>
        <title>Admin - @yield('title')</title>

        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
        <script src="https://kit.fontawesome.com/492a2c0a6b.js" crossorigin="anonymous"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
    @include('admin.nav')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 col-sm-3 sidebar">
                <ul class="nav nav-sidebar">
                    <li class="@yield('active.index')"><a href="{{ route('admin.index') }}">Áttekintés</a></li>
                    <li class="@yield('active.static_messages')"><a href="{{ route('admin.static_messages') }}">Statikus Üzenetek</a></li>
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
@yield('scripts')
</html>