@extends('layouts.home')

@section('title','API Leírás')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>/api/login</b> - Login</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed table-hover">
                        <tr>
                            <th>Típus</th>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <th>Leírás</th>
                            <td>Küldesz emailt és jelszót, visszaadja a felhasználó API kulcsát ha helyes a kombináció</td>
                        </tr>
                        <tr>
                            <th>Paraméterek</th>
                            <td>
                                <ul>
                                    <li>Email</li>
                                    <li>Jelszó</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>Visszaad</th>
                            <td>
                                <ul>
                                    <li>api_key</li>
                                    <li>success</li>
                                    <li>password</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>/api/register</b> - Register</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover table-condensed">
                        <tr>
                            <th>Típus</th>
                            <th>POST</th>
                        </tr>
                        <tr>
                            <th>Leírás</th>
                            <td>Küldesz emailt, két jelszót, keresztnevet és utónevet. Ha nincs ilyen email cím a db-ben, megegyezik a két jelszó, és a jelszó hosszabb mint 8 karakter akkor kapsz egy api kulcsot, ellenkező esetben a reason mezőben megkapod hogy miért szar és success=false.</td>
                        </tr>
                        <tr>
                            <th>Paraméterek</th>
                            <td>
                                <ul>
                                    <li>email</li>
                                    <li>password</li>
                                    <li>password2</li>
                                    <li>given_names</li>
                                    <li>surname</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>Visszaad</th>
                            <td>
                                <ul>
                                    <li>api_key</li>
                                    <li>success</li>
                                    <li>reason</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>/api/user</b> - Felhasználó adatai</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-condensed table-hover table-striped">
                        <tr>
                            <th>Típus</th>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <th>Leírás</th>
                            <td>Küldesz egy api_key-t, és visszaadja a felhasználó adatait.</td>
                        </tr>
                        <tr>
                            <th>Paraméterek</th>
                            <td>
                                <ul>
                                    <li>api_key</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>Visszaad</th>
                            <td>
                                <ul>
                                    <li>id</li>
                                    <li>name</li>
                                    <li>surname</li>
                                    <li>given_names</li>
                                    <li>email</li>
                                    <li>internal_id</li>
                                    <li>points</li>
                                    <li>moderator</li>
                                    <li>approved</li>
                                    <li>created_at</li>
                                    <li>updated_at</li>
                                    <li>nickname</li>
                                    <li>admin</li>
                                    <li>blocked</li>
                                    <li>api_key</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <form action="{{ route('api.user') }}" method="POST">
                        <input type="hidden" name="api_key" value="{{ Auth::user()->api_key }}">
                        <input type="submit" value="Példa" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>/api/next</b> - Következő riddle</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-condensed table-bordered table-hover">
                        <tr>
                            <th>Típus</th>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <th>Leírás</th>
                            <td>Küldesz api kulcsot, és ha épp nincs riddle-je a felhasználónak és van soron következő riddle akkor success=true, egyébként success=false</td>
                        </tr>
                        <tr>
                            <th>Paraméterek</th>
                            <td>
                                <ul>
                                    <li>api_key</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>Visszaadja</th>
                            <td>
                                <ul>
                                    <li>success</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <form action="{{ route('api.next') }}" method="POST">
                        <input type="hidden" name="api_key" value="{{ Auth::user()->api_key }}">
                        <input type="submit" value="Példa" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>/api/check</b> - megoldás tesztelése</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover">
                        <tr>
                            <th>Típus</th>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <th>Leírás</th>
                            <td>Küldesz api kulcsot és megoldást, ha a megoldás jó és az utolsó próba után 1mp eltelt akkor succes=true, egyébként succes=false</td>
                        </tr>
                        <tr>
                            <th>Paraméterek</th>
                            <td>
                                <ul>
                                    <li>api_key</li>
                                    <li>answer</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>Visszaadja</th>
                            <td>
                                <ul>
                                    <li>success</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <form class="form-inline" action="{{ route('api.check') }}" method="POST">
                        <input type="hidden" name="api_key" value="{{ Auth::user()->api_key }}">
                        <input type="text" class="form-control" name="answer" placeholder="answer">
                        <input type="submit" class="btn btn-primary" value="Példa">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>/api/riddle</b> - Aktuális riddle</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover">
                        <tr>
                            <th>Típus</th>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <th>Leírás</th>
                            <td>Adsz api_key-t és visszaküldi az aktuális riddle adatait</td>
                        </tr>
                        <tr>
                            <th>Paraméterek</th>
                            <td>
                                <ul>
                                    <li>api_key</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>Visszaadja</th>
                            <td>
                                <ul>
                                    <li>id</li>
                                    <li>title</li>
                                    <li>creator</li>
                                    <li>difficulty</li>
                                    <li>hints</li>
                                    <li>image</li>
                                    <li>unused_hints</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <form action="{{ route('api.riddle') }}" method="POST">
                        <input type="hidden" name="api_key" value="{{ Auth::user()->api_key }}">
                        <input type="submit" class="btn btn-primary" value="Példa">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>/api/home</b> - Főoldal</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover">
                        <tr>
                            <th>Típus</th>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <th>Leírás</th>
                            <td>Küldesz api kulcsot, visszakapsz egy üdvözlő szöveget</td>
                        </tr>
                        <tr>
                            <th>Paraméterek</th>
                            <td>
                                <ul>
                                    <li>api_key</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>Visszaadja</th>
                            <td>
                                <ul>
                                    <li>home_text</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <form action="{{ route('api.home') }}" method="POST">
                        <input type="hidden" name="api_key" value="{{ Auth::user()->api_key }}">
                        <input type="submit" class="btn btn-primary" value="Példa">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>/api/hasHintsLeft</b> - Van még hint?</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-condensed table-bordered table-hover">
                        <tr>
                            <th>Típus</th>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <th>Leírás</th>
                            <td>Küldesz api kulcsot, és visszakapod hogy van-e még hintje ahhoz a riddle-höz ami épp aktuális</td>
                        </tr>
                        <tr>
                            <th>Paraméterek</th>
                            <td>
                                <ul>
                                    <li>api_key</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>Visszaadja</th>
                            <td>
                                <ul>
                                    <li>has_hints</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <form action="{{ route('api.hasHintsLeft') }}" method="POST">
                        <input type="hidden" name="api_key" value="{{ Auth::user()->api_key }}">
                        <input type="submit" class="btn btn-primary" value="Példa">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>/api/previous</b> - Eddigi riddle-ök</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover">
                        <tr>
                            <th>Típus</th>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <th>Leírás</th>
                            <td>Küldesz api kulcsot, és visszakapsz egy tömböt amiben benne vannak az eddigi megoldott riddle-ök adatai</td>
                        </tr>
                        <tr>
                            <th>Paraméterek</th>
                            <td>
                                <ul>
                                    <li>api_key</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>Visszaadja</th>
                            <td>
                                <ul>
                                    <li>array:
                                        <ul>
                                            <li>title</li>
                                            <li>answer</li>
                                            <li>image</li>
                                            <li>solved_at</li>
                                            <li>tries</li>
                                            <li>used_hints</li>
                                            <li>difficulty</li>
                                        </ul></li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <form action="{{ route('api.previous') }}" method="POST">
                        <input type="hidden" name="api_key" value="{{ Auth::user()->api_key }}">
                        <input type="submit" value="Példa" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>/api/scores</b> - Rangsor</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-condensed table-bordered table-hover">
                        <tr>
                            <th>Típus</th>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <th>Leírás</th>
                            <td>Küldesz api kulcsot, és visszakapod egy tömbben az összes felhasználó adatát (pontok szerint csökkenő sorrendbe rendezve)</td>
                        </tr>
                        <tr>
                            <th>Paraméterek</th>
                            <td>
                                <ul>
                                    <li>api_key</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>Visszaadja</th>
                            <td>
                                <ul>
                                    <li>array:</li>
                                    <li>
                                        <ul>
                                            <li>rank</li>
                                            <li>name</li>
                                            <li>points</li>
                                            <li>riddles</li>
                                            <li>uploaded_riddles</li>
                                        </ul>
                                    </li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <form action="{{ route('api.scores') }}" method="POST">
                        <input type="hidden" name="api_key" value="{{ Auth::user()->api_key }}">
                        <input type="submit" value="Példa" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><b>/api/nextHint</b> - Következő hint</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-condensed table-hover">
                        <tr>
                            <th>Típus</th>
                            <td>POST</td>
                        </tr>
                        <tr>
                            <th>Leírás</th>
                            <td>Küldesz api kulcsot, és ha van még hint az adott riddle-höz visszakapod a hintet, ha nincs akkor success=false</td>
                        </tr>
                        <tr>
                            <th>Paraméterek</th>
                            <td>
                                <ul>
                                    <li>api_key</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>Visszaadja:</th>
                            <td>
                                <ul>
                                    <li>hint</li>
                                    <li>success</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <form action="{{ route('api.nextHint') }}" method="POST">
                        <input type="hidden" name="api_key" value="{{ Auth::user()->api_key }}">
                        <input type="submit" class="btn btn-primary" value="Példa">
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
