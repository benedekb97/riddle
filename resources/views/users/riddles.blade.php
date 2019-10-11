@extends('layouts.home')

@section('title','Riddle-jeim')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Saját riddle-ök</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Szám</th>
                            <th>Cím</th>
                            <th>Megoldás</th>
                            <th>Kép</th>
                            <th>Megoldva</th>
                            <th>Műveletek</th>
                        </tr>
                        @foreach($riddles as $riddle)
                            <tr>
                                <td>$riddle->number</td>
                                <td>{{ $riddle->title }}</td>
                                <td>{{ $riddle->answer }}</td>
                                <td><a target="_blank" data-toggle="tooltip" title="Kép megtekintése" class="btn btn-sm btn-primary" href="{{ route('riddles.get', ['riddle' => $riddle]) }}"><i class="fa fa-image"></i></a></td>
                                <td>{{ $riddle->solvedBy()->count() }}</td>
                                <td>
                                    <a href="#" data-toggle="tooltip" title="Információk">
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#info_{{ $riddle->id }}">
                                            <i class="fa fa-info"></i>
                                        </button>
                                    </a>
                                    <a href="#" data-toggle="tooltip" title="Hintek">
                                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#hints_{{ $riddle->id }}">
                                            <i class="fa fa-info"></i>
                                        </button>
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

@section('scripts')
    @foreach($riddles as $riddle)
        <script>
            var slider = document.getElementById("difficulty{{ $riddle->id }}");
            var output = document.getElementById("diff_value{{ $riddle->id }}");

            var difficulties = ['Egy perces riddle','Easy','Elgondolkodtató','Nehéz','Kenyér'];

            console.log(slider.value);

            output.innerHTML = difficulties[slider.value-1]; // Display the default slider value

            // Update the current slider value (each time you drag the slider handle)
            slider.oninput = function() {
                output.innerHTML = difficulties[this.value-1];
            }
        </script>
    @endforeach
@endsection

@section('modals')
    @foreach($riddles as $riddle)
        <div class="modal fade" id="edit_{{ $riddle->id }}">
            <form action="{{ route('riddles.edit',['riddle' => $riddle]) }}" method="POST">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">{{ $riddle->title }} - Szerkesztés</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="input-group-addon" for="title">Cím</label>
                                    <input type="text" name="title" class="form-control" id="title" value="{{ $riddle->title }}" placeholder="Cím">
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="input-group-addon" for="answer{{ $riddle->id }}">Megoldás</label>
                                    <input type="text" class="form-control" id="answer{{ $riddle->id }}" name="answer{{ $riddle->id }}" value="{{ $riddle->answer }}" placeholder="Megoldás">
                                </div>
                            </div>
                            <div class="form-group">
                                <div style="width:33%;" class="input-group">
                                    <label for="difficulty{{ $riddle->id }}">Nehézség: <span style="font-style:italic;" id="diff_value{{ $riddle->id }}">1</span></label>
                                    <input value="{{ $riddle->difficulty }}" class="slider" type="range" name="difficulty{{ $riddle->id }}" id="difficulty{{ $riddle->id }}" min="1" max ="5">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal fade" id="info_{{ $riddle->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{ $riddle->title }} - Információk</h4>
                    </div>
                </div>

            </div>
        </div>
        <div class="modal fade" id="hints_{{ $riddle->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">{{ $riddle->title }} - Hintek</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th style="text-align:right;">Szám</th>
                                <th>Hint</th>
                                <th>Műveletek</th>
                            </tr>
                            @if($riddle->hints()->count()==0)
                                <tr>
                                    <td style="text-align:center; font-style:italic;" colspan="3">Nincsenek még hintek!</td>
                                </tr>
                            @endif
                            @foreach($riddle->hints as $hint)
                                <tr>
                                    <td style="text-align:right;">{{ $hint->number }}</td>
                                    <td>{{ $hint->hint }}</td>
                                    <td>
                                        <span data-toggle="tooltip" title="Törlés">
                                            <a data-toggle="modal" data-target="#delete_hint_{{ $hint->id }}" href="#" class="btn btn-sm btn-danger">&times;</a>
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="modal-footer">
                        <form class="form-inline" action="{{ route('riddles.hint.add', ['riddle' => $riddle]) }}" method="POST">
                            <div class="form-group">
                                <label for="hint">Új Hint:</label>
                                <input type="text" class="form-control" name="hint" id="hint" placeholder="Hint">
                                <input type="submit" class="btn btn-success" value="Mentés">
                                {{ csrf_field() }}
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    @endforeach
    @foreach($riddles as $riddle)
        @foreach($riddle->hints as $hint)
            <div class="modal fade" id="delete_hint_{{ $hint->id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Biztosan törlöd ezt a hintet?</h4>
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('riddles.hint.delete', ['riddle' => $riddle, 'hint' => $hint]) }}" class="btn btn-danger">Törlés</a>
                            <button type="button" data-dismiss="modal" class="btn btn-default">Mégse</button>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
@endsection

@section('scripts')
    <script>
        @if($view_hints!=null && $option == 'hint')
            $('#hints_{{ $view_hints->id }}').modal('toggle');
        @endif
    </script>
@endsection
