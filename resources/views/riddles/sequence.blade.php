@extends('layouts.home')

@section('title','Sorrend')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Nem sorrendbe állított riddle-ök</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Cím</th>
                            <th>Kép</th>
                            <th>Hintek</th>
                            <th style="text-align:right;">Műveletek</th>
                        </tr>
                        @foreach($unsequenced_riddles as $riddle)
                            <tr>
                                <td>{{ $riddle->title }}</td>
                                <td></td>
                                <td>{{ $riddle->hints()->count() }}</td>
                                <td style="text-align:right;">
                                    <a data-toggle="tooltip" title="Sorrendhez hozzáadás" href="{{ route('riddles.sequence.add', ['riddle' => $riddle]) }}" class="btn btn-sm btn-default">
                                        <i class="fa fa-caret-down"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Jelenlegi sorrend</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Szám</th>
                            <th>Cím</th>
                            <th>Kép</th>
                            <th>Nehézség</th>
                            <th>Hintek száma</th>
                            <th style="text-align:right";>Műveletek</th>
                        </tr>
                        @foreach($sequenced_riddles as $riddle)
                            <tr>
                                <td>{{ $riddle->number }}</td>
                                <td>{{ $riddle->title }}</td>
                                <td></td>
                                <td>{{ $riddle->difficulty }}</td>
                                <td>{{ $riddle->hints()->count() }}</td>
                                <td style="text-align:right;">
                                    @if($riddle->number != 1)
                                        <a data-toggle="tooltip" title="Előre" href="{{ route('riddles.sequence.down',['riddle' => $riddle]) }}" class="btn btn-xs btn-default">
                                            <i class="fa fa-caret-down"></i>
                                        </a>
                                    @endif
                                    @if($riddle->number != $last_number)
                                    <a data-toggle="tooltip" title="Hátra" href="{{ route('riddles.sequence.up', ['riddle' => $riddle]) }}" class="btn btn-xs btn-default">
                                        <i class="fa fa-caret-up"></i>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
