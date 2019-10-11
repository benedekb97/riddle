@extends('layouts.home')

@section('title','Profil szerkesztése')

@section('content')
    <div class="row">
        <div class="col-md-12">
                <ul class="nav nav-tabs" role="tablist">
                    <li>
                        <a href="{{ route('users.profile') }}">Profilom</a>
                    </li>
                    <li class="active">
                        <a href="#">Szerkesztés</a>
                    </li>
                </ul>
                <form action="{{ route('users.profile.save') }}" method="POST">
                    {{ csrf_field() }}
                    <div class="panel panel-default" style="border-top:0;">
                    <div class="panel-body">
                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="nickname">Becenév</label>
                                <input value="{{ Auth::user()->nickname }}" type="text" id="nickname" name="nickname" class="form-control" placeholder="Becenév">
                            </div>
                        </div>
                    </div>
                    <div class="panel-footer">
                        <input type="submit" value="Mentés" class="btn btn-primary">
                        <a href="{{ route('users.profile') }}" class="btn btn-default">Mégsem</a>
                    </div>
                    </div>
                </form>
        </div>
    </div>
@endsection
