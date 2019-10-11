@extends('layouts.home')

@section('title',$riddle->title)

@section('content')
    @if($riddle->user == Auth::user())

    @endif
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
                    @if($solved==false)
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
@endsection

@section('scripts')
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
    </script>
@endsection
