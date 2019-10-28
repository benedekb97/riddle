@extends('layouts.admin')

@section('title','Statikus Üzenetek')
@section('active.static_messages','active')

@section('content')
    <div class="row">
        <h2 class="page-header">Statikus Üzenetek</h2>
        <input class="btn btn-primary" type="button" data-toggle="modal" data-target="#new_message" value="Új üzenet"><br><br>
        @foreach($messages as $message)
            <div class="alert alert-{{ $message_types[$message->type] }}">
                <a class="close" href="#" data-toggle="modal" data-target="#delete_message_{{ $message->id }}">
                    <i class="fa fa-trash"></i>&nbsp;&nbsp;
                </a>
                <a class="close" href="#" data-toggle="modal" data-target="#edit_message_{{ $message->id }}">
                    <i class="fa fa-edit"></i>&nbsp;&nbsp;
                </a>
                @if($message->number!=$messages->count())
                    <a href="{{ route('admin.static_messages.move_down', ['message' => $message]) }}" class="close">
                        <i class="fa fa-arrow-down"></i>&nbsp;&nbsp;
                    </a>
                @endif
                @if($message->number!=1)
                    <a href="{{ route('admin.static_messages.move_up', ['message' => $message]) }}" class="close">
                        <i class="fa fa-arrow-up"></i>&nbsp;&nbsp;
                    </a>
                @endif
                <h4><i class="fa {{ $message_icons[$message->type] }}"></i>&nbsp;&nbsp;{{ $message->title }}</h4><br>
                {{ $message->message }}
            </div>
        @endforeach
    </div>

@endsection

@section('modals')
    @foreach($messages as $message)
        <div class="modal fade" id="edit_message_{{ $message->id }}">
            <form action="{{ route('admin.static_messages.edit', ['message' => $message]) }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal" type="button">&times;</button>
                            <h4 class="modal-title">Üzenet szerkesztése</h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="input-group-addon" for="title">Cím</label>
                                    <input type="text" class="form-control" id="title" name="title{{ $message->id }}" value="{{ $message->title }}" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="input-group-addon" for="message">Üzenet</label>
                                    <textarea style="resize:vertical;" class="form-control" id="message" name="message{{ $message->id }}" required>{{ $message->message }}</textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="input-group">
                                    <label class="input-group-addon" for="type">Típus</label>
                                    <select name="type{{ $message->id }}" id="type" class="form-control" required>
                                        <option value="0" @if($message->type==0) selected @endif>Információ</option>
                                        <option value="1" @if($message->type==1) selected @endif>Figyelmeztetés</option>
                                        <option value="2" @if($message->type==2) selected @endif>Sárga figyelmeztetés</option>
                                        <option value="3" @if($message->type==3) selected @endif>Zöld információ</option>
                                        <option value="4" @if($message->type==4) selected @endif>Sötétkék információ</option>
                                        <option value="5" @if($message->type==5) selected @endif>Sima</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
                            <input type="submit" class="btn btn-primary" value="Mentés">
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal fade" id="delete_message_{{ $message->id }}">
            <form action="{{ route('admin.static_messages.delete', ['message' => $message]) }}" method="POST">
                {{ csrf_field() }}
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button class="close" data-dismiss="modal" type="button">&times;</button>
                            <h4 class="modal-title">Biztosan törlöd az üzenetet?</h4>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-default" data-dismiss="modal" type="button">Mégse</button>
                            <input type="submit" class="btn btn-danger" value="Igen">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @endforeach
    <div class="modal fade" id="new_message">
        <form action="{{ route('admin.static_messages.new') }}" method="POST">
            {{ csrf_field() }}
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                        <h4 class="modal-title">Új üzenet</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="title">Cím</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="message">Üzenet</label>
                                <textarea style="resize:vertical;" class="form-control" id="message" name="message" required></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="input-group">
                                <label class="input-group-addon" for="type">Típus</label>
                                <select name="type" id="type" class="form-control" required>
                                    <option value="0">Információ</option>
                                    <option value="1">Figyelmeztetés</option>
                                    <option value="2">Sárga figyelmeztetés</option>
                                    <option value="3">Zöld információ</option>
                                    <option value="4">Sötétkék információ</option>
                                    <option value="5">Sima</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Mégse</button>
                        <input type="submit" class="btn btn-primary" value="Mentés">
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
