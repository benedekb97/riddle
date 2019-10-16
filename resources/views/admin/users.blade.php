@extends('layouts.admin')

@section('title','Felhasználók')

@section('active.users','active')

@section('content')
    <div class="row">
        <h2 class="page-header">Felhasználók</h2>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Összes felhasználó</h3>
                </div>
                <div class="table-responsive">
                    <table id="users_table" class="table table-striped">
                        <tr>
                            <th>Email</th>
                            <th>Név</th>
                            <th>Megoldott riddle-ök</th>
                            <th>Feltöltött riddle-ök</th>
                            <th>Moderátor</th>
                            <th>Adminisztrátor</th>
                            <th>AuthSCH</th>
                            <th>Jelszó</th>
                            <th>Tiltott riddle-ök</th>
                            <th>Átlag riddle nehézség</th>
                            <th>Műveletek</th>
                        </tr>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->solvedRiddles()->count() }}</td>
                                <td>{{ $user->riddles()->count() }}</td>
                                <td>
                                    @if($user->moderator)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa fa-times"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($user->admin)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa fa-times"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($user->internal_id!=null)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa fa-times"></i>
                                    @endif
                                </td>
                                <td>
                                    @if($user->password!=null)
                                        <i class="fa fa-check"></i>
                                    @else
                                        <i class="fa fa-times"></i>
                                    @endif
                                </td>
                                <td>
                                    {{ $user->riddles()->where('blocked','1')->count() }}
                                </td>
                                <td>
                                    {{ $user->riddles()->average('difficulty') }}
                                </td>
                                <td>
                                    @if(!$user->blocked)
                                    <span data-toggle="tooltip" title="Blokkolnám">
                                        <button type="button" class="btn btn-xs btn-danger" data-toggle="modal" data-target="#block_{{ $user->id }}">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </span>
                                    @else
                                        <span data-toggle="tooltip" title="Unblokkolnám">
                                        <button type="button" class="btn btn-xs btn-success" data-toggle="modal" data-target="#unblock_{{ $user->id }}">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </span>
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

@section('modals')
    @foreach($users as $user)
        @if($user->blocked)
            <div class="modal fade" id="unblock_{{ $user->id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal" type="button">&times;</button>
                            <h4 class="modal-title">Felhasználó oltalmazása (lol)</h4>
                        </div>
                        <div class="modal-body">
                            Biztos feloldod a felhasználót?
                        </div>
                        <div class="modal-footer">
                            <a href="{{ route('admin.users.unblock', ['user' => $user]) }}" class="btn btn-primary">Igen!</a>
                            <button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="modal fade" id="block_{{ $user->id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal" type="button">&times;</button>
                            <h4 class="modal-title">Felhasználó blokkolása</h4>

                        </div>
                        <div class="modal-body">
                            Biztos le akarod tiltani a felhasználót az oldal használatától?
                        </div>
                        <div class="modal-footer">
                            <a class="btn btn-primary" href="{{ route('admin.users.block', ['user' => $user]) }}">Igen!</a>
                            <button class="btn btn-default" type="button" data-dismiss="modal">Mégse</button>
                        </div>
                    </div>
                </div>
            </div>

        @endif
    @endforeach
@endsection
