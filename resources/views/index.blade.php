@extends('layouts.home')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="alert alert-info">
                <h4><i class="fa fa-info-circle"></i> Hasznos információk:</h4>

                <ul>
                    <li>Jelenleg bárki feltölthet riddle-t.</li>
                    <li>Ezt el kell fogadnia egy moderátornak
                        <ul>
                            <li>Ha elfogadja akkor a /riddle/ID címen érhető el</li>
                            <li>Ha nem akkor kapsz egy indokot hogy miért szar</li>
                            <li>Miután kijavítottad megint el kell fogadnia/visszadobni</li>
                            <li>stb.</li>
                        </ul>
                    </li>
                    <li>Ha moderátor felületet akarod tesztelni írj nekem (Beni)</li>
                    <li>Saját riddle-t is megoldhatsz tesztelés céljából</li>
                    <li>LIVE-on az lesz, hogy csak a nem általad elfogadott riddle-öket oldhatod meg moderátorként</li>
                </ul>
            </div>
        </div>
    </div>
@endsection
