@extends('layouts.home')

@section('title','Mótvák')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Duplikált riddle-k (Felhasználók által jelentett)</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-striped" style="width:calc(100% - 1px);">
                        <tr>
                            <th style="text-align:center; border-right:1px solid rgba(0,0,0,0.2);" colspan="3">Eredeti riddle</th>
                            <th style="text-align:center;" colspan="3">Jelentett riddle</th>
                            <th style="border-left:1px solid rgba(0,0,0,0.1); vertical-align:middle; text-align:center;" rowspan="2">Műveletek</th>
                        </tr>
                        <tr>
                            <th style="text-align:center;">Cím</th>
                            <th style="text-align:center;">Kép</th>
                            <th style="text-align:center; border-right:1px solid rgba(0,0,0,0.2);">Megoldás</th>
                            <th style="text-align:center;">Cím</th>
                            <th style="text-align:center;">Kép</th>
                            <th style="text-align:center;">Megoldás</th>
                        </tr>
                        @foreach($duplicates as $duplicate)
                            <tr>
                                <td style="text-align:center;">{{ $duplicate->first()->riddle->title }}</td>
                                <td style="text-align:center;">{{ $duplicate->first()->riddle->title }}</td>
                                <td style="text-align:center; border-right:1px solid rgba(0,0,0,0.2);">{{ $duplicate->first()->riddle->answer }}</td>
                                <td style="text-align:center;">{{ $duplicate->first()->duplicate->title }}</td>
                                <td style="text-align:center;">{{ $duplicate->first()->duplicate->title }}</td>
                                <td style="text-align:center; border-right:1px solid rgba(0,0,0,0.2);">{{ $duplicate->first()->duplicate->answer }}</td>
                                <td style="text-align:center;">
                                    <span data-toggle="tooltip" title="Jelentés törlése">
                                        <button data-toggle="modal" data-target="#delete_report_{{ $duplicate->first()->id }}" class="btn btn-xs btn-success">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </span>
                                    <span data-toggle="tooltip" title="Jelentett Riddle törlése">
                                        <button data-toggle="modal" data-target="#delete_riddle_{{ $duplicate->first()->id }}" class="btn btn-xs btn-danger">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </span>
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

@endsection
