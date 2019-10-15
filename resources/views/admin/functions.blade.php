@extends('layouts.admin')

@section('title','Speciális funkciók')

@section('active.functions','active')

@section('content')
    <div class="row">
        <h2 class="page-header">Speciális funkciók</h2>
        <div class="col-md-4">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">Riddle reset</h3>
                </div>
                <div class="panel-body">
                    <p>Mindenkinek visszaáll az aktuális riddle-je a legkisebb számúra a sorozatban.<br><i>Pl Sorrend rendezgetés után</i></p>
                </div>
                <div class="panel-footer">
                    <a class="btn btn-danger" href="{{ route('admin.functions.reset_current_riddles') }}">Reset</a>
                </div>
            </div>
        </div>
    </div>
@endsection
