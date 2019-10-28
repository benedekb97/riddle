@extends('layouts.admin')

@section('title','API Beállítások')

@section('active.api','active')

@section('content')
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <h3 class="panel-title">API kulcsok törlése</h3>
                </div>
                <div class="panel-body">
                    Törli az összes felhasználó összes API kulcsát<br>
                    <i>Kiléptet mindenkit ha API-ról van belépve</i>
                </div>
                <div class="panel-footer">
                    <a href="{{ route('admin.api.generate_keys') }}" class="btn btn-primary">GO!</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <h3 class="panel-title">Lejárt API kulcsok törlése</h3>
                </div>
                <div class="panel-body">
                    Törli az összes felhasználó lejárt API kulcsait<br>
                </div>
                <div class="panel-footer">
                    <a href="{{ route('admin.api.delete_invalid_keys') }}" class="btn btn-primary">GO!</a>
                </div>
            </div>
        </div>
    </div>
@endsection
