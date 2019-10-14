@extends('layouts.home')

@section('title','Elfogadásra váró riddle-ök')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Elfogadásra váró riddle-ök</h3>
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
                            <th>Hintek</th>
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
                                <td>
                                    <span data-toggle="tooltip" title="Hintek">
                                        <button type="button" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#hints_{{ $riddle->id }}">
                                            <i class="fa fa-lighbulb"></i>
                                        </button>
                                    </span>
                                </td>
                                <td>
                                    <span data-toggle="tooltip" title="Elfogadás">
                                        <a href="{{ route('riddles.approve', ['riddle' => $riddle, 'return' => 'mod']) }}" class="btn btn-xs btn-success">
                                            <i class="fa fa-check"></i>
                                        </a>
                                    </span>
                                    <span data-toggle="tooltip" title="Elutasítás">
                                        <a data-toggle="modal" data-target="#block_{{ $riddle->id }}" href="#" class="btn btn-xs btn-danger">
                                            <i class="fa fa-times"></i>
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

@section('modals')
    @foreach($riddles as $riddle)
        <div class="modal fade" id="hints_{{ $riddle->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Hintek</h4>
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
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
                        <button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="block_{{ $riddle->id }}">
            <form action="{{ route('riddles.block', ['riddle' => $riddle, 'return' => 'mod']) }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" type="button" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Riddle elutasítása</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="input-group-addon" for="reason">Indoklás:</label>
                                    <input type="text" class="form-control" placeholder="Indoklás" id="reason" name="reason">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-danger" value="Elutasít">
                            <input type="button" class="btn btn-default" value="Mégse" data-dismiss="modal">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endforeach
@endsection
