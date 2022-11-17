<ul class="list-unstyled components navbar-nav">
    <li>
        <a href="{{ route('media-project-index') }}" @if(request()->is('media-project-index')) class="active" @endif>Media Project</a>
    </li>
    <li>
        <a href="{{ route('media-source-index') }}">Media Source</a>
    </li>
    <li>
        <a href="#">About</a>
    </li>

    <li>
        <a href="{{ route('logout') }}">Logout</a>
    </li>

    <li>
        <a class="drop-down" href="#pageSubmenu" data-toggle="collapse" aria-expanded="false">Pages
            <i class="fa fa-caret-down"> </i>
        </a>
        <ul class="collapse list-unstyled navbar-nav" id="pageSubmenu">
            <li><a href="#">Page 1</a></li>
            <li><a href="#">Page 2</a></li>
            <li><a href="#">Page 3</a></li>
        </ul>
    </li>
</ul>