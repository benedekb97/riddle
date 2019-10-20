@extends('layouts.admin')

@section('active.logs','active')

@section('title','Logok')

@section('content')
    <div class="row">
        <h2 class="page-header">Logok</h2>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Logok</h3>
                </div>
                <div class="table-responsive">
                    <table id="logs" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Log</th>
                                <th>Adat</th>
                                <th>Oldal</th>
                                <th>Felhasználó</th>
                                <th>IP</th>
                                <th>Riddle</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(function () {
            $('#logs').DataTable({
                serverSide: true,
                processing: true,
                ajax: '/admin/logs/data',
                columns: [
                    {data: 'type'},
                    {data: 'data'},
                    {data: 'page'},
                    {data: 'user_id', name: 'user_id'},
                    {data: 'ip'},
                    {data: 'riddle_id', name: 'riddle_id'},
                    {data: 'created_at'},
                ]
            });
        });
    </script>
@endsection
