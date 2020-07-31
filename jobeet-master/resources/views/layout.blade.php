<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=1024">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <!-- ========== ogp ========== -->
    <!-- Latest compiled and minified CSS -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <!-- Optional theme -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css"
          integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/mystyle.css') }}">
@yield('stylesheet')
<!-- Latest compiled and minified JavaScript -->
    <!-- ========== script ========== -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    {{--<script defer src="https://use.fontawesome.com/releases/v5.0.2/js/all.js"></script>--}}

    @yield('script')
</head>
<body>
<div class="wrapper">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div id="app">
        <nav class="navbar navbar-default navbar-static-top">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                            data-target="#app-navbar-collapse" aria-expanded="false">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ url('/') }}">
                        {{ config('app.name', 'Laravel') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <li><a href="{{ route('register') }}">Register</a></li>
                            @else
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button"
                                       aria-expanded="false" aria-haspopup="true">
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                  style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        @include('flash::message')
        @yield('content')
        <div class="container">
            <div class="snow-time">
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
                <div class="snow">
                    <i class="far fa-snowflake"></i>
                </div>
            </div>
        </div>

    </div>
</div>
<script src="{{ asset('js/app.js') }}"></script>
<script src="{{ asset('js/HidingFlashMessages.js') }}"></script>
</body>
</html>