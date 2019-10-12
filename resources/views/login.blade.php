<!DOCTYPE html>
<html lang="hu">
    <head>
        <title>Bejelentkezés</title>
        <link rel="stylesheet" href="{{ asset('css/main.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}" />
        <link rel="stylesheet" href="{{ asset('css/signin.css') }}" />
        <script src="https://kit.fontawesome.com/492a2c0a6b.js" crossorigin="anonymous"></script>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
    </head>
    <body>
    <div class="container">

        <form class="form-signin" action="{{ route('login.check') }}" method="POST">
            {{ csrf_field() }}
            <h2 class="form-signin-heading">Jelentkezz be!</h2>
            <label for="inputEmail" class="sr-only">Email</label>
            <input name="email" type="email" id="inputEmail" class="form-control" placeholder="Email cím" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input name="password" type="password" id="inputPassword" class="form-control" placeholder="Jelszó" required>
            <button class="btn btn-lg btn-primary btn-block" type="submit">Bejelentkezés</button>
            <a class="btn btn-lg btn-default btn-block" href="#" data-toggle="modal" data-target="#register_modal">Regisztráció</a>
            <br>
            <a class="btn btn-lg btn-default btn-block" href="{{ route('auth_sch_login') }}">Bejelentkezés AuthSCH-val</a>
        </form>

    </div> <!-- /container -->

        <div class="modal fade" id="register_modal">
            <div class="modal-dialog">
                <form action="{{ route('register') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Regisztráció</h4>
                        </div>
                        <div class="modal-body">
                            @if(isset($error) && $error>0 && $error<4)
                            <div class="alert alert-danger">
                                <i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;{{ $error_message }}
                            </div>
                            @endif
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="input-group-addon" for="surname">Vezetéknév</label>
                                    <input type="text" class="form-control" name="surname" id="surname" placeholder="Vezetéknév" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="input-group-addon" for="given_names">Keresztnév</label>
                                    <input type="text" class="form-control" name="given_names" id="given_names" placeholder="Keresztnév" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="input-group-addon" for="email">Email cím</label>
                                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="input-group-addon" for="password">Jelszó</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Jelszó" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="input-group-addon" for="password2">Jelszó megint</label>
                                    <input type="password" class="form-control" id="password2" name="password2" placeholder="Jelszó" required>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-primary" value="Regisztráció">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
    <script src="{{ asset('js/jquery.js') }}"></script>
    <script src="{{ asset('js/bootstrap.js') }}"></script>
@if(isset($error) && $error>0 && $error<4)
    <script>
        $('#register_modal').modal('toggle');
    </script>
@endif
</html>
