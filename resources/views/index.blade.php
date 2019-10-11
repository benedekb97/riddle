@extends('layouts.home')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="alert alert-danger">
                <h4><i class="fa fa-exclamation-triangle"></i> Fejlesztés alatt!</h4>

                <p>Ha bármilyen hibát észlelsz, írj nekem egy email a benedekb97@gmail.com email címre :P</p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="alert alert-info">
                <h4><i class="fa fa-info-circle"></i> Hasznos információk:</h4>

                <ul>
                    <li>Jelenleg "<strong>nyílt-ß tesztelés</strong>" fázisban van az oldal</li>
                    <li><strong>Bárki</strong> tölthet fel riddle-t</li>
                    <li>Ezt el kell fogadnia egy <strong>moderátornak</strong>
                        <ul>
                            <li>Ha elfogadja akkor bekerül a riddle listába</li>
                            <li>Ha nem akkor kapsz egy indokot hogy miért szar</li>
                            <li>Miután kijavítottad megint el kell fogadni/visszadobni</li>
                            <li>stb.</li>
                        </ul>
                    </li>
                    <li>Ha moderátor felületet akarod tesztelni írj nekem (Beni)</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="alert alert-info">
                <h4><i class="fa fa-info-circle"></i> Pontozás:</h4>

                <ul>
                    <li>Minden riddle-nek van egy <strong>nehézsége</strong> (1-5ig)</li>
                    <li>Alapból ez <strong>5-el szorzódik</strong></li>
                    <li>Az elhasznált hintek száma <strong>levonódik</strong> az 5-ből</li>
                    <br>
                    <li><strong>Röviden: pontok = nehézség*max(1,5-felhasznált hintek)</strong></li>
                </ul>
            </div>
        </div>
    </div>
@endsection
