<!DOCTYPE html>
<html lang="hu">
    <head>
        <title>@yield('title')</title>
        <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
        <script src="https://kit.fontawesome.com/492a2c0a6b.js" crossorigin="anonymous"></script>
    </head>
    <body>
        @include('nav')

        <div class="container" role="main">
            @yield('content')
        </div>

        @include('footer')
        @yield('modals')
    </body>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
    @yield('scripts')
</html>
