@extends('layouts.admin')

@section('title','API logok')

@section('active.logs','active')

@section('content')
    <div class="row">
        <h2 class="page-header">Moderátor Logok | <a href="{{ route('admin.logs.index') }}">Vissza</a></h2>
        <div class="col-md-12">
            <div class="table-responsive">
                <table class="table table-striped" id="api_logs">
                    <thead>
                        <tr>
                            <th>Típus</th>
                            <th>Adat</th>
                            <th>Oldal</th>
                            <th>Felhasználó</th>
                            <th>Riddle</th>
                            <th>Mikor</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#api_logs').DataTable({
                serverSide: true,
                processing: true,
                ajax: '/admin/logs/moderator/data',
                columns: [
                    {data: 'description', name: 'description'},
                    {data: 'data'},
                    {data: 'page'},
                    {
                        data: 'user_id',
                        name: 'user_id'
                    },
                    {
                        data: 'riddle_id',
                        name: 'riddle_id'
                    },
                    {
                        data: 'created_at_lol',
                        name: 'created_at_lol'
                    },
                    {
                        data: 'ip',
                        name: 'ip'
                    }
                ]
            });
        });
    </script>
@endsection
