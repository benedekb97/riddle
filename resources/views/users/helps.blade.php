@extends('layouts.home')

@section('title','Segítségkérések')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Segítségkérések</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Kérte</th>
                            <th>Riddle</th>
                            <th>Felhasznált hintek</th>
                            <th>Próbálkozások</th>
                            <th>Műveletek</th>
                        </tr>
                        @foreach($helps as $help)
                            <tr>
                                <td>{{ $help->user->name }}</td>
                                <td>
                                    <span data-toggle="tooltip" title="Riddle">
                                        <button type="button" data-toggle="modal" data-target="#help_riddle_{{ $help->id }}" class="btn btn-xs btn-primary">
                                            <i class="fa fa-info"></i>
                                        </button>
                                    </span>
                                </td>
                                <td>{{ $help->riddle->hints()->count() }}/{{ $help->user->hints()->where('riddle_id',$help->riddle->id)->count() }}</td>
                                <td>
                                    <span data-toggle="tooltip" title="Próbálkozások">
                                        <button type="button" data-toggle="modal" data-target="#help_guesses_{{ $help->id }}" class="btn btn-xs btn-primary">
                                            <i class="fa fa-question-circle"></i>
                                        </button>
                                    </span>
                                </td>
                                <td>
                                    <span data-toggle="tooltip" title="Segítség küldése">
                                        <button type="button" data-toggle="modal" data-target="#send_help_{{ $help->id }}" class="btn btn-xs btn-success">
                                            <i class="fa fa-check"></i>
                                        </button>
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
    @foreach($helps as $help)
        <div class="modal fade" id="help_riddle_{{ $help->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h4>Riddle</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <td colspan="2">
                                    <img src="{{ route('riddles.get', ['riddle' => $help->riddle->id]) }}" alt="{{ $help->riddle->title }}" style="width:100%;">
                                </td>
                            </tr>
                            <tr>
                                <th>Cím</th>
                                <td>{{ $help->riddle->title }}</td>
                            </tr>
                            @if($help->riddle->user_id==Auth::user()->id || $help->riddle->approved_by == Auth::user()->id || Auth::user()->solvedRiddles()->get()->contains($help->riddle))
                            <tr>
                                <th>Megoldás</th>
                                <td>{{ $help->riddle->answer }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" type="button" data-dismiss="modal">Bezárás</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="help_guesses_{{ $help->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h4>Próbálkozások</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th style="text-align:right;">Mit</th>
                                <th>Hányszor</th>
                            </tr>
                            @foreach($help->user->guesses()->where('riddle_id',$help->riddle->id)->get() as $guess)
                                <tr>
                                    <td style="text-align:right;">{{ $guess->guess }}</td>
                                    <td>{{ $guess->count }}</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-default" data-dismiss="modal" type="button">Bezárás</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="send_help_{{ $help->id }}">
            <div class="modal-dialog">
                <form action="{{ route('users.help.send') }}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="help" value="{{ $help->id }}">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal" type="button">&times;</button>
                            <h4>Segítség küldése</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="input-group-addon" for="help_text">Üzenet:</label>
                                    <input type="text" name="help_text" id="help_text" placeholder="Üzenet" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" value="Küldés" class="btn btn-success">
                            <input type="button" class="btn btn-default" value="Mégse" data-dismiss="modal">
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endforeach
@endsection
