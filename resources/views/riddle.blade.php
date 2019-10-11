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
{{--    @if($approved && Auth::user()->id == $riddle->user_id)--}}
{{--        <div class="row">--}}
{{--            <div class="col-md-4 col-md-push-4">--}}
{{--                <div class="alert alert-info">--}}
{{--                    <i class="fa fa-info"></i>Nem oldhatod meg a saját riddle-öd.--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    @endif--}}
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ $riddle->title }}</h3>
                </div>
                <div style="text-align:center;" class="panel-body">
                    <img style="width:50%; margin:auto;" alt="{{ $riddle->title }}" class="img-responsive" src="{{ route('riddles.get', ['riddle' => $riddle]) }}">
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
                </div>
                <div class="modal-footer">
                    <a class="btn btn-primary" href="{{ route('riddles.next') }}">Következő</a>
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
