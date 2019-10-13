@extends('layouts.home')

@section('content')
    @foreach($messages as $message)
        <div class="row">
            <div class="col-md-6 col-md-push-3">
                <div class="alert alert-{{ $message_types[$message->type] }}">
                    <h4><i class="fa {{ $message_icons[$message->type] }}"></i>&nbsp;&nbsp;{{ $message->title }}</h4><br>
                    {!! html_entity_decode($message->message)  !!}
                </div>
            </div>
        </div>
    @endforeach
@endsection
