@extends('layouts.home')

@section('title','Rangsor')

@section('content')
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Ranglista</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <th>Helyezés</th>
                        <th>Név</th>
                        <th>Pontszám</th>
                        <th>Megoldott riddle-ök</th>
                        <th>Feltöltött riddle-ök</th>
                    </tr>
                    <?php $i = 0; ?>
                    @foreach($users as $user)
                        <?php $i++; ?>
                        <tr>
                            <td>{{ $i }}.</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->points }}</td>
                            <td>{{ $user->solvedRiddles()->count() }}</td>
                            <td>{{ $user->approvedRiddles()->count() }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
