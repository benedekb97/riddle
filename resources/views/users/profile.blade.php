@extends('layouts.home')

@section('title','Profilom')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <ul class="nav nav-tabs" role="tablist">
                <li class="active">
                    <a href="#">Profilom</a>
                </li>
                <li>
                    <a href="{{ route('users.profile.edit') }}">Szerkesztés</a>
                </li>
            </ul>
            <div style="border-top:0;" class="panel panel-default">
                <div class="table-responsive">
                    <table style="border-top:0;" class="table table-striped">
                        <tr style="border-top:0;">
                            <th style="border-top:0;">Név</th>
                            <td style="border-top:0;">{{ Auth::user()->name }}</td>
                            <th style="border-top:0;">Becenév</th>
                            <td style="border-top:0;">{{ Auth::user()->nickname }}</td>
                        </tr>
                        <tr>
                            <th>Email cím</th>
                            <td>{{ Auth::user()->email }}</td>
                            <th>Pontok</th>
                            <td>{{ Auth::user()->points }}</td>
                        </tr>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
