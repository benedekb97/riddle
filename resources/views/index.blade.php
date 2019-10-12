@extends('layouts.home')

@section('content')
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="alert alert-danger">
                <h4><i class="fa fa-exclamation-triangle"></i>&nbsp;&nbsp;Fejlesztés alatt!</h4>

                <p>Ha bármilyen hibát észlelsz, írj nekem egy email a benedekb97@gmail.com email címre :P</p>
                <p>Github: <a href="http://github.com/benedekb97/riddle">github.com/benedekb97/riddle</a></p>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="alert alert-info">
                <h4><i class="fa fa-info-circle"></i>&nbsp;&nbsp;Hasznos információk:</h4>

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
                <br>
                <h4><i class="fa fa-info-circle"></i>&nbsp;&nbsp;Újítások:</h4>

                <ul>
                    <li>Rossz próbálkozásnál kiírja hogy rossz</li>
                    <li>Ékezeteket nem veszi figyelembe</li>
                    <li>Riddle-ket moderátorok sorrendbe tudják helyezni miután elfogadták őket, csak akkor lehet őket megoldani ha már benne vannak a listában</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-md-push-3">
            <div class="alert alert-info">
                <h4><i class="fa fa-info-circle"></i>&nbsp;&nbsp;Pontozás:</h4>

                <ul>
                    <li>Saját riddle-ért nem kapsz pontot</li>
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
