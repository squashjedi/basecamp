<ul class="nav navbar-nav navbar-right">
    <!-- Authentication Links -->
    @if (Auth::guest())
        <li><a href="{{ route('login') }}">Log In</a></li>
        <li style="padding-right:15px;"><a href="{{ route('register') }}">Sign Up</a></li>
    @else
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                {{ Auth::user()->name }} <span class="caret"></span>
            </a>

            <ul class="dropdown-menu" role="menu">
                <li><a href="/"><i class="fa fa-home" aria-hidden="true"></i> Homepage</a></li>
                @if (Auth::user()->roles()->first())
                    @if (Auth::user()->roles()->first()->role == 'webmaster')
                        <li><a href="{{ route('admin.users') }}"><i class="fa fa-tachometer" aria-hidden="true"></i> Admin</a></li>
                    @endif
                @endif
                <li><a href="{{ route('settings.account') }}"><i class="fa fa-cog" aria-hidden="true"></i> Settings</a></li>
                <li>
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                        <i class="fa fa-sign-out" aria-hidden="true"></i> Logout
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </li>
            </ul>
        </li>
    @endif
</ul>