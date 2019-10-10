@extends('layouts.home')

@section('title','Új Riddle')

@section('content')
    @if( isset($error_message))
        <div class="error">
            {{ $error_message }}
        </div>
    @endif
{{ Auth::user()->unsolvedRiddles() }}
    <form action="{{ route('riddles.save') }}" method="POST" enctype="multipart/form-data">
        {{ csrf_field() }}
        <label for="title">Cím</label><input type="text" placeholder="Cím" name="title" id="title">
        <label for="answer">Megoldás</label><input type="text" placeholder="Megoldás" name="answer" id="answer">
        <label for="riddle">Riddle</label><input type="file" name="riddle" id="riddle">
        <input type="submit" value="Mentés">
    </form>
@endsection
