@extends('layouts.home')

@section('title','Rangsor')

@section('content')
    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped">
                <tr>
                    <th>Helyezés</th>
                    <th>Név</th>
                    <th>Pontszám</th>
                    <th>Megoldott riddle-ök</th>
                </tr>
                @foreach($users as $id => $user)
                    <tr>
                        <td>{{ $id+1 }}.</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->points }}</td>
                        <td>{{ $user->solvedRiddles()->count() }}</td>
                    </tr>
                @endforeach
            </table>
        </div>
    </div>
@endsection
