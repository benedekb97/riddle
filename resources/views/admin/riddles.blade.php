@extends('layouts.admin')

@section('active.riddles','active')

@section('title','Riddles')

@section('content')
    <div class="row">
        <h2 class="page-header">Riddles</h2>
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Riddles</h3>
                </div>
                <div class="table-responsive">
                    <table id="riddles" class="table table-striped">
                        <thead>
                            <tr>
                                <th>Szám</th>
                                <th>Cím</th>
                                <th>Megoldás</th>
                                <th>Hintek</th>
                                <th>Készítő</th>
                                <th>Elfogadva</th>
                                <th>Elfogadta</th>
                                <th>Tiltva</th>
                                <th>Tiltotta</th>
                                <th>Megoldva</th>
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
            $('#riddles').DataTable({
                serverSide: true,
                processing: true,
                ajax: '/admin/riddles/data',
                columns: [
                    {data: 'number'},
                    {data: 'title'},
                    {data: 'answer'},
                    {
                        data: 'hint',
                        name: 'hint',
                        render: function(val, _, obj){
                            var string = "";
                            val.forEach(function(e){
                                string += "<b>" + e.number + ".</b> " + e.hint + "<br>";
                            });

                            return string;
                        }
                    },
                    {data: 'user_id', name: 'user_id'},
                    {
                        data: 'approved',
                        name:'approved',
                        render: function(val, _, obj){
                            return "<i class='fa fa-" + val + "'></i>";
                        }
                    },
                    {data: 'approved_by', name: 'approved_by'},
                    {
                        data: 'blocked',
                        name:'blocked',
                        render: function(val, _, obj){
                            return "<i class='fa fa-" + val + "'></i>";
                        }},
                    {data: 'blocked_by', name:'blocked_by'},
                    {data: 'solved', name:'solved'}
                ]
            });
        });
    </script>
@endsection
