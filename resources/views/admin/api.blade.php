@extends('layouts.admin')

@section('title','API Beállítások')

@section('active.api','active')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">API kulcsok újragenerálása</h3>
                </div>
                <div class="panel-body">
                    Újragenerálja az összes felhasználó API kulcsát
                </div>
                <div class="panel-footer">
                    <a href="{{ route('admin.api.generate_keys') }}" class="btn btn-primary">GO!</a>
                </div>
            </div>
        </div>
    </div>
@endsection
