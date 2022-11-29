<ul class="list-unstyled components navbar-nav">
    @php
        $routeName = request()->route()->getName();
    @endphp

    <li @if(in_array($routeName,['media-project-index','media-project-create','media-project-edit'])) class="active" @endif>
        <a href="{{ route('media-project-index') }}">Media Project</a>
    </li>

    <li @if(in_array($routeName,['media-source-index','media-source-create','media-source-edit'])) class="active" @endif>
        <a href="{{ route('media-source-index') }}">Media Source</a>
    </li>

    <li @if(request()->route()->getName() == 'about') class="active" @endif>
        <a href="#">About</a>
    </li>

    <li>
        <a href="{{ route('logout') }}">Logout</a>
    </li>

    {{-- <li>
        <a class="drop-down" href="#pageSubmenu" data-toggle="collapse" aria-expanded="false">Pages
            <i class="fa fa-caret-down"> </i>
        </a>
        <ul class="collapse list-unstyled navbar-nav" id="pageSubmenu">
            <li><a href="#">Page 1</a></li>
            <li><a href="#">Page 2</a></li>
            <li><a href="#">Page 3</a></li>
        </ul>
    </li> --}}
</ul>