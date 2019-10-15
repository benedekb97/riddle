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
                                <td>
                                    <a data-toggle="tooltip" title="Kép megtekintése" class="btn btn-xs btn-primary" href="{{ route('riddles.get', ['riddle' => $riddle]) }}">
                                        <i class="fa fa-image"></i>
                                    </a>
                                </td>
                                <td>
                                    <span data-toggle="tooltip" title="Hintek">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#hints_{{ $riddle->d }}">
                                            <i class="fa fa-lightbulb"></i>
                                        </button>
                                    </span>
                                </td>
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
                            <th>Készítő</th>
                            <th>Hintek</th>
                            <th style="text-align:right";>Műveletek</th>
                        </tr>
                        @foreach($sequenced_riddles as $riddle)
                            <tr>
                                <td>{{ $riddle->number }}</td>
                                <td>{{ $riddle->title }}</td>
                                <td>
                                    <a data-toggle="tooltip" title="Kép megtekintése" class="btn btn-xs btn-primary" href="{{ route('riddles.get', ['riddle' => $riddle]) }}">
                                        <i class="fa fa-image"></i>
                                    </a>
                                </td>
                                <td>{{ $riddle->difficulty }}</td>
                                <td>{{ $riddle->user->name }}</td>
                                <td>
                                    <span data-toggle="tooltip" title="Hintek">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#hints_{{ $riddle->d }}">
                                            <i class="fa fa-lightbulb"></i>
                                        </button>
                                    </span>
                                </td>
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
                                    <a data-toggle="tooltip" title="Törlés" href="{{ route('riddles.delete', ['riddle' => $riddle]) }}" class="btn btn-xs btn-danger">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @foreach($riddles as $riddle)
        <div class="modal fade" id="hints_{{ $riddle->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Hintek</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>Hint</th>
                            </tr>
                            @foreach($riddle->hints as $hint)
                                <tr>
                                    <td>{{ $hint->hint }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Bezárás</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
