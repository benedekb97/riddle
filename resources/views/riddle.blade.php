@extends('layouts.home')

@section('title',$riddle->title)

@section('content')
    @if(Auth::user()->id == $riddle->user_id && $riddle->blocked == 1)
        <div class="row">
            <div class="col-md-4 col-md-push-4">
                <div class="alert alert-danger">
                    Vissza lett dobva a riddle-öd.<br>
                    Indoklás: <strong>{{ $riddle->block_reason }}</strong><br><br>
                    Kattints <a href="{{ route('users.riddles',['riddle' => $riddle, 'option' => 'edit']) }}">ide</a> a módosításhoz.
                </div>
            </div>
        </div>
    @endif
    @if(!$approved && Auth::user()->moderator)
        <div class="row">
            <div class="col-md-4 col-md-push-4">
                <div class="alert alert-warning">
                    Ez a riddle elfogadásra vár!<br>
                    <a href="{{ route('riddles.approve',['riddle' => $riddle]) }}" class="btn btn-xs btn-success">
                        <i class="fa fa-tick"></i>y
                    </a>
                    <a data-toggle="modal" data-target="#block" href="#" class="btn btn-xs btn-danger">&times;</a>
                </div>
            </div>
        </div>

    @endif
    @if(!$approved && Auth::user()->id == $riddle->user_id && Auth::user()->moderator != 1)
        <div class="row">
            <div class="col-md-4 col-md-push-4">
                <div class="alert alert-warning">
                    Ez a riddle elfogadásra vár!<br>
                    Amíg nem fogadja el egy moderátor addig nem látja senki :'(
                </div>
            </div>
        </div>
    @endif
    @if($approved && Auth::user()->id == $riddle->user_id)
        <div class="row">
            <div class="col-md-4 col-md-push-4">
                <div class="alert alert-info">
                    <i class="fa fa-info"></i>&nbsp;&nbsp;A saját riddle-ödért nem kapsz pontot :'(
                </div>
            </div>
        </div>
    @endif
    @if($help!=null && $help->help!=null)
        <div class="row">
            <div class="col-md-4 col-md-push-4">
                <div class="alert alert-dismissable alert-success">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    <i class="fa fa-check"></i>&nbsp;&nbsp;Kaptál segítséget!<br>
                    <br>
                    <b>{{ $help->helper->name }}</b> ezt üzente: {{ $help->help }}
                </div>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-md-8 col-md-push-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $riddle->title }}</h3>
                </div>
                <div style="text-align:center;" class="panel-body">
                    <a target="_blank" href="{{ route('riddles.get', ['riddle' => $riddle]) }}">
                        <img style="width:75%; margin:auto;" alt="{{ $riddle->title }}" class="img-responsive" src="{{ route('riddles.get', ['riddle' => $riddle]) }}">
                    </a>
                </div>
                <div class="table-responsive">
                    <table class="table">
                        <tr>
                            <td>Készítette:</td>
                            <td style="text-align:right; font-weight:bold;">{{ $riddle->user->name }} @if($riddle->user->nickname!="") ({{ $riddle->user->nickname }}) @endif</td>
                        </tr>
                        <tr>
                            <td>Nehézség:</td>
                            <td style="text-align:right; font-weight:bold;">{{ $difficulties[$riddle->difficulty-1] }}</td>
                        </tr>
                        @if($solved_by!=null)
                        <tr>
                            <td>Első megoldó:</td>
                            <td style="text-align:right; font-weight:bold;">{{ $solved_by->name }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
                <div class="panel-footer">
                    @if($solved==false && !(!$approved && Auth::user()->moderator) /*&& $riddle->user_id != Auth::user()->id*/)
                    <div class="form-inline">
                        <input type="hidden" id="csrf_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="post_url" id="post_url" value="{{ route('api.riddle.check', ['riddle' => $riddle]) }}">
                        <div class="form-group">
                            <label for="answer">Tipp:</label>
                            <input type="text" name="answer" id="answer" placeholder="Megoldás" class="form-control">
                        </div>
                        <input id="submit" type="button" class="btn btn-primary" value="Próba">
                        <form style="display:inline;" action="{{ route('riddles.hintme', ['riddle' => $riddle]) }}" method="POST">
                            {{ csrf_field() }}
                            <input type="submit" class="btn btn-default" value="Hint kérése ({{ $riddle->hints()->count()-Auth::user()->usedHints($riddle)->count() }})" @if($riddle->hints()->count()-Auth::user()->usedHints($riddle)->count()==0) disabled @endif>
                        </form>
                        @if($show_help)
                          <form id="help_form" style="display:inline;" action="{{ route('riddles.help') }}" method="POST">
                              {{ csrf_field() }}
                              <input data-toggle="tooltip" title="Ha segítséget kérsz nem kapsz pontot erre a riddle-re!" type="submit" class="btn btn-warning" value="Segítsééég!!">
                          </form>
                        @endif
                        @if($show_skip==true)
                          <a href="{{ route('riddles.next')}}" data-toggle="tooltip" title="Max 5 aktív riddle-öd lehet" class="btn btn-warning">Skippidi!</a>
                        @endif
                    </div>
                    @else
                    Megoldás: <i data-toggle="tooltip" title="{{ $riddle->answer }}" class="fa fa-eye"></i>
                    @endif
                </div>
                @if($hints->count()>0)
                    @foreach($hints as $hint)
                        <div class="panel-footer">
                            Hint {{ $hint->number }}: {{ $hint->hint }}
                        </div>
                    @endforeach
                @endif
                @if(!$approved && Auth::user()->moderator)
                    @foreach($riddle->hints as $hint)
                        <div class="panel-footer">
                            Hint {{ $hint->number }}: {{ $hint->hint }}
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection

@section('modals')
    @if(!$reported)
    <form action="{{ route('riddles.duplicate') }}" method="POST">
        <input type="hidden" name="riddle_id" value="{{ $riddle->id }}">
        {{ csrf_field() }}
        <div class="modal fade" id="duplicate">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Mótvá</h4>
                    </div>
                    <div class="modal-body">
                        <p>Válaszd ki, hogy melyik riddle-re hasonlít</p>
                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="similar_to">Riddle:</label>
                                <select class="form-control" name="similar_to" id="similar_to">
                                    <option value="" selected>Válassz ki egy riddle-t</option>
                                    @foreach($solved_riddles as $solved_riddle)
                                        <option value="{{ $solved_riddle->id }}">{{ $solved_riddle->title }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Küldés">
                        <input type="button" class="btn btn-default" value="Mégse" data-dismiss="modal">
                    </div>
                </div>
            </div>
        </div>
    </form>
    @endif
    <form id="next_riddle" action="{{ route('riddles.next') }}" method="GET">
        {{ csrf_field() }}
    </form>
    <div class="modal fade" id="success_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Gratulálok!</h3>
                </div>
                <div class="modal-body">
                    Sikerült megoldanod ezt a rendkívül nehéz riddle-t!
                    <p>Kaptál érte @if(Auth::user()->id == $riddle->user_id || $riddle->approved_by == Auth::user()->id || $helped) <strong>0</strong> @else <strong>{{ $points }}</strong> @endif pontot</p>
                    <p>Így a pontszámod: @if(Auth::user()->id == $riddle->user_id || $riddle->approved_by == Auth::user()->id || $helped) {{ Auth::user()->getPoints() }} @else {{ Auth::user()->getPoints() + $points }} @endif </p>
                </div>
                <div class="modal-footer">
                    <button onclick="$('#success_modal').modal('toggle');" type="button" data-toggle="modal" data-target="#duplicate" class="btn btn-warning">Mótvá?</button>
                    <a class="btn btn-primary" href="{{ route('riddles.current') }}">Következő</a>
                </div>
            </div>
        </div>
    </div>
    @if(!$approved && Auth::user()->moderator)
        <div class="modal fade" id="block">
            <form action="{{ route('riddles.block', ['riddle' => $riddle]) }}" method="POST">
            <div class="modal-dialog">
                {{ csrf_field() }}
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Riddle visszadobása</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="reason">Indoklás</label>
                                <input type="text" class="form-control" name="reason" id="reason" placeholder="Indoklás">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" value="Visszadobás" class="btn btn-danger">
                        <input type="button" data-dismiss="modal" value="Mégse" class="btn btn-default">
                    </div>

                </div>
            </div>
            </form>
        </div>
    @endif
@endsection

@section('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
@endsection
