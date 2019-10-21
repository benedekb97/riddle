@extends('layouts.admin')

@section('active.moderators','active')

@section('title','Moderators')

@section('content')
    <div class="row">
        <h2 class="page-header">Moderátorok</h2>
        <form action="{{ route('admin.moderators.add') }}" method="POST">
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="hidden" name="user_id" id="user_id">
            <div class="col-md-6">
                <input type="hidden" id="post_url" name="post_url" value="{{ route('admin.moderators.search') }}">
                <div class="form-group">
                    <div class="input-group autocomplete_box">
                        <label class="input-group-addon" for="name_search">Név</label>
                        <input placeholder="Keresés" type="text" name="name_search" id="name_search" class="form-control">
                        <div class="autocomplete" id="autocomplete"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <input type="submit" value="Hozzáadás" class="btn btn-primary" disabled id="submit_button">
            </div>
        </form>
    </div>

    <div class="row">
        <div class="table-responsive">
            <table class="table table-striped">
                <tr>
                    <th>Név</th>
                    <th>Email cím</th>
                    <th>AuthSCH</th>
                    <th>Jelszó</th>
                    <th>Elfogadott riddle-ök</th>
                    <th>Visszadobott riddle-ök</th>
                    <th>Műveletek</th>
                </tr>
                @foreach($moderators as $moderator)
                    <tr>
                        <td>{{ $moderator->name }}</td>
                        <td>{{ $moderator->email }}</td>
                        <td>
                            @if($moderator->internal_id!=null)
                                <i class="fa fa-check"></i>
                            @else
                                <i class="fa fa-times"></i>
                            @endif
                        </td>
                        <td>
                            @if($moderator->password!=null)
                                <i class="fa fa-check"></i>
                            @else
                                <i class="fa fa-times"></i>
                            @endif
                        </td>
                        <td>{{ $moderator->riddlesApprovedBy()->count() }}</td>
                        <td>{{ $moderator->riddlesBlockedBy()->count() }}</td>
                        <td>
                        <span data-toggle="tooltip" title="Törlés">
                            <a type="button" class="btn btn-xs btn-danger" href="{{ route('admin.moderators.delete', ['user' => $moderator]) }}">
                                <i class="fa fa-times"></i>
                            </a>
                        </span>
                        </td>
                    </tr>
                @endforeach
            </table>
        </div>

    </div>
@endsection

@section('scripts')
<script src="{{ asset('js/admin_search.js') }}"></script>
@endsection
