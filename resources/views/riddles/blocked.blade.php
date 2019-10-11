@extends('layouts.home')

@section('title','Tiltott Riddle-k')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Tiltott riddle-ök</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Szám</th>
                            <th>Cím</th>
                            <th>Megoldás</th>
                            <th>Nehézség</th>
                            <th>Kép</th>
                            <th>Készítő</th>
                            <th>Tiltás oka</th>
                            <th>Műveletek</th>
                        </tr>
                        @foreach($riddles as $riddle)
                            <tr>
                                <td>{{ $riddle->id }}</td>
                                <td>{{ $riddle->title }}</td>
                                <td>{{ $riddle->answer }}</td>
                                <td>{{ $riddle->difficulty }}</td>
                                <td>
                                    <a target="_blank" class="btn btn-xs btn-primary" href="{{ route('riddles.get',['riddle' => $riddle]) }}">
                                        <i class="fa fa-image"></i>
                                    </a>
                                </td>
                                <td>{{ $riddle->user->name }}</td>
                                <td>{{ $riddle->block_reason }}</td>
                                <td>
                                    <span data-toggle="tooltip" title="Engedélyezés">
                                        <a href="{{ route('riddles.approve', ['riddle' => $riddle, 'return' => 'blocked']) }}" class="btn btn-xs btn-success">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
