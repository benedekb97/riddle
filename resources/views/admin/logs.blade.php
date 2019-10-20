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
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->type }}</td>
                                    <td>{{ $log->data }}</td>
                                    <td>{{ $log->page }}</td>
                                    <td>@if($log->user != null) {{ $log->user->name }} @endif</td>
                                    <td>{{ $log->ip }}</td>
                                    <td>@if($log->riddle!=null) {{ $log->riddle->id }} @endif</td>
                                    <td>{{ $log->created_at }}</td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    $('#logs').dataTable();
</script>
@endsection
