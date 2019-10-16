@extends('layouts.home')

@section('title','Összes Eddigi Ridli')

@section('content')
    <div class="row">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Megoldott riddle-jeim</h3>
            </div>
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr>
                        <th>Cím</th>
                        <th>Megoldás</th>
                        <th>Kép</th>
                        <th>Mikor</th>
                        <th>Hány próbálkozás</th>
                        <th>Felhasznált hintek</th>
                        <th>Nehézség</th>
                    </tr>
                    @foreach($riddles as $riddle)
                        <tr>
                            <td>{{ $riddle->title }}</td>
                            <td>
                                <i data-toggle="tooltip" title="{{ $riddle->answer }}" class="fa fa-eye"></i>
                            </td>
                            <td>
                                <a class="btn btn-xs btn-primary" href="{{ route('riddles.get', ['riddle' => $riddle]) }}" target="_blank">
                                    <i class="fa fa-image"></i>
                                </a>
                            </td>
                            <td>
                                {{ Auth::user()->solvedRiddles()->where('riddle_id',$riddle->id)->first()->created_at }}
                            </td>
                            <td>{{ $riddle->guesses()->where('user_id',Auth::user()->id)->count() }}</td>
                            <td>{{ Auth::user()->usedHints()->where('riddle_id',$riddle->id)->count() }}</td>
                            <td>{{ $difficulties[$riddle->difficulty-1] }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection
