@extends('layouts.home')

@section('title','Riddle készítők')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Ridül gyártók</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th>Név</th>
                            <th>Riddle-ök száma</th>
                            <th>Átlag Riddle-nehézség</th>
                            <th>Műveletek</th>
                        </tr>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->approvedRiddles()->count()}} ({{ $user->riddles()->count() }})</td>
                                <td>{{ $user->riddles()->average('difficulty') }}</td>
                                <td>
                                    <a data-toggle="tooltip" title="@if($user->approved) Feltölthet jóváhagyás nélkül @else Jóvá kell minden szarját hagyni @endif" href="{{ route('users.user.modify', ['user' => $user]) }}" class="btn btn-xs @if($user->approved) btn-danger @else btn-primary @endif">
                                        <i class="fa fa-user"></i>
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
