@extends('layouts.home')

@section('title',$riddle->title)

@section('content')
    @if($riddle->user == Auth::user())

    @endif
    <img alt="{{ $riddle->title }}" src="{{ route('riddles.get', ['riddle' => $riddle]) }}">
@endsection
