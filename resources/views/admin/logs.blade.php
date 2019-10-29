@extends('layouts.admin')

@section('active.logs','active')

@section('title','Logok')

@section('content')
    <div class="row">
        <h2 class="page-header">Logok</h2>
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">API</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-condensed table-striped">
                        <tr>
                            <th>Bejegyzések száma</th>
                            <td>{{ $api->logs()->count() }}</td>
                        </tr>
                        <tr>
                            <th>Utolsó bejegyzés</th>
                            <td>{{ $api->logs()->max('logs.created_at') }}</td>
                        </tr>
                        <tr>
                            <th>Utolsó felhasználó</th>
                            @if($api->logs->sortBy('logs.created_at')->last()!=null)
                                <td>{{ $api->logs->sortBy('logs.created_at')->last()->user->name }}</td>
                            @else
                                <td><i>N/A</i></td>
                            @endif
                        </tr>
                        <tr>
                            <th>Oldal</th>
                            <td>{{ $api->logs->sortBy('logs.created_at')->last()->page }}</td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <a href="{{ route('admin.logs.api') }}">Megtekint</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">Admin</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-condensed">
                        <tr>
                            <th>Bejegyzések száma</th>
                            <td>{{ $admin->logs()->count() }}</td>
                        </tr>
                        <tr>
                            <th>Utolsó bejegyzés</th>
                            <td>{{ $admin->logs()->max('logs.created_at') }}</td>
                        </tr>
                        <tr>
                            <th>Utolsó felhasználó</th>
                            @if($admin->logs->sortBy('logs.created_at')->last()!=null)
                                <td>{{ $admin->logs->sortBy('logs.created_at')->last()->user->name }}</td>
                            @else
                                <td><i>N/A</i></td>
                            @endif
                        </tr>
                        <tr>
                            <th>Oldal</th>
                            <td>{{ $admin->logs->sortBy('logs.created_at')->last()->page }}</td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <a href="{{ route('admin.logs.admin') }}">Megtekint</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">Moderator</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-condensed">
                        <tr>
                            <th>Bejegyzések száma</th>
                            <td>{{ $moderator->logs()->count() }}</td>
                        </tr>
                        <tr>
                            <th>Utolsó bejegyzés</th>
                            <td>{{ $moderator->logs()->max('logs.created_at') }}</td>
                        </tr>
                        <tr>
                            <th>Utolsó felhasználó</th>
                            @if($moderator->logs->sortBy('logs.created_at')->last()!=null)
                                <td>{{ $moderator->logs->sortBy('logs.created_at')->last()->user->name }}</td>
                            @else
                                <td><i>N/A</i></td>
                            @endif
                        </tr>
                        <tr>
                            <th>Oldal</th>
                            @if($moderator->logs->sortBy('logs.created_at')->last()!=null)
                                <td>{{ $moderator->logs->sortBy('logs.created_at')->last()->page }}</td>
                            @else
                                <td><i>N/A</i></td>
                            @endif
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <a href="{{ route('admin.logs.moderator') }}">Megtekint</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Felhasználó</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-condensed">
                        <tr>
                            <th>Bejegyzések száma</th>
                            <td>{{ $user->logs()->count() }}</td>
                        </tr>
                        <tr>
                            <th>Utolsó bejegyzés</th>
                            <td>{{ $user->logs()->max('logs.created_at') }}</td>
                        </tr>
                        <tr>
                            <th>Utolsó felhasználó</th>
                            @if($user->logs->sortBy('logs.created_at')->last()!=null)
                                <td>{{ $user->logs->sortBy('logs.created_at')->last()->user->name }}</td>
                            @else
                                <td><i>N/A</i></td>
                            @endif
                        </tr>
                        <tr>
                            <th>Oldal</th>
                            <td>{{ $user->logs->sortBy('logs.created_at')->last()->page }}</td>
                        </tr>
                    </table>
                </div>
                <div class="panel-footer">
                    <a href="{{ route('admin.logs.user') }}">Megtekint</a>
                </div>
            </div>
        </div>
    </div>
@endsection
