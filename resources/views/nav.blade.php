<nav class="navbar navbar-inverse">
    <div class="container">
        <div class="navbar-header">
            <button class="navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#navbar">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('index') }}">Riddle.sch</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="{{ route('index') }}">Home</a></li>
                <li><a href="{{ route('riddles.all') }}">Eddigiek</a></li>
                <li><a href="{{ route('riddles.current') }}">Aktuális</a></li>
                <li><a href="{{ route('users.list') }}">Rangsor</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                @if(Auth::user()->moderator)
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button">Moderálás <span class="caret"></span></a>
                        <ul class="dropdown-menu">
                            <li><a href="{{ route('riddles.unapproved') }}">Elfogadásra váró Riddle-k</a></li>
                            <li><a href="{{ route('riddles.blocked') }}">Tiltott Riddle-k</a></li>
                            <li><a href="{{ route('users.creators') }}">Riddle készítők</a></li>
                            <li><a href="{{ route('riddles.sequence') }}">Sorrend</a></li>
                        </ul>
                    </li>
                @endif
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ route('users.profile') }}">Profilom</a></li>
                        <li><a href="{{ route('users.riddles') }}">Riddle-jeim</a></li>
                        <li><a href="{{ route('riddles.new') }}">Riddle feltöltése</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
