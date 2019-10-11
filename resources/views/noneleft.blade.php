@extends('layouts.home')

@section('title','Nincs több ridli')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="alert alert-info">
                <h4><i class="fa fa-info-circle"></i> Sajnos elfogyott a ridli :'(</h4>
                <p>Ezt a problémát könnyen lehet orvosolni, <a href="{{ route('riddles.new') }}">ridlik gyártásával</a> ;)</p>
            </div>
        </div>
    </div>
@endsection
