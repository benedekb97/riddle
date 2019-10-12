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
                            <th style="text-align:center; border-right:1px solid rgba(0,0,0,0.2);" colspan="2">Eredeti riddle</th>
                            <th style="text-align:center;" colspan="2">Jelentett riddle</th>
                            <th style="border-left:1px solid rgba(0,0,0,0.1); vertical-align:middle; text-align:center;" rowspan="2">Megoldás egyezik?</th>
                            <th style="border-left:1px solid rgba(0,0,0,0.1); vertical-align:middle; text-align:center;" rowspan="2">Műveletek</th>
                        </tr>
                        <tr>
                            <th style="text-align:center;">Cím</th>
                            <th style="text-align:center;  border-right:1px solid rgba(0,0,0,0.2);">Kép</th>
                            <th style="text-align:center;">Cím</th>
                            <th style="text-align:center;">Kép</th>
                        </tr>
                        @foreach($duplicates as $duplicate)
                            <tr>
                                <td style="text-align:center;">{{ $duplicate->first()->riddle->title }}</td>
                                <td style="text-align:center; border-right:1px solid rgba(0,0,0,0.2);">{{ $duplicate->first()->riddle->title }}</td>
                                <td style="text-align:center;">{{ $duplicate->first()->duplicate->title }}</td>
                                <td style="text-align:center; border-right:1px solid rgba(0,0,0,0.2);">{{ $duplicate->first()->duplicate->title }}</td>
                                <td style="text-align:center; border-right:1px solid rgba(0,0,0,0.2);">
                                    @if($duplicate->first()->riddle->compare($duplicate->first()->duplicate))
                                        Igen
                                    @else
                                        Nem
                                    @endif
                                </td>
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
    @foreach($duplicates as $duplicate)
        <div class="modal fade" id="delete_report_{{ $duplicate->first()->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h4 class="modal-title">Biztosan törlöd a jelentést?</h4>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('riddles.duplicates.delete.report', ['duplicate' => $duplicate->first()]) }}" class="btn btn-success">Igen</a>
                        <button data-dismiss="modal" class="btn btn-danger" type="button">Mégse</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="delete_riddle_{{ $duplicate->first()->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h4 class="modal-title">Biztosan törlöd a "mótvá" riddle-t?</h4>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th>Cím</th>
                                <td>{{ $duplicate->first()->duplicate->title }}</td>
                            </tr>
                            <tr>
                                <th>Kép</th>
                                <td>
                                    <a href="{{ route('riddles.get', ['riddle' => $duplicate->first()->duplicate_id]) }}" target="_blank" data-toggle="tooltip" title="Kép megtekintése" class="btn btn-xs btn-primary">
                                        <i class="fa fa-image"></i>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>Megoldás megegyezik?</th>
                                <td>@if($duplicate->first()->riddle->compare($duplicate->first()->duplicate)) Igen @else Nem @endif</td>
                            </tr>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <a href="{{ route('riddles.delete', ['riddle' => $duplicate->first()->duplicate_id]) }}" class="btn btn-success">Igen</a>
                        <button data-dismiss="modal" class="btn btn-danger" type="button">Mégse</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
