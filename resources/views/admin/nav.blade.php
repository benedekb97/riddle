<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('admin.index') }}">Riddle.sch <b>Admin</b></a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav navbar-right">
                <li><a href="{{ route('index') }}">Rendes oldal</a></li>
                <li><a href="{{ route('admin.index') }}">Irányítópult</a></li>
                <li><a href="{{ route('admin.settings') }}">Beállítások</a></li>
                <li><a href="{{ route('admin.profile') }}">Profilom</a></li>
            </ul>
        </div>
    </div>
</nav>
