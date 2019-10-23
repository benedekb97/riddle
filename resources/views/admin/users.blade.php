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
                        <thead>
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
                        </thead>

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

@section('scripts')
    <script>
        $(function () {
            $('#users_table').DataTable({
                serverSide: true,
                processing: true,
                ajax: '/admin/users/data',
                columns: [
                    {data: 'email'},
                    {data: 'name'},
                    {data: 'id'},
                    {
                        data: 'solved_riddles',
                        name: 'solved_riddles'
                    },
                    {
                        data: 'riddles',
                        name: 'riddles'
                    },
                    {
                        data: 'moderator',
                        name: 'moderator',
                        render: function(val, _, obj){
                            return "<i class='fa fa-" + val + "'></i>";
                        }
                    },
                    {
                        data: 'admin',
                        name: 'admin',
                        render: function(val, _, obj){
                            return "<i class='fa fa-" + val + "'></i>";
                        }
                    },
                    {
                        data: 'internal_id',
                        name: 'internal_id',
                        render: function(val, _, obj){
                            return "<i class='fa fa-" + val + "'></i>";
                        }
                    },
                    {
                        data: 'password',
                        name: 'password',
                        render: function(val, _, obj){
                            return "<i class='fa fa-" + val + "'></i>";
                        }
                    },
                    {
                        data: 'blocked_riddles',
                        name: 'blocked_riddles'
                    },
                    {
                        data: 'avg_diff',
                        name: 'avg_diff'
                    },
                    {
                        data: 'block',
                        name: 'block',
                        render: function(val, _, obj){
                            if(val===true){
                                return "<span data-toggle=\"tooltip\" title=\"Blokkolnám\">\n" +
                                    "                                        <button type=\"button\" class=\"btn btn-xs btn-danger\" data-toggle=\"modal\" data-target=\"#block_" + obj.id + "\">\n" +
                                    "                                            <i class=\"fa fa-times\"></i>\n" +
                                    "                                        </button>\n" +
                                    "                                    </span>";
                            }else{
                                return "<span data-toggle=\"tooltip\" title=\"Unblokkolnám\">\n" +
                                    "                                        <button type=\"button\" class=\"btn btn-xs btn-success\" data-toggle=\"modal\" data-target=\"#unblock_" + obj.id + "\">\n" +
                                    "                                            <i class=\"fa fa-check\"></i>\n" +
                                    "                                        </button>\n" +
                                    "                                    </span>";
                            }
                        }
                    }
                ]
            });
        });
    </script>
@endsection
