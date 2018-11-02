<nav class="navbar navbar-default navbar-static-top">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle Collapsed" data-toggle="collapse" data-target="">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ url('/') }}">LaraBBS</a>
        </div>

        <div class="collapse navbar-collapse" id="app-header-collapse">
            <ul class="nav navbar-nav">

            </ul>
            <ul class="nav navbar-nav navbar-right">
                @guest
                    <li><a href="{{ route('register') }}">注册</a></li>
                    <li><a href="{{ route('login') }}">登录</a></li>
                @else
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" type="button" id="dropdownMenu" data-toggle="dropdown">
                            <span class="user-avatar pull-left" style="margin-right:8px; margin-top:-5px;">
                                <img src="{{ Auth::user()->avatar }}" class="img-responsive img-circle" width="30px" height="30px">
                            </span>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('users.edit', Auth::id()) }}">
                                    编辑资料
                                </a>
                            </li>
                            <li><a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();">退出登录</a></li>
                            <form action="{{ route('logout') }}" method="post" id="logout-form" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </ul>
                    </li>
                @endguest
            </ul>
        </div>
    </div>
</nav>